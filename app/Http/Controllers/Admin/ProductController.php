<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Document;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::query()->select(['id', 'sku', 'price', 'is_active', 'is_featured', 'created_at']);
            return DataTables::of($products)
                ->addColumn('title', function ($row) {
                    return $row->translate('title') ?? 'Untitled';
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_active) {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>';
                    }
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">Draft</span>';
                })
                ->addColumn('featured', function ($row) {
                    return $row->is_featured ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>' : '';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.products.edit', $row->id);
                    return view('admin.products.partials._datatable_actions', compact('editUrl','row'))->render();
                })
                ->rawColumns(['status','featured','actions'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    public function create()
    {
        $existing = Product::where('is_active', 0)
            ->where('created_by', auth()->id())
            ->latest()
            ->first();

        if ($existing) {
            return redirect()->route('admin.products.edit', $existing->id);
        }

        $sku = 'draft-' . now()->format('YmdHis') . '-' . auth()->id();
        $draft = Product::create([
            'title' => ['en' => 'Untitled Product'], // store minimal JSON
            'sku' => $sku,
            'slug' => Str::slug('Untitled Product') . '-' . Str::random(6),
            'is_active' => 0,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.products.edit', $draft->id);
    }

    public function edit(Product $product)
    {
        // eager load small relations
        $product->load('categories','brand','variants','documents');
        return view('admin.products.form', compact('product'));
    }

    /**
     * Autosave (for drafts). Keeps product as draft (is_active = 0).
     */
    public function autosave(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'nullable|array',
            'title.*' => 'nullable|string|max:191',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
            'sku' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'track_stock' => 'nullable|boolean',
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
        ]);

        DB::beginTransaction();
        try {
            // basic scalar fields
            $product->update([
                'sku' => $request->input('sku', $product->sku),
                'price' => $request->input('price', $product->price),
                'compare_at_price' => $request->input('compare_at_price', $product->compare_at_price),
                'stock_quantity' => $request->input('stock_quantity', $product->stock_quantity ?? 0),
                'track_stock' => $request->has('track_stock') ? (bool) $request->input('track_stock') : $product->track_stock,
                'brand_id' => $request->input('brand_id', $product->brand_id),
                // keep draft
                'is_active' => 0,
            ]);

            // translations
            $product->setTranslationsFromRequest($request->only(['title','description']));

            // categories sync (optional single selection here; adapt if you keep many-to-many)
            if ($request->filled('category_id')) {
                $product->categories()->sync([$request->input('category_id')]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Draft saved automatically.',
                'updated_at' => now()->toDateTimeString()
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>'Failed to autosave','error'=>$e->getMessage()],500);
        }
    }

    /**
     * Update (full save without publishing). Accepts images + variants.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|array',
            'title.*' => 'required|string|max:191',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
            'sku' => ['required','string', Rule::unique('products','sku')->ignore($product->id)],
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'track_stock' => 'nullable|boolean',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            // images: new_main_image, new_gallery[] and attachments to existing docs via ids
            'new_main_image' => 'nullable|file|image|max:5120',
            'new_gallery.*' => 'nullable|file|image|max:5120',
            'gallery_remove_ids' => 'nullable|array',
            'gallery_remove_ids.*' => 'integer|exists:documents,id',
            // variants json
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.sku' => 'required_with:variants|string',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.options' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // update product fields
            $product->update([
                'sku' => $validated['sku'],
                'price' => $validated['price'],
                'compare_at_price' => $validated['compare_at_price'] ?? null,
                'stock_quantity' => $validated['stock_quantity'] ?? $product->stock_quantity,
                'track_stock' => (bool) ($validated['track_stock'] ?? $product->track_stock),
                'brand_id' => $validated['brand_id'] ?? $product->brand_id,
                'is_featured' => $request->has('is_featured') ? (bool)$request->input('is_featured') : $product->is_featured,
                'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : $product->is_active,
            ]);

            // translations (title/description)
            $product->setTranslationsFromRequest($request->only(['title','description']));

            // categories (single)
            if ($request->filled('category_id')) {
                $product->categories()->sync([$request->input('category_id')]);
            }

            // images: remove gallery items
            if ($request->filled('gallery_remove_ids')) {
                Document::whereIn('id', $request->input('gallery_remove_ids'))
                    ->where('documentable_type', Product::class)
                    ->delete();
            }

            if ($request->filled('existing_main_document_id')) {
                Document::where('id', $request->input('existing_main_document_id'))->update([
                    'documentable_id' => $product->id,
                    'documentable_type' => Product::class,
                    'document_type' => 'main',
                ]);
            }

            // attach new main image
            if ($request->hasFile('new_main_image')) {
                $file = $request->file('new_main_image');
                $path = $file->store('documents', 'public');
                $docData = [
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'document_type' => 'main',
                ];
                // remove old main if exists (or keep history if you prefer)
                $product->documents()->where('document_type','main')->delete();
                $product->documents()->create($docData);
            }

            // attach new gallery images
            if ($request->hasFile('new_gallery')) {
                foreach ($request->file('new_gallery') as $file) {
                    $path = $file->store('documents', 'public');
                    $docData = [
                        'name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientOriginalExtension(),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'document_type' => 'gallery',
                    ];
                    $product->documents()->create($docData);
                }
            }

            // sync variants
            if ($request->has('variants')) {
                $this->syncVariants($product, $request->input('variants'));
            }

            DB::commit();

            return response()->json(['success'=>true,'message'=>'Product saved.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>'Save failed','error'=>$e->getMessage()],500);
        }
    }

    /**
     * Publish: mark as active and set published_at
     * This endpoint will also perform a save (delegates to update)
     */
    public function publish(Request $request, Product $product)
    {
        // Reuse validation from update, but we accept same fields via form-data
        $res = $this->update($request, $product);
        if ($res->getStatusCode() !== 200) {
            return $res;
        }

        // now mark published
        $product->update([
            'is_active' => 1,
            'published_at' => now(),
        ]);

        return response()->json(['success'=>true,'message'=>'Product published successfully.']);
    }

    /**
     * Sync variants array: each variant item may have id (update) or no id (create).
     * Each item: ['id'=>..., 'sku'=>'', 'price'=>'', 'stock_quantity'=>int, 'options'=>[]]
     */
    protected function syncVariants(Product $product, array $variants)
    {
        $incomingIds = [];
        foreach ($variants as $v) {
            if (!empty($v['id'])) {
                // update existing
                $variant = ProductVariant::find($v['id']);
                if (!$variant || $variant->product_id != $product->id) continue;
                $variant->update([
                    'sku' => $v['sku'],
                    'price' => $v['price'],
                    'stock_quantity' => $v['stock_quantity'] ?? $variant->stock_quantity,
                    'options' => $v['options'] ?? $variant->options,
                ]);
                $incomingIds[] = $variant->id;
            } else {
                // create new
                $new = $product->variants()->create([
                    'sku' => $v['sku'],
                    'price' => $v['price'],
                    'stock_quantity' => $v['stock_quantity'] ?? 0,
                    'options' => $v['options'] ?? [],
                ]);
                $incomingIds[] = $new->id;
            }
        }

        // Delete any variants that were removed by the UI
        $product->variants()->whereNotIn('id', $incomingIds)->delete();
    }
}

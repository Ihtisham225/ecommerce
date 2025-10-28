<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::query()->select(['id', 'sku', 'price', 'is_active', 'is_featured', 'created_at']);

            return DataTables::of($products)
                ->addColumn('title', function ($row) {
                    // use your polymorphic translate helper
                    return $row->translate('title') ?? 'Untitled';
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_active) {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>';
                    }
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">Draft</span>';
                })
                ->addColumn('featured', function ($row) {
                    return $row->is_featured
                        ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>'
                        : '';
                })
                ->addColumn('actions', function ($row) {
                    $showUrl = route('admin.products.show', $row->id);
                    $editUrl = route('admin.products.edit', $row->id);
                    return view('admin.products.partials._datatable_actions', compact('showUrl', 'editUrl', 'row'))->render();
                })
                ->rawColumns(['status','featured','actions'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    /**
     * Bulk action handler
     * Expects: ids[] (array), action (string)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:products,id',
            'action' => 'required|string'
        ]);

        $ids = $request->input('ids', []);
        $action = $request->input('action');

        DB::beginTransaction();
        try {
            switch ($action) {
                case 'delete':
                    Product::whereIn('id', $ids)->delete();
                    break;

                case 'publish':
                    Product::whereIn('id', $ids)->update(['is_active' => 1]);
                    break;

                case 'unpublish':
                    Product::whereIn('id', $ids)->update(['is_active' => 0]);
                    break;

                case 'feature':
                    Product::whereIn('id', $ids)->update(['is_featured' => 1]);
                    break;

                case 'unfeature':
                    Product::whereIn('id', $ids)->update(['is_featured' => 0]);
                    break;

                default:
                    return response()->json(['success' => false, 'message' => 'Unknown action'], 422);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Bulk action completed.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bulk action failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the main product create page (Shopify-style editor)
     */
    public function create()
    {
        $existing = Product::where('is_active', 0)
            ->where('created_by', auth()->id())
            ->latest()
            ->first();

        if ($existing) {
            return redirect()->route('admin.products.edit', $existing->id);
        }

        // Create a unique temp SKU and slug
        $uniqueId = uniqid();
        $title = 'Untitled Product';
        $sku = 'draft-' . now()->format('YmdHis') . '-' . auth()->id();
        $slug = Str::slug($title) . '-' . Str::random(6);

        $draft = Product::create([
            'title' => $title,
            'sku' => $sku,
            'slug' => $slug,
            'is_active' => 0,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.products.edit', $draft->id);
    }



    /**
     * Display the product edit form (with AJAX sub-tabs)
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Handle auto-save (called via AJAX for drafts)
     */
    public function autosave(Request $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $product->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'sku' => $request->input('sku'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'category_id' => $request->input('category_id'),
                'is_active' => 0, // always keep draft mode
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Draft saved automatically.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to auto-save product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle final publish (when user clicks Save/Publish)
     */
    public function publish(Request $request, Product $product)
    {
        $product->update(['is_active' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Product published successfully.',
        ]);
    }
}

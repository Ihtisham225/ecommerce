<a href="{{ $showUrl }}" class="inline-flex items-center px-2 py-1 mr-1 rounded text-xs bg-blue-50 text-blue-700 hover:bg-blue-100">
    Show
</a>
<a href="{{ $editUrl }}" class="inline-flex items-center px-2 py-1 mr-1 rounded text-xs bg-gray-50 text-gray-800 hover:bg-gray-100">
    Edit
</a>
<button data-id="{{ $row->id }}" class="inline-flex items-center px-2 py-1 rounded text-xs bg-red-50 text-red-700 delete-btn hover:bg-red-100">
    Delete
</button>

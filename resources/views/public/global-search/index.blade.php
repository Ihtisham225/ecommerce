<div class="admin-navbar bg-white dark:bg-[#161615] border-b border-gray-200 dark:border-[#3E3E3A]">
    <div class="search-container px-4 py-2">
        <div class="search-input-wrapper relative">
            <input 
                type="text" 
                class="search-input w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                       bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 
                       placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                placeholder="{{__('Search across courses') }}" 
                autocomplete="off" 
                id="globalSearchInput"
            >
            <i class="fas fa-search search-icon absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
        </div>

        <!-- Results dropdown -->
        <div class="search-results mt-2 hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg" id="searchResults">
            <div class="view-all-results px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                {{ __("View all results for") }} "<span id="searchQueryText" class="font-medium"></span>"
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function() {
    const $searchInput = $('#globalSearchInput');
    const $searchResults = $('#searchResults');
    const $searchQueryText = $('#searchQueryText');
    let searchTimeout;

    // CSRF token for Laravel
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // Show results when focused
    $searchInput.on('focus', function() {
        $searchResults.addClass('active');
        updateQueryText();
    });

    // Hide results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-container').length) {
            $searchResults.removeClass('active');
        }
    });

    // Debounced search
    $searchInput.on('input', function() {
        clearTimeout(searchTimeout);
        updateQueryText();

        searchTimeout = setTimeout(function() {
            let query = $searchInput.val().trim();
            if (query.length > 2) {
                performSearch(query);
            } else {
                clearSearchResults();
            }
        }, 300);
    });

    function updateQueryText() {
        $searchQueryText.text($searchInput.val() || 'query');
    }

    function performSearch(query) {
        $.ajax({
            url: '/global-search',
            method: 'GET',
            data: { q: query },
            success: function(data) {
                displayResults(data, query);
            },
            error: function(err) {
                console.error('Search error:', err);
            }
        });
    }

    function displayResults(data, query) {
        clearSearchResults();

        $.each(data, function(model, items) {
            if (items.length === 0) return;

            let $section = $('<div>').addClass('result-section');
            let $sectionTitle = $('<div>')
                .addClass('result-section-title px-4 py-2 font-semibold text-gray-600 dark:text-gray-400')
                .html(`<i class="fas fa-search mr-2"></i> ${model}`);
            $section.append($sectionTitle);

            $.each(items, function(i, item) {
                let title = item.title ?? item.name ?? item.email ?? 'Untitled';
                let description = item.description ?? '';
                let href = '#';

                // 🔹 Use correct route slugs
                if (model.toLowerCase() === 'courses') {
                    href = `/courses/${item.slug}`;
                } else if (model.toLowerCase() === 'categories') {
                    href = `/categories/${item.slug}`; // ✅ FIXED: use slug not id
                } else {
                    href = `/${model}/${item.id}`;
                }

                let $resultItem = $(`
                    <div class="result-item px-4 py-2 flex items-start gap-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                        <div class="result-item-icon mt-1">
                            <i class="fas fa-search text-gray-500 dark:text-gray-400"></i>
                        </div>
                        <div>
                            <div class="result-item-title font-medium text-gray-800 dark:text-gray-100">${title}</div>
                            <div class="result-item-desc text-sm text-gray-500 dark:text-gray-400">${description}</div>
                        </div>
                    </div>
                `);

                $resultItem.on('click', function() {
                    window.location.href = href;
                });

                $section.append($resultItem);
            });

            $section.insertBefore($searchResults.find('.view-all-results'));
        });

        $searchResults.find('.view-all-results').off('click').on('click', function() {
            window.location.href = `/search/all?q=${encodeURIComponent(query)}`;
        });
    }

    function clearSearchResults() {
        $searchResults.find('.result-section').remove();
    }
});
</script>

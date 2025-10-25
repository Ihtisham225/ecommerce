<div class="admin-navbar bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800">
    <div class="search-container px-4 py-2">
        <div class="search-input-wrapper relative">
            <input 
                type="text" 
                class="search-input w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                       bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 
                       placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500" 
                placeholder="{{ __('Search across all models...') }}" 
                autocomplete="off" 
                id="globalSearchInput"
            >
            <i class="fas fa-search search-icon absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
        </div>

        <!-- Results dropdown -->
        <div class="search-results mt-2 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 hidden" id="searchResults">
            <div class="view-all-results px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                View all results for "<span id="searchQueryText" class="font-medium"></span>"
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

    // Mobile toggle
    $('#toggleSearch').on('click', function() {
        $('.search-container').toggleClass('active');
        $searchInput.focus();
    });

    function updateQueryText() {
        $searchQueryText.text($searchInput.val() || 'query');
    }

    function performSearch(query) {
        $.ajax({
            url: '/admin/global-search',
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
            let $sectionTitle = $('<div>').addClass('result-section-title').html(`<i class="fas fa-search"></i> ${model}`);
            $section.append($sectionTitle);

            $.each(items, function(i, item) {
                let $resultItem = $(`
                    <div class="result-item">
                        <div class="result-item-icon"><i class="fas fa-search"></i></div>
                        <div>
                            <div class="result-item-title">${item.title || item.name || item.email || 'Untitled'}</div>
                            <div class="result-item-desc">${item.description || ''}</div>
                        </div>
                    </div>
                `);

                $resultItem.on('click', function() {
                    window.location.href = `/admin/${model}/${item.id}`;
                });

                $section.append($resultItem);
            });

            $section.insertBefore($searchResults.find('.view-all-results'));
        });

        $searchResults.find('.view-all-results').off('click').on('click', function() {
            window.location.href = `/admin/search/all?q=${encodeURIComponent(query)}`;
        });
    }

    function clearSearchResults() {
        $searchResults.find('.result-section').remove();
    }
});
</script>

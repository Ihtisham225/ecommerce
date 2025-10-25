document.addEventListener('alpine:init', () => {
    Alpine.data('liveSearch', () => ({
        query: '',
        results: [],
        isLoading: false,
        
        init() {
            // Debounce the search function
            this.$watch('query', (value) => {
                if (value.length > 2) {
                    this.isLoading = true;
                    this.debouncedSearch();
                } else {
                    this.results = [];
                }
            });
        },
        
        debouncedSearch: _.debounce(function() {
            this.search();
        }, 300),
        
        search() {
            fetch(`/search/live?q=${encodeURIComponent(this.query)}`)
                .then(response => response.json())
                .then(data => {
                    this.results = data;
                    this.isLoading = false;
                })
                .catch(() => {
                    this.isLoading = false;
                });
        },
        
        close() {
            this.query = '';
            this.results = [];
        }
    }));
});
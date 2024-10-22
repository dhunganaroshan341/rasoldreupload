<header>
    <!-- Navigation bar or other header content -->
    @php
        $currentUrl = url()->current();
        $excludeUrls = [url('/'), url('/home'), url('/dashboard')];
    @endphp

    @if (!in_array($currentUrl, $excludeUrls))
        <a href="#" class="btn btn-small" id="backButton">
            <i class="fa fa-arrow-left text-blue"></i>
            {{-- Back --}}
        </a>
    @endif
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backButton = document.getElementById('backButton');

        if (backButton) {
            // Initialize navigation history if not already set
            let history = JSON.parse(sessionStorage.getItem('navigationHistory')) || [];

            // Function to get the parent URL based on the current URL
            function getParentURL(url) {
                const urlParts = url.split('/');
                if (urlParts.length > 3) {
                    urlParts.pop(); // Remove the last segment to get the parent URL
                    return urlParts.join('/');
                }
                return '{{ url('/') }}'; // Default to home if not found
            }

            // Store the current URL in sessionStorage
            const currentURL = window.location.href;
            const parentURL = getParentURL(currentURL);

            // Check if current URL is not the same as the last one in history
            if (history.length === 0 || history[history.length - 1].url !== currentURL) {
                history.push({
                    url: currentURL,
                    parent: parentURL
                });
                sessionStorage.setItem('navigationHistory', JSON.stringify(history));
            }

            backButton.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior

                // Get the navigation history from sessionStorage
                let history = JSON.parse(sessionStorage.getItem('navigationHistory')) || [];

                // Remove the current URL from history
                if (history.length > 0) {
                    history.pop(); // Remove the current page
                }

                // Get the previous URL from history
                const previous = history.pop();
                const previousURL = previous ? previous.url : '{{ url('/') }}';

                // Navigate to the previous URL or home if no previous URL
                window.location.href = previousURL;

                // Update the navigation history
                sessionStorage.setItem('navigationHistory', JSON.stringify(history));
            });

            // Update navigation history on page load
            window.addEventListener('load', function() {
                const currentURL = window.location.href;
                let history = JSON.parse(sessionStorage.getItem('navigationHistory')) || [];
                const parentURL = getParentURL(currentURL);

                // Ensure the current URL and its parent are not already in history
                if (history.length === 0 || history[history.length - 1].url !== currentURL) {
                    history.push({
                        url: currentURL,
                        parent: parentURL
                    });
                    sessionStorage.setItem('navigationHistory', JSON.stringify(history));
                }
            });
        }
    });
</script>

<script>
    (function () {
        try {
            var theme = localStorage.getItem('cyra-theme');

            if (theme === 'light') {
                document.documentElement.setAttribute('data-cyra-theme', 'light');
                document.documentElement.style.colorScheme = 'light';
            }
        } catch (error) {
            // Ignore storage failures during first paint.
        }
    })();
</script>

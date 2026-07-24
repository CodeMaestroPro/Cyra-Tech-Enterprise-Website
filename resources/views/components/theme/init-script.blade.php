<script>
    (function () {
        try {
            var theme = localStorage.getItem('cyra-theme-v2');

            if (theme !== 'dark') {
                document.documentElement.setAttribute('data-cyra-theme', 'light');
                document.documentElement.style.colorScheme = 'light';
            } else {
                document.documentElement.setAttribute('data-cyra-theme', 'dark');
                document.documentElement.style.colorScheme = 'dark';
            }
        } catch (error) {
            document.documentElement.setAttribute('data-cyra-theme', 'light');
            document.documentElement.style.colorScheme = 'light';
        }
    })();
</script>

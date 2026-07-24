<script>
    (function () {
        try {
            var theme = localStorage.getItem('cyra-theme');

            if (theme !== 'dark') {
                document.documentElement.setAttribute('data-cyra-theme', 'light');
                document.documentElement.style.colorScheme = 'light';
            }
        } catch (error) {
            document.documentElement.setAttribute('data-cyra-theme', 'light');
            document.documentElement.style.colorScheme = 'light';
        }
    })();
</script>

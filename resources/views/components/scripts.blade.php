<script>
    // Auto-show flash messages via global showToast (defined in global.js / app.js)
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            showToast("{{ addslashes(session('success')) }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ addslashes(session('error')) }}", 'error');
        @endif
        @if(session('info'))
            showToast("{{ addslashes(session('info')) }}", 'info');
        @endif
    });
</script>

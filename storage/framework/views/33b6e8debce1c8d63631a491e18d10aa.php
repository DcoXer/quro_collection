<script>
    // Auto-show flash messages via global showToast (defined in global.js / app.js)
    document.addEventListener('DOMContentLoaded', function () {
        <?php if(session('success')): ?>
            showToast("<?php echo e(addslashes(session('success'))); ?>", 'success');
        <?php endif; ?>
        <?php if(session('error')): ?>
            showToast("<?php echo e(addslashes(session('error'))); ?>", 'error');
        <?php endif; ?>
        <?php if(session('info')): ?>
            showToast("<?php echo e(addslashes(session('info'))); ?>", 'info');
        <?php endif; ?>
    });
</script>
<?php /**PATH C:\laragon\www\qurocollection\resources\views/components/scripts.blade.php ENDPATH**/ ?>
<script src="assets/site-pwa/js/lib/jquery.min.js"></script>
<script src="assets/site-pwa/js/lib/bootstrap.min.js"></script>
<script src="assets/site-pwa/js/plugins/splide/splide.min.js"></script>
<script src="assets/site-pwa/js/plugins/progressbar-js/progressbar.min.js"></script>
<script src="assets/site-pwa/js/base.js"></script>
<!-- APP -->
<script src="assets/app.core.min.js"></script>
<script src="assets/app.min.js"></script>
<script>
    (function ($, document) {
        $.each(readyQ, function (index, func) {
            $(func);
        });
        $.each(bindReadyQ, function (index, func) {
            $(document).on('ready', func);
        });
    })(jQuery, document);
</script>

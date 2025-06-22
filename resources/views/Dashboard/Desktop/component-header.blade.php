<head>
	<base href="{{ config("app.url") }}">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>{{ config("app.name") }}</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="robots" content="index, follow">
	<meta property="og:title" content="{{ config("app.name") }}">
	<meta property="og:site_name" content="">
	<meta property="og:description" content="">
	<meta property="og:type" content="website">
	<meta property="og:url" content="">
	<meta property="og:image" content="">
	<!-- THEME ICONS -->
	<link href="assets/dashboard/media/favicons/favicon.png" rel="shortcut icon">
	<link href="assets/dashboard/media/favicons/favicon-192x192.png" rel="icon" type="image/png" sizes="192x192">
	<link href="assets/dashboard/media/favicons/apple-touch-icon-180x180.png" rel="apple-touch-icon" sizes="180x180">
	<!-- THEME STYLESHEET -->
	<link rel="stylesheet" href="assets/dashboard/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="assets/dashboard/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
	<link rel="stylesheet" href="assets/dashboard/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">
	<link rel="stylesheet" href="assets/dashboard/_scss/main.min.css">
	<!-- APP -->
	<link rel="stylesheet" href="assets/app.min.css">
	<link rel="stylesheet" href="assets/app.core.min.css">
	<script>
        (function (window, document, undefined) {
            window.readyQ     = [];
            window.bindReadyQ = [];
            function pushToQueue(eventType, callback) {
                if (eventType === 'ready') {
                    window.bindReadyQ.push(callback);
                }
                else {
                    window.readyQ.push(eventType);
                }
            }
            var mockJQuery = {
                ready: pushToQueue,
                bind : pushToQueue
            };
            window.$       = window.jQuery = function (param) {
                if (param === document || param === undefined) {
                    return mockJQuery;
                }
                else {
                    pushToQueue(param);
                }
            };
        })(window, document);
	</script>
</head>

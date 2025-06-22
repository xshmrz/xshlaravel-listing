<head>
	<base href="{{ config("app.url") }}">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover"/>
	<meta name="mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="theme-color" content="#000000">
	<title>{{ config("app.name") }}</title>
	<meta name="description" content="">
	<meta name="keywords" content=""/>
	<link rel="icon" type="image/png" href="assets/site-pwa/img/favicon.png" sizes="32x32">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/site-pwa/img/icon/192x192.png">
	<link rel="stylesheet" href="assets/site-pwa/sass/style.min.css">
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

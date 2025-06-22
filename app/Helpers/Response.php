<?php
    use Illuminate\Http\JsonResponse;
    function responseContinue($response = [], $status = 100) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseSwitchingProtocols($response = [], $status = 101) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseProcessing($response = [], $status = 102) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseEarlyHints($response = [], $status = 103) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseOk($response = [], $status = 200) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseCreated($response = [], $status = 201) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseAccepted($response = [], $status = 202) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseNonAuthoritativeInformation($response = [], $status = 203) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseNoContent($response = [], $status = 204) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseResetContent($response = [], $status = 205) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responsePartialContent($response = [], $status = 206) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseMultiStatus($response = [], $status = 207) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseAlreadyReported($response = [], $status = 208) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseIAmUsed($response = [], $status = 226) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseMultipleChoices($response = [], $status = 300) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseMovedPermanently($response = [], $status = 301) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseFound($response = [], $status = 302) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseSeeOther($response = [], $status = 303) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseNotModified($response = [], $status = 304) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseUseProxy($response = [], $status = 305) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseSwitchProxy($response = [], $status = 306) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseTemporaryRedirect($response = [], $status = 307) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responsePermanentRedirect($response = [], $status = 308) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseBadRequest($response = [], $status = 400) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseUnauthorized($response = [], $status = 401) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responsePaymentRequired($response = [], $status = 402) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseForbidden($response = [], $status = 403) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseNotFound($response = [], $status = 404) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseMethodNotAllowed($response = [], $status = 405) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseNotAcceptable($response = [], $status = 406) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseProxyAuthenticationRequired($response = [], $status = 407) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseRequestTimeout($response = [], $status = 408) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseConflict($response = [], $status = 409) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseGone($response = [], $status = 410) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseLengthRequired($response = [], $status = 411) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responsePreconditionFailed($response = [], $status = 412) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responsePayloadTooLarge($response = [], $status = 413) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseUriTooLong($response = [], $status = 414) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseUnsupportedMediaType($response = [], $status = 415) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseRangeNotSatisfiable($response = [], $status = 416) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseExpectationFailed($response = [], $status = 417) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseIAmATeapot($response = [], $status = 418) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseMisdirectedRequest($response = [], $status = 421) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseUnprocessableEntity($response = [], $status = 422) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseLocked($response = [], $status = 423) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseFailedDependency($response = [], $status = 424) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseTooEarly($response = [], $status = 425) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseUpgradeRequired($response = [], $status = 426) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responsePreconditionRequired($response = [], $status = 428) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseTooManyRequests($response = [], $status = 429) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseRequestHeaderFieldsTooLarge($response = [], $status = 431) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseUnavailableForLegalReasons($response = [], $status = 451) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseInternalServerError($response = [], $status = 500) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseNotImplemented($response = [], $status = 501) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseBadGateway($response = [], $status = 502) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseServiceUnavailable($response = [], $status = 503) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseGatewayTimeout($response = [], $status = 504) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseHttpVersionNotSupported($response = [], $status = 505) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseVariantAlsoNegotiates($response = [], $status = 506) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseInsufficientStorage($response = [], $status = 507) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseLoopDetected($response = [], $status = 508) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseNotExtended($response = [], $status = 510) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    function responseNetworkAuthenticationRequired($response = [], $status = 511) {
        return response()->json($response, $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

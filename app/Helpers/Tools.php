<?php
    use Illuminate\Support\Facades\Http;
    use Carbon\Carbon;
    function binanceGetKLines($symbol = 'BTCUSDT', $interval = '1d', $days = 30) {
        $end      = Carbon::now();
        $start    = Carbon::now()->subDays($days);
        $response = Http::get('https://api.binance.com/api/v3/klines', [
            'symbol'    => $symbol,
            'interval'  => $interval,
            'startTime' => $start->getTimestampMs(),
            'endTime'   => $end->getTimestampMs(),
            'limit'     => 1000,
        ]);
        if ($response->failed()) {
            return ['error' => 'Klines API bağlantı hatası'];
        }
        return collect($response->json())->map(function ($item) {
            return [
                'date'   => Carbon::createFromTimestampMs($item[0])->toDateString(),
                'open'   => (float) $item[1],
                'high'   => (float) $item[2],
                'low'    => (float) $item[3],
                'close'  => (float) $item[4],
                'volume' => (float) $item[5],
            ];
        });
    }
    function binanceGetCurrentPrice($symbol = 'BTCUSDT') {
        $response = Http::get('https://api.binance.com/api/v3/ticker/price', [
            'symbol' => $symbol,
        ]);
        if ($response->failed()) {
            return ['error' => 'Fiyat API bağlantı hatası'];
        }
        $data = $response->json();
        return [
            'symbol' => $data['symbol'],
            'price'  => (float) $data['price'],
        ];
    }
    function binanceGetSymbols($quoteAsset = 'TRY') {
        $response = Http::get('https://api.binance.com/api/v3/exchangeInfo');
        if ($response->failed()) {
            return ['error' => 'ExchangeInfo API bağlantı hatası'];
        }
        $symbols = collect($response->json()['symbols'])
            ->where('status', 'TRADING');
        if ($quoteAsset) {
            $symbols = $symbols->where('quoteAsset', strtoupper($quoteAsset));
        }
        return $symbols->map(function ($item) {
            return [
                'symbol'      => $item['symbol'],
                'base_asset'  => $item['baseAsset'],
                'quote_asset' => $item['quoteAsset'],
            ];
        })->values(); // index sıfırlama
    }
    function sendPriceAnalysisToOpenAi(array $prices) : array {
        $apiKey        = env('OPENAI_API_KEY');
        $systemMessage = [
            [
                'role'    => 'system',
                'content' => [
                    [
                        'type' => 'input_text',
                        'text' => 'Sen bir teknik analiz asistanısın. Kullanıcının verdiği fiyat verilerine göre kısa, sade ve alım/satım kararlarına uygun öneriler ver. Gereksiz açıklamalardan kaçın. JSON formatında yanıt ver.',
                    ],
                ],
            ],
            [
                'role'    => 'user',
                'content' => [
                    [
                        'type' => 'input_text',
                        'text' => 'Kapanış fiyatları: '.json_encode($prices),
                    ],
                ],
            ],
        ];
        $payload       = [
            'model'             => 'gpt-4.1-mini',
            'input'             => $systemMessage,
            'text'              => [
                'format' => [
                    'type' => 'text',
                ],
            ],
            'reasoning'         => new \stdClass(),
            'tools'             => [
                [
                    'type'       => 'function',
                    'name'       => 'getBuySellSignal',
                    'strict'     => false,
                    'parameters' => [
                        'type'       => 'object',
                        'required'   => [],
                        'properties' => new \stdClass(),
                    ],
                ],
            ],
            'temperature'       => 1,
            'max_output_tokens' => 2048,
            'top_p'             => 1,
            'store'             => true,
        ];
        $response      = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer '.$apiKey,
        ])->post('https://api.openai.com/v1/responses', $payload);
        return $response->json();
    }

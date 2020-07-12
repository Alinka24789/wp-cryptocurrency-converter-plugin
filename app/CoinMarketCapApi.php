<?php

namespace app;


class CoinMarketCapApi
{
    public static $apiKey = CPC_CONVERTER_COIN_MARKET_CAP_API_KEY;

    public static $apiUrl = CPC_CONVERTER_COIN_MARKET_CAP_API_URL;

    function __construct()
    {

    }

    public function getCryptocurrencyListings()
    {
        return self::get('/cryptocurrency/listings/latest?limit=5000');

    }

    private static function get(string $url)
    {
        $response = wp_remote_get(self::$apiUrl . $url,
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-CMC_PRO_API_KEY' => self::$apiKey
                ]
            ]);
        $body = wp_remote_retrieve_body( $response );
        return json_decode($body);
    }
}
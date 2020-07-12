<?php

namespace app\http;

use ConverterCurrencies;
use ConverterHistory;

class CPCHttp
{
    public function getRate()
    {
        $fromCurrency = trim($_POST['fromCurrency']);
        $toCurrency = trim($_POST['toCurrency']);
        $count = floatval($_POST['count']);

        $converterCurrencies = new ConverterCurrencies();
        $rate = $converterCurrencies->getExchangeRate($fromCurrency, $toCurrency, $count);

        $converterHistory = new ConverterHistory();
        $converterHistory->saveConverterResult($rate['from'], $rate['to'], $rate['count'], $rate['rate']);

        echo json_encode([
            'status' => 'success',
            'data'   => $rate
        ]);

        wp_die();
    }
}
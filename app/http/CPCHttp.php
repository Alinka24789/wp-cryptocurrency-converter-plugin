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

        $regex = '/^[A-Za-z0-9]{2,}$/';

        if (!preg_match($regex, $fromCurrency) || !preg_match($regex, $toCurrency) || !$count) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data isn`t correct'
            ]);
        }

        try {
            $converterCurrencies = new ConverterCurrencies();
            $rate = $converterCurrencies->getExchangeRate($fromCurrency, $toCurrency, $count);

            $converterHistory = new ConverterHistory();
            $converterHistory->saveConverterResult($rate['from'], $rate['to'], $rate['count'], $rate['rate']);

            echo json_encode([
                'status' => 'success',
                'data'   => $rate
            ]);
        } catch (\Exception $error) {
            echo json_encode([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }

        wp_die();
    }
}
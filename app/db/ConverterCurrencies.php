<?php

use app\db\ConverterDBWrap;
use app\CoinMarketCapApi;

class ConverterCurrencies extends ConverterDBWrap
{
    public $tableName = 'converter_currencies';

    /**
     * List of the table`s columns
     */
    const CURRENCY_ID = 'id';
    const CURRENCY_NAME = 'currency_name';
    const CURRENCY_SLUG = 'currency_slug';
    const CURRENCY_SYMBOL = 'currency_symbol';
    const USD_PRICE = 'usd_price';
    const UPDATED_AT = 'updated_at';

    public $columns = [
        self::CURRENCY_NAME,
        self::CURRENCY_SLUG,
        self::CURRENCY_SYMBOL,
        self::USD_PRICE,
        self::UPDATED_AT,
    ];

    public function createTable()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS {$this->getWPFullTableName()} (
                " . self::CURRENCY_ID . " mediumint(9) NOT NULL AUTO_INCREMENT,
                " . self::CURRENCY_NAME . " varchar(50) NOT NULL,
                " . self::CURRENCY_SLUG . " varchar(50) NOT NULL,
                " . self::CURRENCY_SYMBOL . " varchar(10) NOT NULL,
                " . self::USD_PRICE . " decimal(30,15) NOT NULL,
                " . self::UPDATED_AT . " TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY  (" . self::CURRENCY_ID . ")
                ) {$this->db->get_charset_collate()}
                ");
    }

    public function updateFromApi()
    {
        $coinApi = new CoinMarketCapApi();
        $results = $coinApi->getCryptocurrencyListings();

        if (!count($results->data)) {
            error_log('No list currencies were received from CoinMarketCap API');
            return false;
        }
        $dataToInsert = [];
        foreach ($results->data as $currency) {
            $row = [
                self::CURRENCY_NAME   => $currency->name,
                self::CURRENCY_SLUG   => $currency->slug,
                self::CURRENCY_SYMBOL => $currency->symbol,
                self::USD_PRICE       => $currency->quote->USD->price,
                self::UPDATED_AT      => gmdate("Y-m-d H:i:s")
            ];
            array_push($dataToInsert, $row);
        }
        $dataToInsertChunked = array_chunk($dataToInsert, 1000);

        $values = $placeHolders = [];
        foreach ($dataToInsertChunked as $currencies) {
            foreach ($currencies as $currency) {
                array_push($values, $currency[self::CURRENCY_NAME], $currency[self::CURRENCY_SLUG],
                    $currency[self::CURRENCY_SYMBOL],
                    $currency[self::USD_PRICE], $currency[self::UPDATED_AT]);
                $placeHolders[] = "( %s, %s, %s, %s, %s)";
            }
        }

        $this->truncateTable();
        $this->doMultiInsert($placeHolders, implode(', ', $this->columns), $values);
        return true;
    }

    public function getCurrenciesList()
    {
        return $this->db->get_results("SELECT " . self::CURRENCY_ID . ", " . self::CURRENCY_NAME . ", " . self::CURRENCY_SYMBOL . " FROM {$this->getWPFullTableName()}");
    }

    public function getExchangeRate(string $fromCurrency = 'BTC', string $toCurrency = 'ETH', float $count = 1)
    {
        $fromCurrencyRow = $this->db->get_row("SELECT * FROM {$this->getWPFullTableName()} WHERE " . self::CURRENCY_SYMBOL . "='{$fromCurrency}'");
        $toCurrencyRow = $this->db->get_row("SELECT * FROM {$this->getWPFullTableName()} WHERE " . self::CURRENCY_SYMBOL . "='{$toCurrency}'");

        $exchangeRate = $fromCurrencyRow->usd_price / $toCurrencyRow->usd_price * $count;

        return [
            'from' => $fromCurrency,
            'to' => $toCurrency,
            'count' => $count,
            'rate' => $exchangeRate
        ];
    }
}
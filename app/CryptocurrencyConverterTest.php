<?php

namespace app;

use app\ConverterView;
use app\CoinMarketCapApi;
use ConverterHistory;
use ConverterCurrencies;

class CryptocurrencyConverterTest
{
    private $db;

    private $fullPath;

    private $converterHistoryTable;

    private $converterCurrenciesTable;

    function __construct(string $fullPath)
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->fullPath = $fullPath;
        $this->converterHistoryTable = new ConverterHistory();
        $this->converterCurrenciesTable = new ConverterCurrencies();
    }

    public function run()
    {
        register_activation_hook($this->fullPath, [$this, 'activate']);
        register_deactivation_hook($this->fullPath, [$this, 'deactivate']);

        add_filter( 'cron_schedules', [$this, 'cronAddFiveMinutesInterval'] );

        add_action('wp', [$this, 'activateScheduledUpdatingCurrencies']);

        add_action ('cpc_converter_cron', [$this, 'doScheduledEvent']);

        add_filter('get_header', [$this, 'addConverter']);
    }

    public function activate()
    {
        $this->converterHistoryTable->createTable();

        $this->converterCurrenciesTable->createTable();
    }

    public function deactivate()
    {
        $this->converterHistoryTable->deleteTable();

        $this->converterCurrenciesTable->deleteTable();

        $timestamp = wp_next_scheduled ('cpc_converter_cron');
        wp_unschedule_event ($timestamp, 'cpc_converter_cron');
    }

    public function cronAddFiveMinutesInterval()
    {
        $schedules['everyfiveminutes'] = array(
            'interval' => 60,
            'display' => __( 'Once Every 5 Minutes' )
        );
        return $schedules;
    }

    public function activateScheduledUpdatingCurrencies()
    {
        if( !wp_next_scheduled( 'cpc_converter_cron' ) ) {
            wp_schedule_event( time(), 'everyfiveminutes', 'cpc_converter_cron' );
        }
    }

    public function doScheduledEvent()
    {
        $this->converterHistoryTable->deleteTable();
    }

    public function addConverter()
    {
        if ($_SERVER['REQUEST_URI'] !== '/') {
            return;
        }

        ConverterView::view('index');
    }
}
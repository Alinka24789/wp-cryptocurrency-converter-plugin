<?php

namespace app;

use app\http\CPCHttp;
use ConverterHistory;
use ConverterCurrencies;

class CryptocurrencyConverterTest
{
    private $fullPath;

    private $converterHistoryTable;

    private $converterCurrenciesTable;

    private $http;

    function __construct(string $fullPath)
    {
        $this->fullPath = $fullPath;
        $this->converterHistoryTable = new ConverterHistory();
        $this->converterCurrenciesTable = new ConverterCurrencies();
        $this->http = new CPCHttp();
    }

    public function run()
    {
        register_activation_hook($this->fullPath, [$this, 'activate']);
        register_deactivation_hook($this->fullPath, [$this, 'deactivate']);

        add_filter( 'cron_schedules', [$this, 'cronAddFiveMinutesInterval'] );

        add_action ('cpc_converter_cron', [$this, 'doScheduledEvent']);

        add_action('wp_enqueue_scripts', [$this, 'registerScripts']);

        add_filter('get_header', [$this, 'addConverter']);

        add_action( 'wp_ajax_getRate', [$this->http, 'getRate'] );
        add_action( 'wp_ajax_nopriv_getRate', [$this->http, 'getRate'] );
    }

    public function activate()
    {
        $this->converterHistoryTable->createTable();

        $this->converterCurrenciesTable->createTable();

        $this->converterCurrenciesTable->updateFromApi();

        $this->activateScheduledUpdatingCurrencies();
    }

    public function registerScripts()
    {
        wp_register_style('cpc_converter_styles', CPC_PLUGIN_URL . 'public/css/style.css');
        wp_register_style('cpc_converter_input_styles', CPC_PLUGIN_URL . 'public/css/converter-input.css');
        wp_enqueue_style('cpc_converter_styles');
        wp_enqueue_style('cpc_converter_input_styles', ['cpc_converter_styles']);
        wp_enqueue_script( 'cpc_converter_js', CPC_PLUGIN_URL . 'public/js/index.js', array( 'jquery' ), '1.0.0' );

        $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
        wp_localize_script( 'cpc_converter_js', 'cpc_object',
            array( 'cpc_ajax_url' => admin_url( 'admin-ajax.php', $protocol )));
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
            'interval' => 300,
            'display' => esc_html__( 'Once Every 5 Minutes' )
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
        $this->converterCurrenciesTable->updateFromApi();
    }

    public function addConverter()
    {
        if ($_SERVER['REQUEST_URI'] !== '/') {
            return;
        }
        $currencies = $this->converterCurrenciesTable->getCurrenciesList();
        $exchangeRate = $this->converterCurrenciesTable->getExchangeRate();
        $history = $this->converterHistoryTable->getRecentlyConverted();
        ConverterView::view('index', [
            'currencies' => $currencies,
            'exchangeRate' => $exchangeRate,
            'history' => $history
        ]);
    }
}
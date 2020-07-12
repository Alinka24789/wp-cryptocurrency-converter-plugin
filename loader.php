<?php

define( 'CPC_PLUGIN_FILE', __FILE__ );
define( 'CONVERTER__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define('CPC_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once(CPC_PLUGIN_DIR . 'config.php');
require_once(CPC_PLUGIN_DIR . 'app/CryptocurrencyConverterTest.php');
require_once(CPC_PLUGIN_DIR . 'app/ConverterView.php');
require_once(CPC_PLUGIN_DIR . 'app/CoinMarketCapApi.php');
require_once(CPC_PLUGIN_DIR . 'app/db/ConverterDBWrap.php');
require_once(CPC_PLUGIN_DIR . 'app/db/ConverterHistory.php');
require_once(CPC_PLUGIN_DIR . 'app/db/ConverterCurrencies.php');
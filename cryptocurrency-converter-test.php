<?php
/*
Plugin Name: Cryptocurrency converter test
Description: Cryptocurrency converter test
Version: 1.0
Author: Alina Syrhiienko
*/
define('CPC_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once( CPC_PLUGIN_DIR . 'loader.php' );

use app\CryptocurrencyConverterTest;

$wp_plugin_template = new CryptocurrencyConverterTest(__FILE__);
$wp_plugin_template->run();
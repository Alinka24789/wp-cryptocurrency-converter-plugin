<?php

namespace app;

// use cpconverter\ConverterView;

class CryptocurrencyConverterTest
{
    private $db;

    private $tableName;

    private $fullPath;

    function __construct($fullPath)
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->tableName = "{$this->db->prefix}converter_history";
        $this->fullPath = $fullPath;
    }

    public function run()
    {
        register_activation_hook($this->fullPath, [$this, 'activate']);
        register_deactivation_hook($this->fullPath, [$this, 'deactivate']);

        add_filter('get_header', [$this, 'addConverter']);
    }

    public function activate()
    {
        $sql = "CREATE TABLE {$this->tableName} (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                converted_from varchar(10) NOT NULL,
                converted_to varchar(10) NOT NULL,
                rate decimal(15,15) NOT NULL,
                request_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY  (id)
                ) {$this->db->get_charset_collate()};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    }

    public function deactivate()
    {
        $this->db->query("DROP TABLE IF EXISTS  {$this->tableName};");
    }

    public function addConverter()
    {
        if ($_SERVER['REQUEST_URI'] !== '/') {
            return;
        }

        ConverterView::view('index');
    }
}
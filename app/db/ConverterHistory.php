<?php

use app\db\ConverterDBWrap;

class ConverterHistory extends ConverterDBWrap
{
    public $tableName = 'converter_history';

    public function createTable()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS {$this->getWPFullTableName()} (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                converted_from varchar(10) NOT NULL,
                converted_to varchar(10) NOT NULL,
                rate decimal(15,15) NOT NULL,
                request_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY  (id)
                ) {$this->db->get_charset_collate()};");
    }

    public function deleteTable()
    {
        $this->db->query("DROP TABLE IF EXISTS  {$this->getWPFullTableName()};");
    }

}
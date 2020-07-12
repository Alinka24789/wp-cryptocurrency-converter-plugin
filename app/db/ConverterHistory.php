<?php

use app\db\ConverterDBWrap;

class ConverterHistory extends ConverterDBWrap
{
    public $tableName = 'converter_history';

    /**
     * List of the table`s columns
     */
    const HISTORY_ID = 'id';
    const CONVERTED_FROM = 'converted_from';
    const HISTORY_REQUESTED_COUNT = 'requested_count';
    const CONVERTED_TO = 'converted_to';
    const RATE = 'rate';
    const REQUEST_TIME = 'request_time';

    public $columns = [
        self::CONVERTED_FROM,
        self::HISTORY_REQUESTED_COUNT,
        self::CONVERTED_TO,
        self::RATE,
        self::REQUEST_TIME,
    ];

    public function createTable()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS {$this->getWPFullTableName()} (
                " . self::HISTORY_ID . " mediumint(9) NOT NULL AUTO_INCREMENT,
                " . self::CONVERTED_FROM . " varchar(10) NOT NULL,
                " . self::HISTORY_REQUESTED_COUNT . " decimal(30,15) NOT NULL,
                " . self::CONVERTED_TO . " varchar(10) NOT NULL,
                " . self::RATE . " decimal(30,15) NOT NULL,
                " . self::REQUEST_TIME . " TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY  (id)
                ) {$this->db->get_charset_collate()};");
    }

    public function saveConverterResult(string $from, string $to, float $count, float $rate)
    {
        $this->db->insert($this->getWPFullTableName(), [
            self::CONVERTED_FROM          => $from,
            self::HISTORY_REQUESTED_COUNT => $count,
            self::CONVERTED_TO            => $to,
            self::RATE                    => $rate,
            self::REQUEST_TIME            => gmdate("Y-m-d H:i:s")
        ]);
    }

    public function getRecentlyConverted(int $count = 10)
    {
        $history = $this->db->get_results("SELECT * FROM {$this->getWPFullTableName()} ORDER BY " . self::REQUEST_TIME . " DESC LIMIT " . $count);

        return $history;
    }

}
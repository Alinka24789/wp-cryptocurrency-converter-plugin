<?php

namespace app\db;

abstract class ConverterDBWrap
{
    /** @var object $db WordPress database abstraction object. */
    public $db;

    /** @var string $fullPath */
    public $fullPath;

    /** @var string $tableName */
    public $tableName;

    /** @var array $columns table`s columns */
    public $columns;

    function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->fullPath = CPC_PLUGIN_FILE;
    }


    abstract function createTable();

    public function getWPFullTableName()
    {
        return $this->db->prefix . $this->tableName;
    }

    public function doInsert(array $placeHolders, string $fieldsNames,array $values)
    {

        $query = "INSERT INTO {$this->getWPFullTableName()} ({$fieldsNames}) VALUES ";
        $query .= implode(', ', $placeHolders);
        $sql = $this->db->prepare("$query ", $values);

        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteTable()
    {
        $this->db->query("DROP TABLE IF EXISTS  {$this->getWPFullTableName()};");
    }

    public function truncateTable()
    {
        $this->db->query("TRUNCATE TABLE {$this->getWPFullTableName()};");
    }
}
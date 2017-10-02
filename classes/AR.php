<?php

abstract class AR
{
    abstract static function table():string;

    public function PK()
    {
        return ['id' => $this->id];
    }

    /**
     * @param string $index
     * @param string $value
     * @return string[]|static[]
     */
    public static function fetchAll($index = 'id', $value = null)
    {
        $props = pg_query(APP::DB(), 'select * from ' . static::table());
        $result = [];
        while ($prop = pg_fetch_object($props, null, static::class)) {
            $result[$prop->$index] = $value ? $prop->$value : $prop;
        }
        return $result;
    }

    public static function fetchByIds(array $ids, string $pk = 'id')
    {
        $props = pg_query(APP::DB(), 'select * from ' . static::table() . " where $pk in (" . implode(',', $ids) . ')');
        $result = [];
        while ($prop = pg_fetch_object($props, null, static::class)) {
            $result[$prop->$pk] = $prop;
        }
        return $result;
    }

    public function save()
    {
        pg_update(APP::DB(), static::table(), get_object_vars($this), $this->PK());
    }
}
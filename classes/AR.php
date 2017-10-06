<?php

abstract class AR
{
    abstract static function table():string;

    public function PK()
    {
        return ['id' => $this->id];
    }

    /**
     * @param string $col
     * @param QueryCriteria $criteria
     * @return array
     */
    public static function queryColumn(string $col, QueryCriteria $criteria = null)
    {
        return static::query($criteria, function ($object) use ($col) {
            return $object->$col;
        });
    }

    /**
     * @param QueryCriteria|null $criteria
     * @param callable|null      $callback
     * @return static[]
     */
    public static function query(QueryCriteria $criteria = null, callable $callback = null)
    {
        if (!$criteria) {
            $criteria = new QueryCriteria;
        }
        $objects = pg_query_params(APP::DB(), 'select ' . $criteria->getSelect() . ' from ' . static::table() . (($where = $criteria->getWhere()) ? " where $where" : ''), $criteria->getParams());
        $result = [];
        while ($object = pg_fetch_object($objects, null, static::class)) {
            $value = $object;
            if ($callback) {
                $value = $callback($object);
            }
            if ($index = $criteria->getIndex()) {
                $result[$object->$index] = $value;
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }

    public function save()
    {
        pg_update(APP::DB(), static::table(), array_filter(get_object_vars($this), function($prop) { return $prop[0] != '_'; }, ARRAY_FILTER_USE_KEY), $this->PK());
    }
}
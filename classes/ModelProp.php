<?php

class ModelProp
{
    public $model_id;
    public $prop_id;
    public $value;

    public static function fetchByModel(Model $model)
    {
        $items = pg_query(APP::DB(), 'select * from model_props where model_id = ' . $model->id);
        $result = [];
        while ($item = pg_fetch_object($items, null, static::class)) {
            $result[$item->prop_id] = $item;
        }
        return $result;
    }

    public function getProp()
    {
        return Prop::getCached($this->prop_id);
    }

}
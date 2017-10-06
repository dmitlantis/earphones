<?php

class Model extends AR
{
    public $id;
    public $name;
    public $descr;
    public $url;
    public $origin;
    public $diameter;
    public $dinamics;
    public $connector;

    protected $_props;

    public static function table():string {
        return 'models';
    }

    public function getProps() {
        return ModelProp::fetchByModel($this);
    }

    public function getPropById(int $id) {
        if (!isset($this->_props)) {
            $this->_props = $this->getProps();
        }
        return $this->_props[$id] ?? null;
    }

    public function getUrl():string
    {
        return Origin::generateUrlOfCode($this->origin, $this->url);
    }

}
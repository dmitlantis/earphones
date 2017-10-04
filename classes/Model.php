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

    public static function table():string {
        return 'models';
    }

    public function getProps() {
        return ModelProp::fetchByModel($this);
    }

    public function getPropById(int $id) {
        static $props;
        if (!isset($props)) {
            $props = $this->getProps();
        }
        return $props[$id] ?? null;
    }

    public function getUrl():string
    {
        static $origin;
        if (!$origin) {
            $origin = new Origin($this->origin);
        }
        return $origin->generateUrl($this->url);
    }

}
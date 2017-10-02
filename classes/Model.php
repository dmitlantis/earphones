<?php

class Model extends AR
{
    public $id;
    public $name;
    public $descr;
    public $url_mvideo;
    private $props;

    public static function table():string {
        return 'models';
    }

    public function getProps() {
        return ModelProp::fetchByModel($this);
    }

    public function getPropById(int $id) {
        if (!$this->props) {
            $this->props = $this->getProps();
        }
        return $this->props[$id] ?? null;
    }

}
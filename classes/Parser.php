<?php

class Parser
{
    public    $origin;
    protected $map = [];
    protected $namePattern;
    protected $descrPattern;
    protected $charset;
    protected $url = '';
    protected $delayMin = 1;
    protected $delayMax = 4;

    public function __construct(Origin $origin)
    {
        $this->origin = $origin;
    }

    public function addMap(string $attribute, string $property, callable $convert = null)
    {
        $this->map[$attribute] = [$property, $convert];
        return $this;
    }

    public function setCharset(string $charset)
    {
        $this->charset = $charset;
    }

    public function parse(string $parsePattern)
    {
        $models = pg_query_params(APP::DB(), 'select * from models WHERE origin = $1', [$this->origin->code]);
        $fieldsIndexed = Prop::queryColumn('id', QueryCriteria::create()->index('name')->param('origin', $this->origin->code));
        $fieldsIndex = $fieldsIndexed ? max($fieldsIndexed) : 0;

        $counter = 0;
        /** @var Model $model */
        while ($model = pg_fetch_object($models, null, Model::class)) {
            echo ($counter++) . ' Requesting.. ' . $model->url . PHP_EOL;
            $changed = false;
            $curl = curl_init($this->origin->generateUrl($model->url) . $this->url);
            $header = [
                'Accept'           => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Encoding'  => 'gzip, deflate',
                'Accept-Language'  => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
                'Connection'       => 'keep-alive',
                'Host'             =>  $this->origin->getHost(),
                'Referer'          =>  $this->origin->getHttp(),
                'User-Agent'       => 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
                'X-Requested-With' => 'XMLHttpRequest',
            ];
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            $html = $this->convert(curl_exec($curl));
            if ($this->descrPattern) {
                preg_match($this->descrPattern, $html, $descr);
                if (!empty($descr)) {
                    $descr = $descr[1];
                    if ($descr != $model->descr) {
                        $model->descr = $descr;
                        $changed      = true;
                    }
                }
            }
            if ($this->namePattern) {
                preg_match($this->namePattern, $html, $name);
                if (!empty($name)) {
                    $name = trim($name[1]);
                    if ($name != $model->name) {
                        $model->name = $name;
                        $changed     = true;
                    }
                }
            }
            preg_match_all($parsePattern, $html, $props, PREG_SET_ORDER);
            foreach ($props as $prop) {
                $propName = trim($prop[1]);
                $propValue = trim($prop[2]);
                if (!isset($fieldsIndexed[$propName])) {
                    pg_insert(App::DB(), 'props', ['name' => $propName, 'origin' => $this->origin->code]);
                    $fieldsIndexed[$propName] = ++$fieldsIndex;
                }
                pg_query_params(App::DB(), 'insert into model_props (model_id, prop_id, value) values ($1,$2,$3) ON CONFLICT (model_id, prop_id) DO UPDATE SET value = $3', [$model->id, $fieldsIndexed[$propName],  $propValue]);
                foreach ($this->map as $mapField => $mapAttribute) {
                    list($mapAttribute, $convert) = $mapAttribute;
                    if ($propName == $mapField && $propValue != $model->$mapAttribute) {
                        $model->$mapAttribute = !empty($convert) ? $convert($propValue) : $propValue;
                        $changed = true;
                        break;
                    }
                }
            }
            if ($changed) {
                $model->save();
            }
            curl_close($curl);
            sleep(rand($this->delayMin,$this->delayMax));
        }
    }

    public function setNamePattern($namePattern)
    {
        $this->namePattern = $namePattern;
        return $this;
    }

    public function setDelay($min, $max)
    {
        $this->delayMax = $max;
        $this->delayMin = $min;
    }

    public function setDescrPattern($descrPattern)
    {
        $this->descrPattern = $descrPattern;
        return $this;
    }

    public function convert(string $string)
    {
        return $this->charset ? mb_convert_encoding($string, 'utf-8', $this->charset) : $string;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

}
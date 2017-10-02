<?php

require 'init.php';

$models = pg_query(APP::DB(), 'select * from models');
$fieldsIndexed = Prop::fetchAll('name', 'id');
$fieldsIndex = $fieldsIndexed ? max($fieldsIndexed) : 0;

$counter = 0;
/** @var Model $model */
while ($model = pg_fetch_object($models, null, Model::class)) {
    echo ($counter++) . ' Requesting.. ' . $model->url_mvideo . PHP_EOL;
    $curl = curl_init("http://www.mvideo.ru/$model->url_mvideo/specification?ssb_block=descriptionTabContentBlock");
    $header = [
        'Accept'           => 'text/html, */*; q=0.01',
        'Accept-Encoding'  => 'gzip, deflate',
        'Accept-Language'  => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
        'Connection'       => 'keep-alive',
        'Host'             => 'www.mvideo.ru',
        'Referer'          => 'http://www.mvideo.ru/',
        'User-Agent'       => 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
        'X-Requested-With' => 'XMLHttpRequest',
    ];
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    $html = curl_exec($curl);
    $span1 = '<span class="product-details-overview-specification">';
    $span2 = '<\/span>';
    preg_match_all("/$span1(.*?)$span2\\s*<\/td>\\s*<td>\\s*$span1(.*?)$span2/", $html, $props, PREG_SET_ORDER);
    foreach ($props as $prop) {
        if (!isset($fieldsIndexed[$prop[1]])) {
            pg_insert(App::DB(), 'props', ['name' => $prop[1]]);
            $fieldsIndexed[$prop[1]] = ++$fieldsIndex;
        }
        pg_query_params(App::DB(), 'insert into model_props (model_id, prop_id, value) values ($1,$2,$3) ON CONFLICT (model_id, prop_id) DO UPDATE SET value = $3', [$model->id, $fieldsIndexed[$prop[1]],  $prop[2]]);
        if ($fieldsIndexed[$prop[1]] == Prop::MODEL_ID && $prop[2] != $model->name) {
            $model->name = $prop[2];
            $model->save();
        }
    }
    curl_close($curl);
    sleep(rand(1,4));
}



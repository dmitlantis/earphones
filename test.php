<?php
function request($url)
{
    $curl = curl_init("https://www.ozon.ru$url");
    $header = [
        'Accept'           => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding'  => 'gzip, deflate, br',
        'Accept-Language'  => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
        'Connection'       => 'keep-alive',
        'Host'             => 'https://www.ozon.ru/',
        'cache-control'    => 'max-age=0',
        'Referer'          => 'https://www.ozon.ru/?context=search&text=%eb%e0%ec%ef%ee%f7%ea%e8',
        'User-Agent'       => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36',
        'X-Requested-With' => 'XMLHttpRequest',
    ];
    curl_setopt($curl, CURLOPT_COOKIE, '');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    $html = curl_exec($curl);
    curl_close($curl);
    return $html;
}
$urls = [];

foreach ($urls as $url) {
    $html = request($url);
    $html = iconv('cp1251', 'utf8', $html);
    $csv = [$url, 0, 0];
    if (preg_match('{"Value":"(\d+)","Name":"Световой поток, Лм"}', $html, $match)) {
        $csv[1] = $match[1];
    }
    if (preg_match('{"Value":"(\d+)","Name":"Эквивалентная мощность лампы накаливания, Вт"}', $html, $match)) {
        $csv[2] = $match[1];
    }

    print implode(',', $csv) . PHP_EOL;

    sleep(rand(5, 25) / 10);
}

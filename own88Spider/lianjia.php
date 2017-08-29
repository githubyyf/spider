<?php
require './vendor/owner888/phpspider/autoloader.php';
use phpspider\core\phpspider;
use phpspider\core\requests;

//$html=requests::get('http://sh.lianjia.com/ershoufang');
//var_dump($html);
//die();
/* Do NOT delete this comment */
/* 不要删除这段注释 */

$configs = array(
    'name'                => '链家',
    //'tasknum' => 8,
    'log_show'            => true,
    'save_running_state'  => false,
    'domains'             => [
        'sh.lianjia.com'
    ],
    'scan_urls'           => [
        'http://sh.lianjia.com/ershoufang',
    ],
    'list_url_regexes'    => [
        "http://www.mafengwo.cn/\\w+/d\\d+",   // 房源列表页
    ],
    'content_url_regexes' => [
        "http://sh.lianjia.com/ershoufang/sh\\d+.html",
    ],
    'proxies'             => array(
        '112.226.233.252:8118'
    ),
    'export'              => [
        'type' => 'sql',
        'file' => './data/lianjia.sql',
    ],
    'fields'              => [
        // 小区
        array(
            'name'     => "name",
            'selector' => "/html/body/section/div[2]/aside/ul[2]/li[4]/span[2]/span/a[1]",
            //'selector' => "//div[@id='Article']//h1",
            'required' => true,
        ),
        // 分类
        array(
            'name'     => "mianji",
            'selector' => "//*[@id=\"js-baseinfo-header\"]/div[1]/div[1]/div[2]/ul/li[3]/span[2]",
            'required' => true,
        ),
        // 出发时间
        array(
            'name'     => "type",
            'selector' => "//*[@id=\"js-baseinfo-header\"]/div[1]/div[2]/div[3]/ul/li[2]/span[2]",
            'required' => true,
        ),
    ],
);

$spider = new phpspider($configs);

$spider->on_start = function ($phpspider) {
    requests::set_header("Referer", "http://sh.lianjia.com/");
    requests::set_header("Upgrade-Insecure-Requests", 1);
    requests::set_header('User-Agent',
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36");
};

$spider->on_scan_page = function ($page, $content, $phpspider) {
    $area = [
        ['name' => 'jiading'],
        ['name' => 'minhang'],
        ['name' => 'baoshan'],
        ['name' => 'xuhui'],
        ['name' => 'putuo'],
        ['name' => 'yangpu'],
        ['name' => 'changning'],
        ['name' => 'songjiang'],
        ['name' => 'jiading'],
    ];
//限制获取的数据的区
    foreach ($area as $value) {
        $url = $page['url'] . '/' . $value['name'];
        $phpspider->add_url($url);
    }
};
//$spider->on_list_page = function ($page, $content, $phpspider) {
////限制获取的数据的区
//    for ($i = 0; $i <= 100; $i++) {
//        $url2 = $page['url'] . '/d' . $i;
//        $phpspider->add_url($url2);
//    }
//};


$spider->start();
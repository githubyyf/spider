<?php
require './vendor/owner888/phpspider/autoloader.php';
use phpspider\core\phpspider;
use phpspider\core\requests;


$info = requests::get('http://sh.lianjia.com/ershoufang');
var_dump($info);die();


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
//        "http://sh.lianjia.com/ershoufang/\w+",         // 浦东区
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
    requests::set_header("Referer", "http://sh.lianjia.com/");
    requests::set_header('User-Agent',"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36");
};

$spider->on_status_code = function($status_code, $url, $content, $phpspider)
{
    // 如果状态码为429，说明对方网站设置了不让同一个客户端同时请求太多次
    if ($status_code == '429')
    {
        // 将url插入待爬的队列中,等待再次爬取
        $phpspider->add_url($url);
        // 当前页先不处理了
        return false;
    }
    // 不拦截的状态码这里记得要返回，否则后面内容就都空了
    return $content;
};
$spider->on_scan_page = function ($page, $content, $phpspider) {
    $area = [
        ['name' => 'jiading'],
        ['name' => 'minhang'],
//        ['name'=>'baoshan'],
//        ['name'=>'xuhui'],
//        ['name'=>'putuo'],
//        ['name'=>'yangpu'],
//        ['name'=>'changning'],
//        ['name'=>'songjiang'],
//        ['name'=>'jiading'],
    ];
//限制获取的数据的区
    foreach ($area as $value) {
        $url = "http://sh.lianjia.com/ershoufang/{$value['name']}";
        $phpspider->add_url($url);
    }
};
$spider->on_list_page = function ($page, $content, $phpspider) {
    $area = [
        ['name' => 'jiading'],
        ['name' => 'minhang'],
//        ['name'=>'baoshan'],
//        ['name'=>'xuhui'],
//        ['name'=>'putuo'],
//        ['name'=>'yangpu'],
//        ['name'=>'changning'],
//        ['name'=>'songjiang'],
//        ['name'=>'jiading'],
    ];
//限制获取的数据的区
    foreach ($area as $value) {
        for ($i = 0; $i <= 100; $i++) {
            $url2 = "http://sh.lianjia.com/ershoufang/{$value['name']}/d{$i}";
            $phpspider->add_url($url2);
        }
    }
};


$spider->start();
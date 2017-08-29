<?php

require './vendor/autoload.php';
use phpspider\core\phpspider;

/* Do NOT delete this comment */
/* 不要删除这段注释 */
$configs = array(
    'name'                => 'baike',
    'domains'             => array(
        'qiushibaike.com',
        'www.qiushibaike.com'
    ),
    'scan_urls'           => array(
        'http://www.qiushibaike.com/'
    ),
    'content_url_regexes' => array(
        "http://www.qiushibaike.com/article/\\d+"
    ),
    'list_url_regexes'    => array(
        "http://www.qiushibaike.com/8hr/page/\\d+\\?s=\\d+"
    ),
    'proxies'             => array(
        '112.226.233.252:8118'
    ),

    'fields' => array(
        array(
// 抽取内容页的文章内容
            'name'     => "article_content",
            'selector' => "//*[@id='single-next-link']",
            'required' => true
        ),
        array(
// 抽取内容页的文章作者
            'name'     => "article_author",
            'selector' => "//div[contains(@class,'author')]//h2",
            'required' => true
        ),
    ),
);
$spider = new phpspider($configs);

$spider->on_status_code = function ($status_code, $url, $content, $phpspider) {

    // 如果状态码为429，说明对方网站设置了不让同一个客户端同时请求太多次
    if ($status_code == '429') {
        // 将url插入待爬的队列中,等待再次爬取
        $phpspider->add_url($url);
        // 当前页先不处理了
        return false;
    }

    // 不拦截的状态码这里记得要返回，否则后面内容就都空了
    return $content;
};
$spider->start();
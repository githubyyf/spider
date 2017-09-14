<?php
/**
 * Created by PhpStorm.
 * User: haowang
 * Date: 2016/12/20
 * Time: 下午4:42
 */

namespace app\commands;


use app\models\City;
use app\models\District;
use app\models\Region;
use GuzzleHttp\Client;
use yii\console\Controller;

/**
 * 获取城市数据入库
 * Class SiteController
 * todo ./yii zhi-lian-json
 * @package app\commands
 */
class ZhiLianJsonController extends Controller
{
    //http://sou.zhaopin.com/jobs/searchresult.ashx?jl=%E4%B8%8A%E6%B5%B7&kw=PHP&isadv=0&sg=fbcdbe0ebe4e4c4cb0966a267088841f&p=2  //上海PHP
    //http://sou.zhaopin.com/jobs/searchresult.ashx?bj=160000&jl=%E4%B8%8A%E6%B5%B7&sm=0&isfilter=0&fl=538&isadv=0&sg=70a9d6244950409ca1f888cd56b799b7&p=2
    const URL = 'http://sou.zhaopin.com/jobs/searchresult.ashx?jl=%E4%B8%8A%E6%B5%B7&kw=PHP&isadv=0&sg=fbcdbe0ebe4e4c4cb0966a267088841f&p=';
    const MAX_PAGE = 90;

    public function actionIndex()
    {
        $i = 1;
        do {
            $data = null;
            $data = $this->fetchInfo($i);
            $i++;
        } while ($data);

    }

    public function fetchInfo($page)
    {
        $url = static::URL . $page;
        $client = new Client();
        $response = $client->request('get', $url);
        $code = $response->getStatusCode();
        if ($code != 200) {
            return false;
        }
        $html = $response->getBody();
//        $infoes = html5qp((string)$html,
//            '#newlist_list_content_table')->children();
        $infoes = html5qp((string)$html,
            '#newlist_list_content_table > table:nth-child(1) > tbody > tr > th.zwmc > span')->text();

        var_dump($infoes);die();
        foreach ($infoes as $infoe){
            $infoe->text();
        }

        var_dump($infoes);die();
        $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'zhilianPhp' . DIRECTORY_SEPARATOR . $page . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true); // 如果不存在则创建
        }
        file_put_contents($dir . $page . '.json', $response->getBody());

        $data = $body->data;
        return $data->info->list;

    }
}
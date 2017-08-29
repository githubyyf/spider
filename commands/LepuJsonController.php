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
 * todo ./yii lepu-json
 * @package app\commands
 */
class LepuJsonController extends Controller
{
    const URL = 'http://api.lepu.cn/app/shop/search?page=';

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
        $body = \GuzzleHttp\json_decode($response->getBody());
        $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'lepu' . DIRECTORY_SEPARATOR . $page . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true); // 如果不存在则创建
        }
        file_put_contents($dir . $page . '.json', $response->getBody());

        $data = $body->data;
        return $data->info->list;

    }
}
<?php
namespace app\commands;

use app\models\LianJianCity;
use GuzzleHttp\Client;
use yii\console\Controller;

/**
 * 获取链家城市和对应链接数据入库
 * Class SiteController
 * todo ./yii lianjia-city-json
 * @package app\commands
 */
class LianjiaCityJsonController extends Controller
{
    const URL = 'https://bj.lianjia.com/';

    public function actionIndex()
    {
        $url = static::URL;
        $client = new Client();
        $response = $client->request('get', $url);
        $code = $response->getStatusCode();
        if ($code != 200) {
            return false;
        }
        $html = $response->getBody();
        $infoes = html5qp((string)$html,
            'body > div.header > div > div.city-change.animated > div.fc-main.clear > div.fl.citys-l > ul')->children();
        foreach ($infoes as $info) {
            $regions = $info->find('a');
            foreach ($regions as $region) {
                $model = new LianJianCity();
                $model->name = $region->text();
                $model->url = $region->attr('href');
                var_dump($model->save());
                var_dump($region->attr('href'));
            }
        }
//body > div.header > div > div.city-change.animated.bounceIn > div.fc-main.clear > div.fl.citys-r > ul
        $infoes = html5qp((string)$html,
            'body > div.header > div > div.city-change.animated > div.fc-main.clear > div.fl.citys-r > ul')->children();
        foreach ($infoes as $info) {
            $regions = $info->find('a');
            foreach ($regions as $region) {
                $model = new LianJianCity();
                $model->name = $region->text();
                $model->url = $region->attr('href');
                var_dump($model->save());
                var_dump($region->attr('href'));
            }
        }
        echo 'success';
        die();

    }
}
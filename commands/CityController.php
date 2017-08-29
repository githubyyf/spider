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
 * @package app\commands
 */
class CityController extends Controller
{
    const URL = 'http://www.dianping.com';


    //101-200
    //1-101
    public function actionIndex()
    {
        for ($i = 101; $i <= 200; $i++) {
            $this->fetchCity($i);
        }
    }

    public function fetchCity($id)
    {
        $url = static::URL . '/search/category/' . $id . '/20/g187';
        $client = new Client();
        $response = $client->request('get', $url);
        $html = $response->getBody();
        $city = new City();
        $city->name = html5qp((string)$html, '#page-header > div.container > a.city.J-city')->text(); //存放城市名称的
        $city->id = $id;
        $city->save();

        $districts = html5qp((string)$html, '#region-nav')->children();
        foreach ($districts as $district) {
            $dis = new District(); //存放区的名称
            $dis->city_id = $city->id;
            $dis->name = $district->text();
            $dis->save();

            $regionUrl = ($district->find('a')->attr('href'));
            $client = new Client();
            $response = $client->request('get', static::URL . $regionUrl);
            $html = $html = $response->getBody();
            $regions = html5qp((string)$html, '#region-nav-sub')->children();
            foreach ($regions as $region) {
                if ($region->text() == '不限') {
                    continue;
                }

                $url = $region->find('a')->attr('href');
                preg_match('/r(\d+)/', $url, $res);

                if (empty($res) || count($res) != 2) {
                    continue;
                }

                $region_id = $res[1];
                $reg = new Region();
                $reg->city_id = $city->id;
                $reg->district_id = $dis->id;
                $reg->name = $region->text();
                $reg->region_id = $region_id;
                $reg->save();
            }
        }
    }
}
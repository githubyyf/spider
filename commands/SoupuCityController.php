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
 * 获取搜铺的招商类型获取=》城市综合体（xzid=9），购物中心（xzid=1），社区商业（xzid=2），商业街（xzid=3）
 * Class SiteController
 * @package app\commands
 */
class SoupuCityController extends Controller
{
    const URL = 'http://www.soupu.com/';
    ///UIPro/BusniessProject.aspx?pid=1&cid=742&xzid=9&oss=0北京昌平区
///UIPro/BusniessProject.aspx?pid=1&cid=743&xzid=9&oss=0"
///UIPro/BusniessProject.aspx?pid=3&xzid=9&oss=0


    public function actionIndex()
    {
        $url = static::URL . '/search/category/' . $id . '/20/g187';
        $client = new Client();
        $response = $client->request('get', $url);
        $html = $response->getBody();
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
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
class ProvinceController extends Controller
{
    const URL = 'http://www.dianping.com/citylist/citylist?citypage=1';


    //101-200
    //1-101
    public function actionIndex()
    {
        $this->saveProvince();
    }

    public function saveProvince()
    {
        $url = static::URL;
        $client = new Client();
        $response = $client->request('get', $url);
        $html = $response->getBody();
        $privinceNamube = [
            3 => 7,//华北东北=>6个省，初始值是2，最大值是7
            4 => 6,//华东地区=>5个省，初始值是2，最大值是6
            5 => 14,//中部西部=>13个省，初始值是2，最大值是14
            6 => 4,//华南地区=>3个省，初始值是2，最大值是4
        ];

//#divArea > li:nth-child(3) > strong 华北东北
//#divArea > li:nth-child(4) > strong 华东地区
//#divArea > li:nth-child(5) > strong 中部西部
//#divArea > li:nth-child(6) > strong 华南地区
        for ($i = 3; $i <= 6; $i++) {
            //#divArea > li:nth-child(3) > dl:nth-child(2) > dt   河北省
            //#divArea > li:nth-child(3) > dl:nth-child(7) > dt   山西省

            //#divArea > li:nth-child(4) > dl:nth-child(2) > dt   山东省
            //#divArea > li:nth-child(4) > dl:nth-child(6) > dt   福建省

            //#divArea > li:nth-child(5) > dl:nth-child(2) > dt   河南
            //#divArea > li:nth-child(5) > dl:nth-child(14) > dt   甘肃

            //#divArea > li:nth-child(6) > dl:nth-child(2) > dt   广东
            //#divArea > li:nth-child(6) > dl:nth-child(4) > dt   海南
            for ($j=2;$j<=$privinceNamube[$i];$j++){
                $province = html5qp((string)$html,
                    '#divArea > li:nth-child(' . $i . ') > dl:nth-child('.$j.') > dt')->text(); //获取省的名称$province = html5qp((string)$html,
                if (empty($province)){
                    continue;
                }
                $city = html5qp((string)$html, '#divArea > li:nth-child(' . $i . ') > dl:nth-child('.$j.') > dd')->childrenText();
                if (empty($city)){
                    continue;
                }
                $cityInfoes = explode('|', $city);
                foreach ($cityInfoes as $cityInfo) {
                    $cityName = trim($cityInfo);
                    if (empty($cityName)){
                        continue;
                    }
                    $tableCity = City::findOne(['name' => $cityName]);
                    if (!empty($tableCity)){
                        $tableCity->province_name = $province.'省';
                        $tableCity->save(false);
                    }
                }
            }

        }

    }
}
<?php
/**
 * Created by PhpStorm.
 * User: haowang
 * Date: 1016/12/10
 * Time: 下午4:42
 */

namespace app\commands;


use app\models\SoupuMapData;
use GuzzleHttp\Client;
use yii\console\Controller;
use yii\helpers\Json;

/**
 * 获取搜铺的招商类型获取=》城市综合体（xzid=9），购物中心（xzid=1），社区商业（xzid=2），商业街（xzid=3）
 * Class SiteController
 * todo ./yii soupu-map
 * @package app\commands
 */
class SoupuMapController extends Controller
{
    public static $PROVINCE_NAME;
    public static $CITY_NAME;
    public static $ARES_NAME;
    public static $FIRST_ERROR = [];
    const URL = 'http://www.soupu.com/Api/MapData.ashx';

    //http://www.soupu.com/UIPro/ProjectDetails.aspx?projectid=9299


    /**
     * @return bool
     */
    public function actionIndex()
    {
        $fourth = ['北京', '上海', '重庆', '天津'];
        $url = static::URL . '?action=Default';
        $client = new Client();
        $response = $client->request('get', $url);
        $html = $response->getBody();
        if ($response->getStatusCode() != 200) {
            sleep(60);
            $this->actionIndex();
        }
        try {
            if (json_decode($response->getBody()) == null) {
                var_dump($url);
                sleep(60);
                $this->actionIndex();

            }

            $ProvinceJsonData = Json::decode($html);
            $needDatas = $ProvinceJsonData['ds'];

            foreach ($needDatas as $needDataData) {
                if (in_array($needDataData['province'], $fourth)) {
                    static::$PROVINCE_NAME = $needDataData['province'];
                    if (!$this->CountryData($needDataData['province'])) {
                        sleep(60);
                        $this->CountryData($needDataData['province']);
                    }
                } else {
                    static::$PROVINCE_NAME = $needDataData['province'];
                    if (!$this->CityData($needDataData['province'])) {
                        sleep(60);
                        $this->CityData($needDataData['province']);
                    }

                }
            }
        } catch (\Exception $e) {
            sleep(60);
            $this->actionIndex();
        }
        if (!empty(static::$FIRST_ERROR)) {
            $data = \GuzzleHttp\json_encode(static::$FIRST_ERROR);
            $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'soupu' . DIRECTORY_SEPARATOR;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true); // 如果不存在则创建
            }
            file_put_contents($dir . 'error.json', $data);
        }
        return true;
    }


    /**
     * 获取根据省的名称每个城市的数据
     * @param $name
     * @return bool
     */
    public function CityData($name)
    {
        $url = static::URL . '?action=CityData&province=' . $name;
        $client = new Client();
        $response = $client->request('get', $url);
        $html = $response->getBody();
        if ($response->getStatusCode() != 200) {
            static::$FIRST_ERROR[] = $url;
            var_dump($url);
            return false;
        }

        try {
            if (json_decode($response->getBody()) == null) {
                static::$FIRST_ERROR[] = $url;
                var_dump($url);
                return false;
            }
            $cityJsonData = Json::decode($html);
        } catch (\Exception $e) {
            static::$FIRST_ERROR[] = $url;
            var_dump($url);
            return false;
        }

        $cityNeedDatas = $cityJsonData['ds'];
        foreach ($cityNeedDatas as $needData) {
            static::$CITY_NAME=$needData['city'];
            //如果省和城市数据已经存在就不再获取下面的区
//            $cityInfo = SoupuMapData::findOne(['city_name'=>$needData['city'].'市','province_name'=>static::$PROVINCE_NAME.'省']);
//            if (!empty($cityInfo)) {
//                continue;
//            }
            if (!$this->CountryData($needData['city'])) {
                sleep(10);
                $this->CountryData($needData['city']);
            }
        }
        return true;
    }


    /**
     * 获取城市下每个区的数据
     * @param $name
     * @return bool
     */
    public function CountryData($name)
    {
        static::$CITY_NAME = $name;
        $url = static::URL . '?action=CountryData&city=' . static::$CITY_NAME;
        $client = new Client();
        $response = $client->request('get', $url);
        $html = $response->getBody();

        if ($response->getStatusCode() != 200) {
            static::$FIRST_ERROR[] = $url;
            return false;
        }

        try {
            if (json_decode($html) == null) {
                static::$FIRST_ERROR[] = $url;
                return false;
            }
            $areaJsonData = Json::decode($html);
        } catch (\Exception $e) {
            static::$FIRST_ERROR[] = $url;
            return false;
        }

        $areaneedDatas = empty($areaJsonData['ds']) ? [] : $areaJsonData['ds'];

        foreach ($areaneedDatas as $areaneedData) {
            static::$ARES_NAME = $areaneedData['area'];
            if (!$this->ProjectData($areaneedData['area'])) {
                sleep(60);
                $this->ProjectData($areaneedData['area']);
            }
        }
        return true;

    }

    /**
     * 获取地图上的数据信息
     * 存入json文件并入库
     * @param $name
     * @return bool
     */
//    public function actionProjectData($name)
    public function ProjectData($name)
    {
        static::$ARES_NAME = $name;
        $url = static::URL . '?action=ProjectData&city=' . static::$CITY_NAME . '&country=' . static::$ARES_NAME;
        $client = new Client();
        $response = $client->request('get', $url);
        $html = $response->getBody();

        if ($response->getStatusCode() != 200) {
            static::$FIRST_ERROR[] = $url;
            return false;
        }
        /**
         * 数据存json
         */
//        $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'soupu' . DIRECTORY_SEPARATOR . static::$PROVINCE_NAME . DIRECTORY_SEPARATOR . static::$CITY_NAME . DIRECTORY_SEPARATOR . static::$ARES_NAME . DIRECTORY_SEPARATOR;
//        if (!is_dir($dir)) {
//            mkdir($dir, 0777, true); // 如果不存在则创建
//        }
//        file_put_contents($dir . 'mapData.json', $response->getBody());

        try {
            if (json_decode($response->getBody()) == null) {
                static::$FIRST_ERROR[] = $url;
                return false;
            }
            $projectJsonData = Json::decode($html);
        } catch (\Exception $e) {
            static::$FIRST_ERROR[] = $url;
            return false;
        }
        $projectNeedDatas = empty($projectJsonData['ds']) ? [] : $projectJsonData['ds'];

        foreach ($projectNeedDatas as $projectNeedData) {
            $model = new SoupuMapData();
            $model->province_name = static::$PROVINCE_NAME . '省';
            $model->city_name = static::$CITY_NAME . '市';
            $model->area_name = static::$ARES_NAME;
            $model->name = $projectNeedData['name'];
            $model->data_id = $projectNeedData['id'];
            $model->coordinate_x = $projectNeedData['lat'];
            $model->coordinate_y = $projectNeedData['lng'];
            $model->address = $projectNeedData['address'];
            $model->url = 'http://www.soupu.com/UIPro/ProjectDetails.aspx?projectid=' . $projectNeedData['id'];
            $model->save();
        }
        sleep(10);
        return true;

    }
}
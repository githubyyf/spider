<?php
namespace app\commands;

use GuzzleHttp\Client;
use yii\console\Controller;
use yii\helpers\Json;


/**
 * 获取商多多数据
 * todo ./yii shang-duo-duo-json
 * Class ShangDuoDuoJsonController
 * @package app\commands
 */
class ShangDuoDuoJsonController extends Controller
{
    const URL = 'http://www.shangdd.com/user_website/house/newMapList.do';
    private static $PROVINCE_NAME;
    private static $CITY_NAME;


    public function actionIndex()
    {
        $provinces = $this->getProvince();

        foreach ($provinces as $province) {
            static::$PROVINCE_NAME = $province['regionName'];
            $citys = $this->getCity($province);
            foreach ($citys as $city) {
                static::$CITY_NAME = $city['regionName'];
                $result = $this->getArea($city);
            }
        }
    }

    /**
     * 获取省Json的数据
     * @return mixed
     */
    private function getProvince()
    {
        $url = static::URL;
        $client = new Client();
        $response = $client->request('POST', $url, ['json' => ['params' => ['regionId' => 0]]]);
        $code = $response->getStatusCode();
        if ($code != 200) {
            sleep(20);
            $this->actionIndex();
        }
        $html = $response->getBody();
        $json = Json::decode($html);
        return $json['data']['sub'];
    }

    /**
     * 获取城市Json数据
     * @param $provinceInfo
     * @return mixed
     */
    private function getCity($provinceInfo)
    {
        $url = static::URL;
        $client = new Client();
        $response = $client->request('POST', $url, ['json' => ['params' => ['regionId' => $provinceInfo['regionId']]]]);
        $code = $response->getStatusCode();
        if ($code != 200) {
            sleep(20);
            $this->getProvince();
        }
        $html = $response->getBody();
        $json = Json::decode($html);
        return $json['data']['sub'];

    }


    /**
     * 获取每个区的数据，并存为json文件
     * @param $cityInfo
     * @return mixed
     */
    private function getArea($cityInfo)
    {
        $url = static::URL;
        $client = new Client();
        $response = $client->request('POST', $url, ['json' => ['params' => ['regionId' => $cityInfo['regionId']]]]);
        $code = $response->getStatusCode();
        if ($code != 200) {
            sleep(20);
            $this->getProvince();
        }
        $html = $response->getBody();
        $json = Json::decode($html);
        $datas = $json['data']['sub'];
        $jsonData = Json::encode($datas);
        $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'shangduoduo' . DIRECTORY_SEPARATOR . iconv('utf-8',
                'gb2312', static::$PROVINCE_NAME) . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true); // 如果不存在则创建
        }
        file_put_contents($dir . iconv('utf-8', 'gb2312',
                static::$CITY_NAME) . '.json', $jsonData);
        return true;
    }
}
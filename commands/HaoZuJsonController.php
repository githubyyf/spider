<?php
namespace app\commands;

use app\models\LianJianCity;
use GuzzleHttp\Client;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * 获取好租的Json数据
 * todo ./yii hao-zu-json
 * Class HaoZuJsonController
 * @package app\commands
 */
class HaoZuJsonController extends Controller
{
    const URL = 'https://www.haozu.com/';

    public function actionIndex()
    {
        $cityInfos = $this->getCity();
        foreach ($cityInfos as $cityInfo) {
            $area = $this->getArear($cityInfo);
            $district_ids = ArrayHelper::getColumn($area['district'],'district_id');

            $json = '222';
            $page = 1;
            while (!empty($json)) {
                $district_id = implode(',',$district_ids);
                $url = static::URL . 'ajax/map/?do=list&domain=' . $cityInfo['ch'] . '&district_ids='.$district_id.'&listorder=0&page='.$page;
                $client= new Client();
                $response = $client->request('GET', $url);
                $html = $response->getBody();
                $allJson=Json::decode($html);
                $json = $allJson['data']['buildingList']['items'];
//                var_dump($json);die();
                //存入json文件
                $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'haozu' . DIRECTORY_SEPARATOR . iconv('utf-8',
                        'gb2312', $cityInfo['city_name']) . DIRECTORY_SEPARATOR;
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true); // 如果不存在则创建
                }

                file_put_contents($dir . $page . '.json', Json::encode($json));
                $page+=1;
            }

        }

    }

    private function getCity()
    {
        $url = static::URL . 'gz/';
        $client = new Client();
        $response = $client->request('GET', $url);
        $code = $response->getStatusCode();

        if ($code != 200) {
            sleep(20);
            $this->actionIndex();
        }
        $html = $response->getBody();

        $infos = html5qp((string)$html,
            'body > div.banner-con > div.banner-top > div.site-city.fl > ul > li')->children();

        $data = [];
        foreach ($infos as $info) {
            $data[] = [
                'city_name' => $info->text(),
                'ch'        => explode('com/', $info->attr('href'))[1],
            ];
        }
        return $data;


    }

    private function getArear($city)
    {
        //https://www.haozu.com/gz/xzlditu/?is_ajax=1

        $url = static::URL . $city['ch'] . '/xzlditu/?is_ajax=1';
        $client = new Client();
        $response = $client->request('GET', $url);
        $code = $response->getStatusCode();

        if ($code != 200) {
            sleep(20);
            $this->actionIndex();
        }
        $html = $response->getBody();

        return Json::decode($html);

    }
}
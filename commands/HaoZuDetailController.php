<?php
namespace app\commands;

use app\models\LianJianCity;
use app\models\SystemGatheringPlace;
use GuzzleHttp\Client;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * 获取好租的Json数据
 * todo ./yii hao-zu-detail
 * Class HaoZuJsonController
 * @package app\commands
 */
class HaoZuDetailController extends Controller
{
    const URL = 'https://www.haozu.com';

    public function actionIndex()
    {
        $error = [];
//        $datas = SystemGatheringPlace::find()->where(['>', 'id', 81])->all();
        $datas = SystemGatheringPlace::find()->all();

        foreach ($datas as $data) {
            if (empty($data->contain)) {
                continue;
            }
            $url = static::URL . $data->contain;
            $client = new Client();
            $response = $client->request('GET', $url);
            $code = $response->getStatusCode();

            if ($code != 200) {
                $error[] = $url;
                continue;
            }
            $html = $response->getBody();
            $parent = html5qp((string)$html, '#building-baseinfo-div > ul')->children();
            foreach ($parent as $value) {
                $info = $value->text();
                $info = explode('：', $info);
                $first = trim($info[0]);
                $need = trim($info[1]);
                switch ($first) {
                    case '建筑面积':
                        //建筑面积
                        $number = explode('m²', $need)[0];
                        $data->building_area = empty($number) ? -1 : ($number / 10000);
                        var_dump($data->building_area);
                        break;
                    case '开发商':
                        //开发商
                        $pd = $need;
                        $data->property_developer = empty($pd) ? null : $pd;
                        var_dump($pd);
                        break;
                    case '物业公司':
                        //物业公司
                        $pc = $need;
                        $data->property_company = empty($pc) ? null : $pc;
                        break;
                    default:
                        continue;
                }
            }

            $data->remark = html5qp((string)$html,
                '#switch-widget')->text();
            if (!$data->save()) {
                var_dump($data->getErrors());
                die();
                $error[] = $url;
            }
            sleep(2);
        }

        var_dump($error);
    }
}
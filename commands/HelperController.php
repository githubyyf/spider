<?php
/**
 * Created by PhpStorm.
 * User: haowang
 * Date: 2017/1/25
 * Time: 下午2:34
 */

namespace app\commands;


use app\models\SystemCompetitionShop;
use GuzzleHttp\Client;
use yii\base\Controller;
use yii\helpers\Json;

class HelperController extends Controller
{
    public function actionIndex()
    {
        /**
         * @var $shops SystemCompetitionShop[]
         */
        $shops = SystemCompetitionShop::find()->where([
            '<=',
            'id',
            100000
        ])->all();

        $client = new Client();

        foreach ($shops as $shop) {
            $response = $client->request('GET', 'http://api.map.baidu.com/geocoder/v2/', [
                'delay'           => 100,
                'connect_timeout' => 5,
                'query'           => [
                    'ak'      => 'gvTcwVrGTmHoIGXlT9k3QXfATs4gBeaA',
                    'output'  => 'json',
                    'address' => $shop->location_detail
                ]
            ]);
            try {
                $data = Json::decode($response->getBody());
                $shop->coordinate_y = (string)$data['result']['location']['lng'];
                $shop->coordinate_x = (string)$data['result']['location']['lat'];
            } catch (\Exception $exception) {
                echo $shop->id . PHP_EOL;
                echo $shop->location_detail . PHP_EOL;
                echo $response->getBody() . PHP_EOL;
                die();
            }
            if ($shop->save()) {
                file_put_contents('1.txt', $shop->id);
            } else {
                var_dump($shop->errors);
                die();
            }
        }
    }

    public function actionTest()
    {
        $poi = 'IJRAESZVIUVBUD';
        $a = exec('node /Applications/XAMPP/xamppfiles/htdocs/basic/tool.js ' . $poi);
        list($y, $x) = explode(',', $a);
        echo $y . PHP_EOL;
        echo $x . PHP_EOL;
    }
}
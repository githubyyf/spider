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
use yii\helpers\Json;

/**
 * 获取搜铺的招商类型获取=》城市综合体（xzid=9），购物中心（xzid=1），社区商业（xzid=2），商业街（xzid=3）
 * todo ./yii soupu-city
 * Class SiteController
 * @package app\commands
 */
class SoupuCityController extends Controller
{
    const URL = 'http://www.soupu.com/UIPro/BusniessProject.aspx?pid=90&oss=0';
    ///UIPro/BusniessProject.aspx?pid=1&cid=742&xzid=9&oss=0北京昌平区
///UIPro/BusniessProject.aspx?pid=1&cid=743&xzid=9&oss=0"
///UIPro/BusniessProject.aspx?pid=3&xzid=9&oss=0


    public function actionIndex()
    {
        $types = [
            [
                'type' => 1,
                'name' => '购物中心',
                'page' => 683,
            ],
            [
                'type' => 2,
                'name' => '社区商业',
                'page' => 212,
            ],
            [
                'type' => 3,
                'name' => '商业街',
                'page' => 304,
            ],
            [
                'type' => 9,
                'name' => '城市综合体',
                'page' => 609,
            ],
        ];
        foreach ($types as $key => $type) {
            $url = static::URL . '&xzid=' . $type['type'];
            $client = new Client();
            $response = $client->request('get', $url);
            $page = 1;
            do {
                $html = $response->getBody();
                $data = html5qp((string)$html,
                    'body > div.area_15.clearfix > div.col_g.clearfix > div.col_g_l.c_fl')->children('body > div.area_15.clearfix > div.col_g.clearfix > div.col_g_l.c_fl > div.table_style2'); //获取总记录数量
                $needData = [];
                foreach ($data as $value) {
                    $name = $value->find('a')->text();
                    $href = $value->find('a')->attr('href');

                    //body > div.area_15.clearfix > div.col_g.clearfix > div.col_g_l.c_fl > div:nth-child(4) > table > tbody > tr:nth-child(2) > td:nth-child(2)
                    $time = $value->find('tr:nth-child(2) > td:nth-child(2)')->text();
                    $tempDate = explode('：', $time);

                    $needType = $value->find('tr:nth-child(3) > td:nth-child(1)')->text();
                    $tempType = explode('：', $needType);

                    $needType = $value->find('tr:nth-child(3) > td:nth-child(2)')->text();
                    $tempArea = explode('：', $needType);

                    $needType = $value->find('tr:nth-child(4) > td:nth-child(1)')->text();
                    $tempDesc = explode('：', $needType);
                    $needData[] = [
                        'name'    => trim($name),
                        'data'    => $tempDate[1]??null,
                        'type'    => $tempType[1]??null,
                        'area'    => $tempArea[1]??null,
                        'desc'    => $tempDesc[1]??null,
                        'href'    => 'http://www.soupu.com/UIPro/' . $href,
                        'data_id' => explode('=', $href)[1]??0,
                    ];
                }
                $jsonData = Json::encode($needData);
                $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'soupu' . DIRECTORY_SEPARATOR . $type['type'] . DIRECTORY_SEPARATOR;
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true); // 如果不存在则创建
                }
                file_put_contents($dir . $page . '.json', $jsonData);
                $page += 1;
                sleep(20);
            } while ($page <= $type['page']);
        }
    }
}
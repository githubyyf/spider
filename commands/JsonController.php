<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Region;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\console\Controller;

/**
 * 获取店铺数据本地持久化---将大众点评中的json数据下载到本地
 * Class JsonController
 * @package app\commands
 */
class JsonController extends Controller
{

    public function actionIndex()
    {
        $regions = Region::find()->all();
        /**
         * @var $region Region
         */

        foreach ($regions as $region) {
            $this->getJson($region);
        }
    }


    public function getJson(Region $region)
    {
        $page = 1;
        do {
            $client = new Client();
            $body = null;
            try {
                $response = $client->request('post', 'http://www.dianping.com/search/map/ajax/json', [
                    'headers'         => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36',
                        'Referer'    => 'http://www.dianping.com/search/map/category/10/20/g187r52',
                        'Origin'     => 'http://www.dianping.com',
                        'Host'       => 'www.dianping.com'
                    ],
                    'connect_timeout' => 5,
                    'form_params'     => [
                        'cityId'                  => $region->city_id,
//                        'cityId'                  => 3,
//                    'cityEnName'              => 'shanghai',
                        'promoId'                 => 0,
                        'shopType'                => 20,
                        'categoryId'              => 187,
                        'regionId'                => $region->region_id,
//                        'regionId'                => 1983,
                        'sortMode'                => 2,
                        'shopSortItem'            => 1,
                        'keyword'                 => '',
                        'searchType'              => 1,
                        'branchGroupId'           => 0,
                        'shippingTypeFilterValue' => 0,
                        'page'                    => $page,
                    ]
                ]);
                if ($response->getStatusCode() == 200) {
                    $body = \GuzzleHttp\json_decode($response->getBody());
                    $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'dazhong' . DIRECTORY_SEPARATOR . $region->city_id . DIRECTORY_SEPARATOR . $region->district_id . DIRECTORY_SEPARATOR . $region->region_id . DIRECTORY_SEPARATOR;
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true); // 如果不存在则创建
                    }
                    file_put_contents($dir . $page . '.json', $response->getBody());
                    $page++;
                }
            } catch (RequestException $exception) {
                sleep(60);
            }
        } while ($body == null || (($body->pageCount > $body->page) && $body->page < 50));
    }
}

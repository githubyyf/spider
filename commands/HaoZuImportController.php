<?php
/**
 * Created by PhpStorm.
 * User: haowang
 * Date: 2016/12/20
 * Time: 下午4:42
 */

namespace app\commands;


use app\models\SoupuMapData;
use app\models\SystemGatheringPlace;
use yii\console\Controller;
use yii\helpers\Json;

/**
 * 将搜铺的json文件入库
 * todo ./yii hao-zu-import
 * Class SiteController
 * @package app\commands
 */
class HaoZuImportController extends Controller
{
    public function actionIndex()
    {
        $provinceInfo = [
            '上海' => '上海市',
            '北京' => '北京市',
            '天津' => '天津市',
            '广州' => '广东省',
            '成都' => '四川省',
            '杭州' => '浙江省',
            '武汉' => '湖北省',
            '深圳' => '广东省',
            '西安' => '陕西省',
        ];
        $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'haozu' . DIRECTORY_SEPARATOR;
        $handler = opendir($dir);
        while (($fileName = readdir($handler)) !== false) {
            if ($fileName != "." && $fileName != "..") {
                $files[] = iconv('gb2312', 'utf-8', $fileName);
            }
        }

        if (!empty($files) && is_array($files)) {
            foreach ($files as $file) {
                $provinceName = $provinceInfo[$file];
                $cityName = $file . '市';
                $childDir = $dir . iconv('utf-8', 'gb2312', $file) . DIRECTORY_SEPARATOR;
                $childHandler = opendir($childDir);
                while (($childFileName = readdir($childHandler)) !== false) {
                    if ($childFileName != "." && $childFileName != "..") {
                        $needDir = $childDir . $childFileName;
                        $jsonFile = file_get_contents($needDir);

                        if (!empty($jsonFile)) {
                            //获取json数据开始入库
                            $content = Json::decode($jsonFile);

                            foreach ($content as $data) {
                                $model = new SystemGatheringPlace();
                                $model->name = $data['name'];
                                $model->province_name = $provinceName;
                                $model->city_name = $cityName;
                                $model->area_name = $data['district_name'] . '区';
                                $model->street_name = $data['street_name'];
                                $model->type = '写字楼';

                                $fourth = ['北京市', '上海市', '重庆市', '天津市'];
                                if (in_array($cityName, $fourth)) {
                                    $model->location = $model->city_name . $model->area_name . $data['address'];
                                } else {
                                    $model->location = $model->province_name . $model->city_name . $model->area_name . $data['address'];
                                }
                                $model->location_detail = $model->location;
                                $model->contain = $data['detail_url'];
                                $model->coordinate_y = $data['lng'];
                                $model->coordinate_x = $data['lat'];
                                $model->on_the_average = -1;
                                $model->created_at = time();
                                $model->updated_at = time();

                                if (!$model->save()) {
                                    var_dump($needDir);
                                    var_dump($model->getErrors());
                                }
                            }

                        }

                    }
                }
            }
        }

    }
}
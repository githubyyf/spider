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
 * todo ./yii soupu-import
 * Class SiteController
 * @package app\commands
 */
class SoupuImportController extends Controller
{
    public function actionIndex()
    {
        $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'soupu' . DIRECTORY_SEPARATOR;
        $handler = opendir($dir);
        while (($fileName = readdir($handler)) !== false) {
            if ($fileName != "." && $fileName != "..") {
                $files[] = $fileName;
            }
        }

        if (!empty($files) && is_array($files)) {
            foreach ($files as $file) {
                $childDir = $dir . $file . DIRECTORY_SEPARATOR;
                $childHandler = opendir($childDir);
                while (($childFileName = readdir($childHandler)) !== false) {
                    if ($childFileName != "." && $childFileName != "..") {
                        $needDir = $childDir . $childFileName;
                        $jsonFile = file_get_contents($needDir);

                        var_dump($needDir);
                        if (!empty($jsonFile)) {
                            //获取json数据开始入库
                            $content = Json::decode($jsonFile);

                            foreach ($content as $data) {
                                //根据名称和data_id在soupu_map_data表中获取
                                $mapModel = SoupuMapData::findOne([
                                    'data_id' => $data['data_id'],
                                    'name'    => $data['name']
                                ]);
                                if (empty($mapModel)) {
                                    continue;
                                }

                                $model = new SystemGatheringPlace();
                                $model->name = $data['name'];
                                $model->province_name = $mapModel->province_name;
                                $model->city_name = $mapModel->city_name;
                                $model->area_name = $mapModel->area_name;
                                $model->type = '商城';

                                $fourth = ['北京市', '上海市', '重庆市', '天津市'];
                                if (in_array($mapModel->city_name, $fourth)) {
                                    $model->province_name = $mapModel->city_name;
                                    $model->location = $mapModel->city_name . $mapModel->area_name . $mapModel->address;
                                } else {
                                    $model->province_name = $mapModel->province_name;
                                    $model->location = $mapModel->province_name . $mapModel->city_name . $mapModel->area_name . $mapModel->address;
                                }


                                $model->location_detail = $model->location;
                                $info = explode('年', $data['data']);
                                $model->built_year = ($data['data'] == '已开业' || $data['data'] == '局部开业') ? 1 : (isset($info[1]) ? $info[0] : 0);
                                $areaInfo = explode('万', $data['area'])[0];
                                $model->building_area = $areaInfo;
                                $model->remark = trim($data['desc']);
                                $model->contain = $data['type'];
                                $model->coordinate_y = $mapModel->coordinate_y;
                                $model->coordinate_x = $mapModel->coordinate_x;
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
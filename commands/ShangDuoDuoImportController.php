<?php
namespace app\commands;

use app\models\SystemGatheringPlace;
use yii\console\Controller;
use yii\helpers\Json;


/**
 * 将商多多的json文件入库
 * todo ./yii shang-duo-duo-import
 * Class ShangDuoDuoImportController
 * @package app\commands
 */
class ShangDuoDuoImportController extends Controller
{
    public function actionIndex()
    {
        $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'shangduoduo' . DIRECTORY_SEPARATOR;
        $handler = opendir($dir);
        while (($fileName = readdir($handler)) !== false) {
            if ($fileName != "." && $fileName != "..") {
                $files[] = iconv('gb2312', 'utf-8', $fileName);
            }
        }

        if (!empty($files) && is_array($files)) {
            foreach ($files as $file) {
                $provinceName = $file;
                $childDir = $dir . iconv('utf-8', 'gb2312', $file) . DIRECTORY_SEPARATOR;
                $childHandler = opendir($childDir);
                while (($childFileName = readdir($childHandler)) !== false) {
                    if ($childFileName != "." && $childFileName != "..") {
                        $cityName = explode('.', iconv('gb2312', 'utf-8', $childFileName))[0];

                        $needDir = $childDir . $childFileName;
                        $jsonFile = file_get_contents($needDir);
                        if (!empty($jsonFile)) {
                            //获取json数据开始入库
                            $content = Json::decode($jsonFile);
                            if (!is_array($content)){
                                var_dump($provinceName . $cityName);
                                continue;
                            }
                            foreach ($content as $data) {
                                //如果没有列表数据就进行下一个区
                                if (empty($data['houseList']) || !is_array($data['houseList'])) {
                                    var_dump($provinceName . $cityName);
                                    continue;
                                }

                                foreach ($data['houseList'] as $houseList) {
                                    $model = new SystemGatheringPlace();
                                    $model->name = $houseList['houseName'];
                                    $model->province_name = $provinceName;
                                    $model->city_name = $cityName;
                                    $model->area_name = $data['regionName'];
                                    $model->type = '商场';
                                    $model->location = rtrim($houseList['address'], '\n');
                                    $model->location_detail = $model->location;
                                    $model->built_year = empty($houseList['openedTime']) ? null : date('Y',
                                        $houseList['openedTime']);
                                    $model->building_area = $houseList['buildingArea'];
                                    $model->remark = trim($houseList['defaultImage']);
                                    $model->contain = $houseList['planFormat'];
                                    $model->coordinate_y = empty($houseList['longitude'])?-1:$houseList['longitude'];
                                    $model->coordinate_x = empty($houseList['latitude'])?-1:$houseList['latitude'];
                                    $model->on_the_average = -1;
                                    $model->created_at = time();
                                    $model->updated_at = time();

                                    if (!$model->save()) {
                                        var_dump($provinceName . $cityName);
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
}
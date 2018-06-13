<?php

namespace app\commands;

use app\models\City;
use app\models\District;
use app\models\Region;
use app\models\SystemCompetitionShop;
use yii\console\Controller;

/**
 * 导入数据库
 * Class ImportController
 * @package app\commands
 */
class ImportController extends Controller
{
    //现在已经入库的是广东省宝安区district_id=88
    public function actionIndex()
    {
        $four=[
            '北京',
            '重庆',
            '上海',
            '天津'
        ];
        $regions = Region::find()->where(['>=','district_id',88])->andWhere(['between','city_id',7,100])->all();
        /**
         * @var $region Region
         */
        foreach ($regions as $region) {
            $city = City::findOne($region->city_id);
            $cityName = $city->name; //市
            if (in_array($cityName,$four)){
                $provinceName = $cityName; //省
            }else{
                $provinceName=$city->province_name?$city->province_name:'';
            }
            $districtName = District::findOne($region->district_id)->name; //区
            $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR .'dazhong' . DIRECTORY_SEPARATOR. $region->city_id . DIRECTORY_SEPARATOR . $region->district_id . DIRECTORY_SEPARATOR . $region->region_id . DIRECTORY_SEPARATOR;
            if (is_dir($dir)) {
                $handle = opendir($dir);//打开文件夹
                while (($file = readdir($handle)) !== false) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }
                    $jsonFile = file_get_contents($dir . $file);
                    $content = json_decode($jsonFile);
                    foreach ($content->shopRecordBeanList as $item) {
                        $shopDetail = $item->shopRecordBean;
                        $systemCompetitionShop = new SystemCompetitionShop();
                        $systemCompetitionShop->name = $shopDetail->shopTotalName;
                        $systemCompetitionShop->per_customer_transaction = $shopDetail->avgPrice;
                        $systemCompetitionShop->location = $city->province_name.$cityName.$districtName.$shopDetail->address;
                        if (SystemCompetitionShop::find()->where([
                                'location' => $shopDetail->address
                            ])->count(1) >= 1
                        ) {
                            continue;
                        }
                        $a = exec('node /basic/tool.js ' . $shopDetail->poi);
                        list($y, $x) = explode(',', $a);
                        $systemCompetitionShop->coordinate_y = $y . '';
                        $systemCompetitionShop->coordinate_x = $x . '';
                        $systemCompetitionShop->branchName = $shopDetail->branchName;
                        $systemCompetitionShop->shopName = $shopDetail->shopName;
                        $systemCompetitionShop->city_name = $cityName;
                        $systemCompetitionShop->province_name = $provinceName;
                        $systemCompetitionShop->area_name = $districtName;
                        $systemCompetitionShop->phone = $shopDetail->phoneNo;
                        $systemCompetitionShop->product = $shopDetail->score1;
                        $systemCompetitionShop->environment = $shopDetail->score2;
                        $systemCompetitionShop->service = $shopDetail->score3;
                        $systemCompetitionShop->get_info_time = time();
                        $systemCompetitionShop->created_at = time();
                        $systemCompetitionShop->updated_at = time();
                        $systemCompetitionShop->save();
                    }
                }
            }
        }
    }
}
<?php

namespace app\commands;

use app\models\SystemSurroundingShop;
use yii\console\Controller;
use yii\helpers\Json;

/**
 * 将乐铺数据入库
 * Class ImportController
 * todo ./yii lepu-import
 * @package app\commands
 */
class LepuImportController extends Controller
{
    public function actionIndex()
    {
        $i=1;
        do{
            $dir = CONSOLE_HOME . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR .'lepu' . DIRECTORY_SEPARATOR. $i . DIRECTORY_SEPARATOR;
            $i++;
            if (is_dir($dir)) {
                try{
                    $handle = opendir($dir);//打开文件夹
                }catch (\Exception $e){
                    var_dump($dir);
                    die();
                }
                while (($file = readdir($handle)) !== false) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }
                    $jsonFile = file_get_contents($dir . $file);
                    $content = json_decode($jsonFile);
                    if (!empty($content) && !empty($content->data) && !empty($content->data->info)&&!empty($content->data->info->list)){
                        foreach ($content->data->info->list as $item) {
//                            var_dump($item->day_money[0]);die();
                            //数据入库
                            $model = new SystemSurroundingShop();
                            $model->province_name ='北京市';
                            $model->province =110000;
                            $model->city_name='北京市';
                            $model->city=110100;
                            $model->area_name=$item->area->name.'区';
                            $model->street_name=$item->street;
                            $model->name=$item->ctitle;
                            $model->shop_number=(string)$item->id;
                            //处理预估租金信息
                            if (!empty($item->money) && isset($item->money[0])){
                                if ($item->money[1]=="万"){
                                    $model->expect_rent=($item->money[0]*10000);
                                }else{
                                    $model->expect_rent=$item->money[0];
                                }
                            }else{
                                $model->expect_rent=-1;
                            }
                            $model->location ='北京市'.$item->address;
                            $model->location_detail =$model->location;
                            $model->coordinate_y =$item->longitude;
                            $model->coordinate_x =$item->latitude;
                            $model->business_format =$item->cbusiness;
                            $model->business_circle_type =$item->property_type;
                            $model->square =isset($item->shop_usage_area[0])?$item->shop_usage_area[0]:null;
                            $model->image_url =$item->main_img;
//                            $model->lease_state =$item->state;
                            $model->building_area =$model->square;
                            $model->use_area =$model->square;
                            $model->unit_rent = $item->day_money[0]?$item->day_money[0]:0;

                            if (!empty($item->cost) && is_numeric($item->cost[0])){
                                if ($item->cost[0][1]=='万'){
                                    $model->transfer_fee=($item->cost[0]*10000);
                                }else{
                                    $model->transfer_fee=$item->cost[0];
                                }
                            }else{
                                $model->transfer_fee =0;
                            }

                            $model->floor =$item->floor_num;
                            $model->split =$item->block=='否'?0:($item->block=='是'?1:null);
                            $model->business_status =$item->state;
                            $model->remark =$item->desc;
                            $model->created_at =time();
                            $model->updated_at =time();
                            $model->save(false);
                            //数据入库
                        }
                    }
                }
            }else{
                var_dump($dir);
                die();
            }
        }while(1);

    }
}
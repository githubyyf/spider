<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "soupu_map_data".
 *
 * @property integer $id
 * @property string $province_name
 * @property string $city_name
 * @property string $area_name
 * @property string $name
 * @property integer $data_id
 * @property double $coordinate_y
 * @property double $coordinate_x
 * @property string $address
 * @property string $url
 */
class SoupuMapData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'soupu_map_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_name', 'city_name', 'area_name', 'name', 'data_id', 'coordinate_y', 'coordinate_x', 'address', 'url'], 'required'],
            [['data_id'], 'integer'],
            [['coordinate_y', 'coordinate_x'], 'number'],
            [['province_name', 'city_name', 'area_name', 'name', 'address', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_name' => 'Province Name',
            'city_name' => 'City Name',
            'area_name' => 'Area Name',
            'name' => 'Name',
            'data_id' => 'Data ID',
            'coordinate_y' => 'Coordinate Y',
            'coordinate_x' => 'Coordinate X',
            'address' => 'Address',
            'url' => 'Url',
        ];
    }
}

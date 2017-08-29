<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "region".
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $district_id
 * @property string $name
 * @property integer $region_id
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'district_id', 'name', 'region_id'], 'required'],
            [['city_id', 'district_id', 'region_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => '城市',
            'district_id' => '区县',
            'name' => '名称',
            'region_id' => '大众店铺的id',
        ];
    }
}

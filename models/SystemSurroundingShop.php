<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "system_surrounding_shop".
 *
 * @property integer $id
 * @property string $name
 * @property string $shop_number
 * @property double $expect_rent
 * @property string $location
 * @property double $coordinate_y
 * @property double $coordinate_x
 * @property integer $province
 * @property string $province_name
 * @property integer $city
 * @property string $city_name
 * @property integer $area
 * @property string $area_name
 * @property integer $street
 * @property string $street_name
 * @property string $location_detail
 * @property string $business_format
 * @property string $business_circle_type
 * @property double $expect_sales
 * @property integer $expect_passenger_flow
 * @property integer $bearings
 * @property integer $site_condition
 * @property double $square
 * @property double $width
 * @property double $vision
 * @property string $accessibility
 * @property integer $park_condition
 * @property string $image_url
 * @property string $lease_state
 * @property string $lease_type
 * @property string $store_type
 * @property string $current_state
 * @property double $unit_rent
 * @property double $building_area
 * @property double $transfer_fee
 * @property string $property_right
 * @property double $use_area
 * @property string $floor
 * @property double $depth
 * @property double $height
 * @property integer $split
 * @property string $business_status
 * @property string $pay_type
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class SystemSurroundingShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_surrounding_shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'expect_rent', 'location', 'coordinate_y', 'coordinate_x', 'created_at', 'updated_at'], 'required'],
            [['expect_rent', 'coordinate_y', 'coordinate_x', 'expect_sales', 'square', 'width', 'vision', 'unit_rent', 'building_area', 'transfer_fee', 'use_area', 'depth', 'height'], 'number'],
            [['province', 'city', 'area', 'street', 'expect_passenger_flow', 'bearings', 'site_condition', 'park_condition', 'split', 'created_at', 'updated_at'], 'integer'],
            [['name', 'shop_number', 'location', 'province_name', 'city_name', 'area_name', 'street_name', 'location_detail', 'business_format', 'business_circle_type', 'accessibility', 'image_url', 'lease_state', 'lease_type', 'store_type', 'current_state', 'property_right', 'floor', 'business_status', 'pay_type', 'remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'shop_number' => 'Shop Number',
            'expect_rent' => 'Expect Rent',
            'location' => 'Location',
            'coordinate_y' => 'Coordinate Y',
            'coordinate_x' => 'Coordinate X',
            'province' => 'Province',
            'province_name' => 'Province Name',
            'city' => 'City',
            'city_name' => 'City Name',
            'area' => 'Area',
            'area_name' => 'Area Name',
            'street' => 'Street',
            'street_name' => 'Street Name',
            'location_detail' => 'Location Detail',
            'business_format' => 'Business Format',
            'business_circle_type' => 'Business Circle Type',
            'expect_sales' => 'Expect Sales',
            'expect_passenger_flow' => 'Expect Passenger Flow',
            'bearings' => 'Bearings',
            'site_condition' => 'Site Condition',
            'square' => 'Square',
            'width' => 'Width',
            'vision' => 'Vision',
            'accessibility' => 'Accessibility',
            'park_condition' => 'Park Condition',
            'image_url' => 'Image Url',
            'lease_state' => 'Lease State',
            'lease_type' => 'Lease Type',
            'store_type' => 'Store Type',
            'current_state' => 'Current State',
            'unit_rent' => 'Unit Rent',
            'building_area' => 'Building Area',
            'transfer_fee' => 'Transfer Fee',
            'property_right' => 'Property Right',
            'use_area' => 'Use Area',
            'floor' => 'Floor',
            'depth' => 'Depth',
            'height' => 'Height',
            'split' => 'Split',
            'business_status' => 'Business Status',
            'pay_type' => 'Pay Type',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

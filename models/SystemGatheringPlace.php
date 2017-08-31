<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "system_gathering_place".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $type
 * @property double  $on_the_average
 * @property double  $coordinate_y
 * @property double  $coordinate_x
 * @property string  $location
 * @property integer $province
 * @property string  $province_name
 * @property integer $city
 * @property string  $city_name
 * @property integer $area
 * @property string  $area_name
 * @property integer $street
 * @property string  $street_name
 * @property string  $location_detail
 * @property integer $population
 * @property double  $per_customer_transaction
 * @property integer $built_year
 * @property double  $property_management_fee
 * @property string  $property_company
 * @property string  $property_developer
 * @property integer $building_total
 * @property integer $houses_total
 * @property double  $building_area
 * @property string  $property_management_fee_detail
 * @property string  $contain
 * @property string  $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class SystemGatheringPlace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_gathering_place';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'name',
                    'type',
                    'on_the_average',
                    'coordinate_y',
                    'coordinate_x',
                    'location',
                    'created_at',
                    'updated_at'
                ],
                'required'
            ],
            [
                [
                    'on_the_average',
                    'coordinate_y',
                    'coordinate_x',
                    'per_customer_transaction',
                    'property_management_fee',
                    'building_area'
                ],
                'number'
            ],
            [
                [
                    'province',
                    'city',
                    'area',
                    'street',
                    'population',
                    'built_year',
                    'building_total',
                    'houses_total',
                    'created_at',
                    'updated_at'
                ],
                'integer'
            ],
            [
                [
                    'name',
                    'type',
                    'location',
                    'province_name',
                    'city_name',
                    'area_name',
                    'street_name',
                    'location_detail',
                    'property_company',
                    'property_developer',
                    'property_management_fee_detail',
                    'contain'
                ],
                'string',
                'max' => 255
            ],
            [['remark'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                             => 'ID',
            'name'                           => 'Name',
            'type'                           => 'Type',
            'on_the_average'                 => 'On The Average',
            'coordinate_y'                   => 'Coordinate Y',
            'coordinate_x'                   => 'Coordinate X',
            'location'                       => 'Location',
            'province'                       => 'Province',
            'province_name'                  => 'Province Name',
            'city'                           => 'City',
            'city_name'                      => 'City Name',
            'area'                           => 'Area',
            'area_name'                      => 'Area Name',
            'street'                         => 'Street',
            'street_name'                    => 'Street Name',
            'location_detail'                => 'Location Detail',
            'population'                     => 'Population',
            'per_customer_transaction'       => 'Per Customer Transaction',
            'built_year'                     => 'Built Year',
            'property_management_fee'        => 'Property Management Fee',
            'property_company'               => 'Property Company',
            'property_developer'             => 'Property Developer',
            'building_total'                 => 'Building Total',
            'houses_total'                   => 'Houses Total',
            'building_area'                  => 'Building Area',
            'property_management_fee_detail' => 'Property Management Fee Detail',
            'contain'                        => 'Contain',
            'remark'                         => 'Remark',
            'created_at'                     => 'Created At',
            'updated_at'                     => 'Updated At',
        ];
    }
}

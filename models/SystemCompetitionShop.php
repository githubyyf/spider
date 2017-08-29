<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 *
 * 系统竞争店铺
 * This is the model class for table "system_competition_shop".
 *
 * @property integer $id
 * @property string  $name
 * @property double  $per_customer_transaction
 * @property string  $location
 * @property string  $coordinate_y
 * @property string  $coordinate_x
 * @property string  $branchName
 * @property string  $shopName
 * @property integer $province
 * @property string  $province_name
 * @property integer $city
 * @property string  $city_name
 * @property integer $area
 * @property string  $area_name
 * @property integer $street
 * @property string  $street_name
 * @property string  $business_format
 * @property string  $business_circle_type
 * @property double  $expect_sales
 * @property double  $expect_rent
 * @property integer $expect_passenger_flow
 * @property integer $bearings
 * @property integer $site_condition
 * @property double  $square
 * @property double  $width
 * @property double  $vision
 * @property string  $accessibility
 * @property integer $park_condition
 * @property string  $image_url
 * @property integer $shelf_count
 * @property integer $sell_tobacco
 * @property string  $bid
 * @property string  $phone
 * @property double  $product
 * @property double  $environment
 * @property double  $service
 * @property string  $shop_hour
 * @property string  $shop_hour_detail
 * @property string  $competition_shop_type
 * @property string  $preferential_promotion
 * @property integer $get_info_time
 * @property string  $remark
 * @property string  $location_detail
 * @property integer $created_at
 * @property integer $updated_at
 */
class SystemCompetitionShop extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_competition_shop';
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
                    'per_customer_transaction',
                    'location',
                    'coordinate_y',
                    'coordinate_x',
                    'created_at',
                    'updated_at'
                ],
                'required'
            ],

            [
                [
                    'per_customer_transaction',
                    'expect_sales',
                    'expect_rent',
                    'square',
                    'width',
                    'vision',
                    'product',
                    'environment',
                    'service'
                ],
                'number'
            ],

            [
                [
                    'province',
                    'city',
                    'area',
                    'street',
                    'expect_passenger_flow',
                    'bearings',
                    'site_condition',
                    'park_condition',
                    'shelf_count',
                    'sell_tobacco',
                    'get_info_time',
                    'created_at',
                    'updated_at',
                    'shop_hour'
                ],
                'integer'
            ],

            [
                [
                    'name',
                    'location',
                    'coordinate_y',
                    'coordinate_x',
                    'branchName',
                    'shopName',
                    'province_name',
                    'city_name',
                    'area_name',
                    'street_name',
                    'location_detail',
                    'business_format',
                    'business_circle_type',
                    'accessibility',
                    'image_url',
                    'bid',
                    'phone',
                    'shop_hour_detail',
                    'competition_shop_type',
                    'preferential_promotion',
                    'remark'
                ],
                'string',
                'max' => 255
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                       => 'ID编号',
            'name'                     => '名称',
            'per_customer_transaction' => '客单价（人均消费-必填）',
            'location'                 => '位置',
            'coordinate_y'             => '坐标Y(经度）',
            'coordinate_x'             => '坐标X（纬度）',
            'branchName'               => '分店名',
            'shopName'                 => '店名',
            'province'                 => '省',
            'province_name'            => '省的名称',
            'city'                     => '市',
            'city_name'                => '市的名称',
            'area'                     => '区域',
            'area_name'                => '区域的名称',
            'street'                   => '乡镇（街道）',
            'street_name'              => '乡镇（街道）的名称',
            'location_detail'          => '地址详细信息',
            'business_format'          => '经营业态',
            'business_circle_type'     => '商圈类型',
            'expect_sales'             => '预估日商',
            'expect_rent'              => '预估租金',
            'expect_passenger_flow'    => '预估客流',
            'bearings'                 => '店铺方位',
            'site_condition'           => '立地条件',
            'square'                   => '门店面积',
            'width'                    => '门店店宽',
            'vision'                   => '门店视野',
            'accessibility'            => '接近性',
            'park_condition'           => '停车条件',
            'image_url'                => '详情页图片',
            'shelf_count'              => '货架组数',
            'sell_tobacco'             => '是否贩烟:1、是；0、否',
            'bid'                      => '品牌',
            'phone'                    => '电话',
            'product'                  => '产品（口味评分）',
            'environment'              => '环境（环境评分）',
            'service'                  => '服务(服务评分)',
            'shop_hour'                => '营业时间(1:24、2:非24)',
            'shop_hour_detail'         => '营业时间详细信息',
            'competition_shop_type'    => '类别',
            'preferential_promotion'   => '优惠促销',
            'get_info_time'            => '获取时间',
            'remark'                   => '备注',
            'created_at'               => '创建时间',
            'updated_at'               => '修改时间',
        ];
    }

    /**
     * 新增数据之前添加创建者
     *
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            if ($this->province_name == $this->city_name) {
                $locationDetail = $this->province_name . $this->area_name . $this->street . $this->location;
            } else {
                $locationDetail = $this->province_name . $this->city_name . $this->area_name . $this->street_name . $this->location;
            }
            $this->location_detail = $locationDetail;
        }

        return parent::beforeSave($insert);

    }
}

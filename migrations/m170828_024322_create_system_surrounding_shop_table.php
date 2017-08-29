<?php

use yii\db\Migration;

/**
 * Handles the creation of table `system_surrounding_shop`.
 */
class m170828_024322_create_system_surrounding_shop_table extends Migration
{
    const TABLE_NAME = '{{system_surrounding_shop}}';

    const TABLE_COMMENT = '系统周边店铺';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB COMMENT "' . static::TABLE_COMMENT . '"';
        }

        $this->createTable(static::TABLE_NAME, [
            'id'                    => $this->primaryKey(),
            'name'                  => $this->string()->notNull()->comment('名称'),
            'shop_number'           => $this->string()->comment('商铺编号（乐铺中的编号）'),
            'expect_rent'           => $this->double()->notNull()->comment('预估租金(月租金-必填)'),
            'location'              => $this->string()->notNull()->comment('位置'),
            'coordinate_y'          => $this->double()->notNull()->comment('坐标Y(经度）'),
            'coordinate_x'          => $this->double()->notNull()->comment('坐标X（纬度）'),
            'province'              => $this->integer()->comment('省'),
            'province_name'         => $this->string()->comment('省的名称'),
            'city'                  => $this->integer()->comment('市'),
            'city_name'             => $this->string()->comment('市的名称'),
            'area'                  => $this->integer()->comment('区域'),
            'area_name'             => $this->string()->comment('区域的名称'),
            'street'                => $this->integer()->comment('乡镇（街道）'),
            'street_name'           => $this->string()->comment('乡镇（街道）的名称'),
            'location_detail'       => $this->string()->comment('地址详细信息=省市区街道位置（当省=市的时候取其中一个）'),
            'business_format'       => $this->string()->comment('经营业态'),
            'business_circle_type'  => $this->string()->comment('商圈类型'),
            'expect_sales'          => $this->double()->comment('预估日商'),
            'expect_passenger_flow' => $this->integer()->comment('预估客流'),
            'bearings'              => $this->integer()->comment('店铺方位'),
            'site_condition'        => $this->integer()->comment('立地条件'),
            'square'                => $this->double()->comment('门店面积'),
            'width'                 => $this->double()->comment('门店店宽（面宽）'),
            'vision'                => $this->double()->comment('门店视野'),
            'accessibility'         => $this->string()->comment('接近性'),
            'park_condition'        => $this->integer()->comment('停车条件'),
            'image_url'             => $this->string()->comment('详情页图片'),
            'lease_state'           => $this->string()->comment('租赁状态'),
            'lease_type'            => $this->string()->comment('租赁类型'),
            'store_type'            => $this->string()->comment('商铺类型'),
            'current_state'         => $this->string()->comment('当前状态'),
            'unit_rent'             => $this->double()->comment('单位面积月租金'),
            'building_area'         => $this->double()->comment('建筑面积'),
            'transfer_fee'          => $this->double()->comment('转让费'),
            'property_right'        => $this->string()->comment('产权'),
            'use_area'              => $this->double()->comment('使用面积'),
            'floor'                 => $this->string()->comment('楼层'),
            'depth'                 => $this->double()->comment('进深'),
            'height'                => $this->double()->comment('层高'),
            'split'                 => $this->integer()->comment('是否分割:1、是；0、否'),
            'business_status'       => $this->string()->comment('经营状态'),
            'pay_type'              => $this->string()->comment('支付方式'),
            'remark'                => $this->string()->comment('备注'),
            'created_at'            => $this->integer()->notNull()->comment('创建时间'),
            'updated_at'            => $this->integer()->notNull()->comment('修改时间'),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }
}

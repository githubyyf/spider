<?php

use yii\db\Migration;

class m161219_051855_system_competition_shop extends Migration
{
    const TABLE_NAME = '{{system_competition_shop}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT "系统竞争店铺"';
        }

        $this->createTable(static::TABLE_NAME, [
            'id'                       => $this->primaryKey(),
            'name'                     => $this->string()->notNull()->comment('名称'),
            'per_customer_transaction' => $this->double()->notNull()->comment('客单价（人均消费-必填）'),
            'location'                 => $this->string()->notNull()->comment('位置'),
            'coordinate_y'             => $this->string()->notNull()->comment('坐标Y(经度）'),
            'coordinate_x'             => $this->string()->notNull()->comment('坐标X（纬度）'),
            'branchName'               => $this->string()->comment('分店'),
            'shopName'                 => $this->string()->comment('店铺名'),
            'province'                 => $this->integer()->comment('省'),
            'province_name'            => $this->string()->comment('省的名称'),
            'city'                     => $this->integer()->comment('市'),
            'city_name'                => $this->string()->comment('市的名称'),
            'area'                     => $this->integer()->comment('区域'),
            'area_name'                => $this->string()->comment('区域的名称'),
            'street'                   => $this->integer()->comment('乡镇（街道）'),
            'street_name'              => $this->string()->comment('乡镇（街道）的名称'),
            'location_detail'          => $this->string()->comment('地址详细信息=省市区街道位置（当省=市的时候取其中一个）'),
            'business_format'          => $this->string()->comment('经营业态'),
            'business_circle_type'     => $this->string()->comment('商圈类型'),
            'expect_sales'             => $this->double()->comment('预估日商'),
            'expect_rent'              => $this->double()->comment('预估租金'),
            'expect_passenger_flow'    => $this->integer()->comment('预估客流'),
            'bearings'                 => $this->integer()->comment('店铺方位'),
            'site_condition'           => $this->integer()->comment('立地条件'),
            'square'                   => $this->double()->comment('门店面积'),
            'width'                    => $this->double()->comment('门店店宽'),
            'vision'                   => $this->double()->comment('门店视野'),
            'accessibility'            => $this->string()->comment('接近性'),
            'park_condition'           => $this->integer()->comment('停车条件'),
            'image_url'                => $this->string()->comment('详情页图片'),
            'shelf_count'              => $this->integer()->comment('货架组数'),
            'sell_tobacco'             => $this->integer()->comment('是否贩烟:1、是；0、否'),
            'bid'                      => $this->string()->comment('品牌'),
            'phone'                    => $this->string()->comment('电话'),
            'product'                  => $this->float()->comment('产品（口味评分）'),
            'environment'              => $this->float()->comment('环境（环境评分）'),
            'service'                  => $this->float()->comment('服务(服务评分)'),
            'shop_hour'                => $this->integer()->comment('营业时间(1:24、2:非24)'),
            'shop_hour_detail'         => $this->string()->comment('营业时间详细信息'),
            'competition_shop_type'    => $this->string()->comment('竞争店铺类别'),
            'preferential_promotion'   => $this->string()->comment('优惠促销'),
            'get_info_time'            => $this->integer()->comment('获取时间'),
            'remark'                   => $this->string()->comment('备注'),
            'created_at'               => $this->integer()->notNull()->comment('创建时间'),
            'updated_at'               => $this->integer()->notNull()->comment('修改时间'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }
}

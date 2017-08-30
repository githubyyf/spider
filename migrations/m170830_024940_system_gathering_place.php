<?php

use yii\db\Migration;

class m170830_024940_system_gathering_place extends Migration
{
    const TABLE_NAME = '{{system_gathering_place}}';

    const TABLE_COMMENT = '系统集客点';

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
            'id'                             => $this->primaryKey(),
            'name'                           => $this->string()->notNull()->comment('名称'),
            'type'                           => $this->string()->notNull()->comment('类型(相当于物业类型-必填)'),
            'on_the_average'                 => $this->double()->notNull()->comment('挂牌均价(必填)'),
            'coordinate_y'                   => $this->double()->notNull()->comment('坐标经度Y'),
            'coordinate_x'                   => $this->double()->notNull()->comment('坐标纬度X'),
            'location'                       => $this->string()->notNull()->comment('位置'),
            'province'                       => $this->integer()->comment('省'),
            'province_name'                  => $this->string()->comment('省的名称'),
            'city'                           => $this->integer()->comment('市'),
            'city_name'                      => $this->string()->comment('市的名称'),
            'area'                           => $this->integer()->comment('区域'),
            'area_name'                      => $this->string()->comment('区域的名称'),
            'street'                         => $this->integer()->comment('乡镇（街道）'),
            'street_name'                    => $this->string()->comment('乡镇（街道）的名称'),
            'location_detail'                => $this->string()->comment('地址详细信息=省市区街道位置（当省=市的时候取其中一个）'),
            'population'                     => $this->integer()->comment('人口'),
            'per_customer_transaction'       => $this->double()->comment('客单价'),
            'built_year'                     => $this->integer()->comment('建造年代'),
            'property_management_fee'        => $this->double()->comment('物业费用'),
            'property_company'               => $this->string()->comment('物业公司'),
            'property_developer'             => $this->string()->comment('开发商'),
            'building_total'                 => $this->integer()->comment('楼栋总数'),
            'houses_total'                   => $this->integer()->comment('户数（房屋总数）'),
            'building_area'                  => $this->float()->defaultValue(-1)->comment('建筑面积'),
            'property_management_fee_detail' => $this->string()->comment('物业费详细信息'),
            'contain'                        => $this->string()->comment('包含类型（例如：商圈招商的类型,或房屋的类型）'),
            'remark'                         => $this->string()->comment('备注'),
            'created_at'                     => $this->integer()->notNull()->comment('创建时间'),
            'updated_at'                     => $this->integer()->notNull()->comment('修改时间'),
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

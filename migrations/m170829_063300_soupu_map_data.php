<?php

use yii\db\Migration;

class m170829_063300_soupu_map_data extends Migration
{
    const TABLE_NAME = '{{soupu_map_data}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT "搜铺的地图数据"';
        }

        $this->createTable(static::TABLE_NAME, [
            'id'            => $this->primaryKey(),
            'province_name' => $this->string()->notNull()->comment('省的名称'),
            'city_name'     => $this->string()->notNull()->comment('市的名称'),
            'area_name'     => $this->string()->notNull()->comment('区域的名称'),
            'name'          => $this->string()->notNull()->comment('名称'),
            'data_id'       => $this->integer()->notNull()->comment('对应的数据ID'),
            'coordinate_y'  => $this->double()->notNull()->comment('坐标Y(经度）'),
            'coordinate_x'  => $this->double()->notNull()->comment('坐标X（纬度）'),
            'address'       => $this->string()->notNull()->comment('地址信息'),
            'url'           => $this->string()->notNull()->comment('对应的详情URL'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }
}
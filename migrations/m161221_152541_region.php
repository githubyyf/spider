<?php

use yii\db\Migration;

class m161221_152541_region extends Migration
{
    const TABLE_NAME = '{{region}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT "区县"';
        }

        $this->createTable(static::TABLE_NAME, [
            'id'          => $this->primaryKey(),
            'city_id'     => $this->integer()->notNull()->comment('城市'),
            'district_id' => $this->integer()->notNull()->comment('区县'),
            'name'        => $this->string()->notNull()->comment('名称'),
            'region_id'   => $this->integer()->notNull()->comment('大众店铺的id')
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }
}

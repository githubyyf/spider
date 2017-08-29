<?php

use yii\db\Migration;

class m161221_152351_district extends Migration
{
    const TABLE_NAME = '{{district}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT "区县"';
        }

        $this->createTable(static::TABLE_NAME, [
            'id'      => $this->primaryKey(),
            'city_id' => $this->integer()->notNull()->comment('城市'),
            'name'    => $this->string()->notNull()->comment('名称'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }
}

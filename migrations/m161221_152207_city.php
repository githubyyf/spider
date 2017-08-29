<?php

use yii\db\Migration;

class m161221_152207_city extends Migration
{
    const TABLE_NAME = '{{city}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT "城市"';
        }

        $this->createTable(static::TABLE_NAME, [
            'id'   => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('名称'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }
}

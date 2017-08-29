<?php

use yii\db\Migration;

class m170828_092815_lian_jia_city extends Migration
{
    const TABLE_NAME = '{{lian_jia_city}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT "链家城市和url列表"';
        }

        $this->createTable(static::TABLE_NAME, [
            'id'   => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('名称'),
            'url' => $this->string()->notNull()->comment('对应的链家地址'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE_NAME);
    }
}

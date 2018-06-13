<?php

use yii\db\Migration;

class m180613_044310_tran_company extends Migration
{
    const TABLE_NAME = '{{tran_company}}';

    const TABLE_COMMENT = '快递公司';

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
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull()->comment('名称'),
            'index_url'  => $this->string()->comment('首页的url地址'),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('修改时间'),
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

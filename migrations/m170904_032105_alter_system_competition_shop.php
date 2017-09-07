<?php

use yii\db\Migration;

class m170904_032105_alter_system_competition_shop extends Migration
{
    const TABLE_NAME = '{{system_competition_shop}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        $this->renameColumn(static::TABLE_NAME, 'branchName', 'branch_name');
        $this->renameColumn(static::TABLE_NAME, 'shopName', 'shop_name');
    }
}

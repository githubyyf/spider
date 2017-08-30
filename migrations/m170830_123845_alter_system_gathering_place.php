<?php

use yii\db\Migration;

class m170830_123845_alter_system_gathering_place extends Migration
{
    const TABLE_NAME = '{{system_gathering_place}}';

    const TABLE_COMMENT = '系统集客点';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $tableOptions = null;
        $this->alterColumn(static::TABLE_NAME,'remark',$this->text()->comment('备注信息'));
    }
}

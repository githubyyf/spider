<?php

use yii\db\Migration;

class m170825_063950_add_city extends Migration
{
    const TABLE_NAME = '{{city}}';

    public function safeUp()
    {
        $this->addColumn(static::TABLE_NAME,'province_name',$this->string()->comment('省的名称'));
    }

    public function safeDown()
    {
        $this->dropColumn(static::TABLE_NAME,'province_name');
    }
}

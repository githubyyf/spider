<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tran_company".
 *
 * @property integer $id
 * @property string $name
 * @property string $index_url
 * @property integer $created_at
 * @property integer $updated_at
 */
class TranCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tran_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'index_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'index_url' => 'Index Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

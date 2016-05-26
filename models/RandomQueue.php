<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "random_queue".
 *
 * @property integer $type
 * @property integer $index
 * @property integer $resource_id
 */
class RandomQueue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'random_queue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'index', 'resource_id'], 'required'],
            [['type', 'index', 'resource_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Type',
            'index' => 'Index',
            'resource_id' => 'Resource ID',
        ];
    }
}

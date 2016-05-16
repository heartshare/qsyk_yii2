<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property string $desc
 * @property integer $daily_limit
 */
class Task extends \yii\db\ActiveRecord
{

    const TASK_LOGIN  = 1;
    const TASK_SHARE  = 2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc', 'daily_limit'], 'required'],
            [['daily_limit'], 'integer'],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'desc' => 'Desc',
            'daily_limit' => 'Daily Limit',
        ];
    }
}

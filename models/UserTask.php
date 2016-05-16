<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_task".
 *
 * @property integer $id
 * @property integer $task_id
 * @property integer $user_id
 * @property integer $points
 * @property string $finish_time
 */
class UserTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'user_id', 'points'], 'required'],
            [['task_id', 'user_id', 'points'], 'integer'],
            [['finish_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
            'points' => 'Points',
            'finish_time' => 'Finish Time',
        ];
    }
}

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
    public function getTask() {
        return self::hasOne(Task::className(), ['id'=>'task_id']);
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['id'], $fields['user_id'], $fields['finish_time'], $fields['task_id']);
        $fields[] = 'finishTimeDesc';
        $fields[] = 'task';
        return $fields; 
    }

    public function getFinishTimeDesc() {
        return date('Y-m-d H:i:s', $this->finish_time);
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

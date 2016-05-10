<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sign_history".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $sign_time
 * @property integer $points
 */
class SignHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sign_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'sign_time', 'points'], 'required'],
            [['user_id', 'points'], 'integer'],
            [['sign_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'sign_time' => 'Sign Time',
            'points' => 'Points',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "points_history".
 *
 * @property integer $id
 * @property integer $betting_id
 * @property string $time
 * @property integer $points
 */
class PointsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'points_history';
    }

    /**
     * @return ActiveQuery
     */
    public function getBetting()
    {
        return $this->hasOne(UsersBetting::className(), ['id' => 'betting_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['betting_id', 'time', 'points'], 'required'],
            [['betting_id', 'points'], 'integer'],
            [['time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'betting_id' => '用户id',
            'time' => '领取时间',
            'points' => '积分点数',
        ];
    }
}

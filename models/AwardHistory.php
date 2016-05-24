<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "award_history".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $betting_id
 * @property integer $period
 * @property integer $award_level
 * @property string $deliver_time
 */
class AwardHistory extends \yii\db\ActiveRecord
{

    static $AWARD_MAP = [
        1 => '一等奖',
        2 => '二等奖',
        3 => '三等奖',
        4 => '四等奖',
        5 => '五等奖',
        6 => '六等奖',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'award_history';
    }

    public function fields()
    {
        $fields = parent::fields();
        // remove fields that contain sensitive information
        $fields[] = 'awardDesc';
        return $fields;
    }

    public function getAwardDesc() {
        return self::$AWARD_MAP[$this->award_level];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'betting_id', 'period', 'award_level', 'deliver_time'], 'required'],
            [['user_id', 'betting_id', 'period', 'award_level'], 'integer'],
            [['deliver_time'], 'safe'],
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
            'betting_id' => 'Betting ID',
            'period' => 'Period',
            'award_level' => 'Award Level',
            'deliver_time' => 'Deliver Time',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prize_history".
 *
 * @property integer $id
 * @property string $period
 * @property integer $winning_number_id
 */
class PrizeHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prize_history';
    }

    public function getDrawResult()
    {
        return $this->hasOne(DoubleNumbers::className(), ['id' => 'winning_number_id']);
    }

    public function getPeriodAr()
    {
        return $this->hasOne(Period::className(), [ 'period_id' =>'period' ]);
    }
    public function getPeriodDesc() {
        $period = $this->periodAr;
        return $this->period . "期 " . date("Y-m-d", strtotime($period->draw_time));
    }
    public function fields()
    {
        $fields = parent::fields();
        // remove fields that contain sensitive information
        $fields[] = 'drawResult';
        $fields[] = 'periodDesc';
        $fields[] = 'periodAr';
        return $fields;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'period', 'winning_number_id'], 'required'],
            [[ 'winning_number_id', 'period'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'period' => 'Period',
            'winning_number_id' => 'Winning Number ID',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "period".
 *
 * @property integer $period_id
 * @property string $draw_time
 * @property integer $status
 * @property string $sale_starttime
 * @property string $sale_drawtime
 * @property string $sale_endtime
 */
class Period extends \yii\db\ActiveRecord
{


    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_FINISH = 2;
    static $STATUS_MAP = [
        self::STATUS_DELETED => '已删除',
        self::STATUS_ACTIVE => '进行中',
        self::STATUS_FINISH => '已开奖',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'period';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['period_id'], 'required'],
            [['period_id', 'status'], 'integer'],
            [['draw_time', 'sale_starttime', 'sale_drawtime', 'sale_endtime'], 'safe'],
        ];
    }


    public function fields()
    {
        $fields = parent::fields();
        // remove fields that contain sensitive information
        $fields[] = 'awardNum';
        return $fields;
    }
    public function getAwardNum() {
        return $this->hasMany(AwardHistory::className(), ['period' => 'period_id'])->count();
    }
    public function getPeriodDesc() {
        return "第" . $this->period_id . "期";
    }
    public function getStatusDesc() {
        return self::$STATUS_MAP[$this->status];
    }
    public function getRuleDesc() {
        return "rule";
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'period_id' => 'Period ID',
            'draw_time' => 'Draw Time',
            'status' => 'Status',
            'sale_starttime' => 'Sale Starttime',
            'sale_drawtime' => 'Sale Drawtime',
            'sale_endtime' => 'Sale Endtime',
        ];
    }
}

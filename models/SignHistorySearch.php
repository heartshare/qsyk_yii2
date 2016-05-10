<?php

namespace app\models;

use DateTime;
use Yii;
use yii\base\Model;

class SignHistorySearch extends Model
{
    public $user = false;
    public $sign_time;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sign_time'], 'required'],
            [['sign_time'], 'safe'],
            ['sign_time', 'validateSignValid'],
        ];
    }

    public function validateSignValid($attribute, $params)
    {
        $startTime = $endTime = 0;
        $dt = DateTime::CreateFromFormat("Y-m-d H:i:s", $this->sign_time);
        $seconds = intval($dt->format('H')) * 60 * 60 + intval($dt->format('i')) * 60 + intval($dt->format('s'));
        switch ($seconds) {
            case ($seconds >= 3 * 60 * 60 && $seconds < 12 * 60 * 60):
                $startTime = date('Y-m-d H:i:s', mktime(3 , 0, 0, date('m'), date('d'), date('Y')));
                $endTime = date('Y-m-d H:i:s', mktime(12 , 0, 0, date('m'), date('d'), date('Y')));
                break;
            case ($seconds >= 12 * 60 * 60 && $seconds < 20 * 60 * 60):
                $startTime = date('Y-m-d H:i:s', mktime(12, 0, 0, date('m'), date('d'), date('Y')));
                $endTime = date('Y-m-d H:i:s', mktime(20, 0, 0, date('m'), date('d'), date('Y')));
                break;
            case ($seconds >= 20 * 60 * 60 || $seconds < 3 * 60 * 60):
                $startTime = date('Y-m-d H:i:s', mktime(20, 0, 0, date('m'), date('d'), date('Y')));
                $endTime = date('Y-m-d H:i:s', mktime(3, 0, 0, date('m'), date('d') + 1, date('Y')));
                break;
        }
        if ($startTime && $endTime) {

            $exists = SignHistory::find()->where(['between', 'sign_time', $startTime, $endTime])->andWhere(['user_id'=>$this->user->id])->exists();
            if ($exists) {
                $this->addError('user', '用户已经签到过');
                return false;
            }
        } else {
            return false;
        }
        if ($this->user && $this->user->status != Users::STATUS_ACTIVE) {
            $this->addError('user', '用户不能签到，状态异常');
            return false;
        }

        return true;
    }
    public function allowSign($user)
    {
        $this->user = $user;
        return $this->validateSignValid(null, null);
    }
    public function sign($user)
    {
        $this->user = $user;
        if ($this->validate()) {
            $signHistory = new SignHistory();
            $signHistory->load([
                'sign_time' => $this->sign_time,
                'user_id' => $this->user->id,
                'points' => 10,
            ], '');
            if (!$signHistory->save()) {
                $this->addErrors($signHistory->getErrors());
                return false;
            }
            $this->user->points += 10;
            if (!$this->user->save()) {
                $this->addErrors($this->user->getErrors());
                return false;
            }
            return true;
        }
        return false;
    }

}

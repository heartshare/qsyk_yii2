<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/4/22
 * Time: 14:43
 */

namespace app\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class UsersBettingSearch extends Model
{
    private $_user = false;
    public $pick_time;
    public $period;
    public $numbers;


    public function rules()
    {
        return [
            [['pick_time', 'period', 'numbers'], 'required'],
            [['pick_time'], 'safe'],
            [[ 'period'], 'number'],
            ['numbers', 'each', 'rule' => ['integer']],
            ['period', 'exist', 'targetClass' => Period::className(), 'targetAttribute' => 'period_id'],
            ['period', 'validatePointsEnough'],
            ['period', 'validatePeriodOver'],
        ];
    }


    public function validatePointsEnough($attribute, $params)
    {
        if ($this->_user && $this->_user->points < 10) {
            $this->addError('user', '用户积分不足，无法投注');
            return;
        }
    }
    public function validatePeriodOver($attribute, $params)
    {
        $period = Period::findOne($this->period);
        if (!empty($period) && $period->status != Period::STATUS_ACTIVE) {
            $this->addError($attribute, '本期投注已结束');
            return;
        }

    }


    public function insert($user) {
        $this->_user = $user;
        if ($this->validate()) {
            $userBetting = new UsersBetting();
            $pickNumbers = new DoubleNumbers();
            $pointsHistory = new PointsHistory();
            $pickNumbers->load(array(
                'red_1st'=>$this->numbers[0],
                'red_2nd'=>$this->numbers[1],
                'red_3rd'=>$this->numbers[2],
                'red_4th'=>$this->numbers[3],
                'red_5th'=>$this->numbers[4],
                'red_6th'=>$this->numbers[5],
                'blue_1st'=>$this->numbers[6],
            ), '');

            if (!$pickNumbers->save()) {
                $this->addErrors($pickNumbers->getErrors());
                return false;
            }
            $userBetting->load(array(
                'pick_time'=>$this->pick_time,
                'period'=>$this->period,
                'user_id'=>$this->_user->id,
            ), '');
            $userBetting->link('pickNumber', $pickNumbers);
            if (!$userBetting->save()) {
                $this->addErrors($userBetting->getErrors());
                return false;
            }

            $pointsHistory->load([
                'time'=>$this->pick_time,
                'points'=>10,
            ], '');
            $pointsHistory->link('betting', $userBetting);
            if (!$pointsHistory->save()) {
                $this->addErrors($pointsHistory->getErrors());
                return false;
            }
            $this->_user->points -= 10;
            if (!$this->_user->save()) {
                $this->addErrors($this->_user->getErrors());
                return false;
            }
            return true;
        }
//        re($this->numbers);
        return false;


    }


    public function search($params) {
        $this->user_id = isset($params["user_id"]) ? $params["user_id"] : 0;
        $data = [];
        $periods = (new \yii\db\Query())
            ->select(['period'])
            ->from('users_betting')
            ->where(['user_id'=>$this->user_id])
            ->groupBy('period')
            ->column();
        foreach($periods as $period) {
            $bettingDataProvider = new ActiveDataProvider([
                'query' => UsersBetting::find()
                    ->where([
                        'user_id'=>$this->user_id,
                        'period'=>$period,
                    ]),
            ]);
            $periodAr = Period::findOne($period);

            $data[] = [
                'period'=>$period,
                'periodDesc'=>!empty($periodAr) ? $periodAr->periodDesc : "",
                'periodRule'=>!empty($periodAr) ? $periodAr->ruleDesc : "",
                'bettings'=>$bettingDataProvider->getModels(),

            ];
        }

        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['period'],
            ],
        ]);


        return $provider;
    }
}
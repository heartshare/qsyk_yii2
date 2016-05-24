<?php

namespace app\models;

use DateTime;
use Yii;
use yii\base\Model;

class UserTaskSearch extends Model
{
    private $user = false;
    private $hasLoginOnce = false;

    /**
     * @inheritdoc
     */
    public function validateShareValid($user) {
        $startTime = mktime(0 , 0, 0, date('m'), date('d'), date('Y'));
        $endTime = mktime(0 , 0, 0, date('m'), date('d') + 1, date('Y'));
        $exists = UserTask::find()->where(['between', 'finish_time', $startTime, $endTime])->andWhere([
            'user_id'=>$user->id,
            'task_id'=>Task::TASK_SHARE,
        ])->exists();
        if (!$exists) {
            return true;
        } else {
            $this->addError('user', '用户本日分享已经领取过奖励了');
            return false;
        }

    }
    public function validateSignValid($user)
    {

        $startTime = mktime(0 , 0, 0, date('m'), date('d'), date('Y'));
        $endTime = mktime(0 , 0, 0, date('m'), date('d') + 1, date('Y'));
        $loginCount = UserTask::find()->where(['between', 'finish_time', $startTime, $endTime])->andWhere([
            'user_id'=>$user->id,
            'task_id'=>Task::TASK_LOGIN,
        ])->count();
        switch ($loginCount) {
            case 0:
                return true;
                break;
            case 1:
                $this->hasLoginOnce = true;
                return true;
                break;
            default:
                $this->addError('user', '用户本日已经领取过奖励了');
                return false;
                break;
        }
    }

    public function sign()
    {
        $user = Yii::$app->user->identity;
        if ($this->validate() && $this->validateSignValid($user)) {
            $userTask = new UserTask();
            $attributes = [
                'task_id' => Task::TASK_LOGIN,
                'user_id' => $user->id,
                'finish_time' => time(),
            ];
            if ($this->hasLoginOnce) {
                $attributes['points'] = 5;
            } else {
                $attributes['points'] = 10;
            }
            $userTask->load($attributes, '');
            if (!$userTask->save()) {
                $this->addErrors($userTask->getErrors());
                return false;
            }
            $user->points += $attributes['points'];
            if (!$user->save()) {
                $this->addErrors($user->getErrors());
                return false;
            }
            return true;
        }
        return false;
    }


    public function share()
    {
        $user = Yii::$app->user->identity;
        if ($this->validate() && $this->validateShareValid($user)) {
            $userTask = new UserTask();
            $attributes = [
                'task_id' => Task::TASK_SHARE,
                'user_id' => $user->id,
                'points' => 5,
                'finish_time' => time(),
            ];
            $userTask->load($attributes, '');
            if (!$userTask->save()) {
                $this->addErrors($userTask->getErrors());
                return false;
            }
            $user->points += $attributes['points'];
            if (!$user->save()) {
                $this->addErrors($user->getErrors());
                return false;
            }
            return true;
        }
        return false;
    }

}

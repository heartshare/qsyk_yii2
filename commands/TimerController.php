<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/4/27
 * Time: 13:08
 */

namespace app\commands;


use app\models\AwardHistory;
use app\models\Period;
use app\models\PrizeHistory;
use app\models\UsersBetting;
use yii\console\Controller;

class TimerController extends Controller
{
    public function actionEnd($period = "") {
        $now = date('Y-m-d H:i:s');
        if (empty($period)) {
            $periodAr = Period::find()
                ->where(['status'=>1])
                ->andWhere(['<', 'sale_endtime', $now])
                ->orderBy('period_id desc')->one();
            $period = $periodAr->period_id;
        } else {
            $periodAr = Period::findOne($period);
        }

        if ($now >= $periodAr->sale_endtime && $periodAr->status == Period::STATUS_ACTIVE) {
            $periodAr->status = Period::STATUS_FINISH;
            if (!$periodAr->save()) {
                var_dump($periodAr->getErrors());
                exit;
            }
        }

    }

    public function actionPrize($period = "") {
        if (empty($period)) {
            $periodAr = Period::find()->where(['status'=>2])->orderBy('period_id desc')->one();
            $period = $periodAr->period_id;
        } else {
            $periodAr = Period::findOne($period);
        }
        $prizeHistory = PrizeHistory::find()->where([
            'period'=>$period,

        ])->one();
        if (!empty($prizeHistory)) {
            $drawResult = $prizeHistory->drawResult;
            $drawRedNoArr = [
                $drawResult->red_1st,
                $drawResult->red_2nd,
                $drawResult->red_3rd,
                $drawResult->red_4th,
                $drawResult->red_5th,
                $drawResult->red_6th,
            ];
            $drawBlueNoArr = [
                $drawResult->blue_1st
            ];

            //一等奖

            $pickArr = UsersBetting::find()->where(['period'=>$period,'is_award'=>0])->all();
            foreach($pickArr as $pick) {
                if ($pick->is_award) {
                    continue;
                }
                $numbers = $pick->pickNumber;
                $redNos = [
                    $numbers->red_1st,
                    $numbers->red_2nd,
                    $numbers->red_3rd,
                    $numbers->red_4th,
                    $numbers->red_5th,
                    $numbers->red_6th
                ];
                $blueNos = [
                    $numbers->blue_1st
                ];
                $redDiff = array_diff($drawRedNoArr, $redNos);
                $blueDiff = array_diff($drawBlueNoArr, $blueNos);

                $awardLevel = 0;
                if (count($redDiff) == 0 && count($blueDiff) == 0) {
                    //一等奖
                    $awardLevel = 1;
                } elseif (count($redDiff) == 0 && count($blueDiff) == 1) {
                    //二等奖
                    $awardLevel = 2;
                } elseif (count($redDiff) == 1 && count($blueDiff) == 0) {
                    //三等奖
                    $awardLevel = 3;
                } elseif ((count($redDiff) == 1 && count($blueDiff) == 1) || (count($redDiff) == 2 && count($blueDiff) == 0)) {
                    //四等奖
                    $awardLevel = 4;
                } elseif ((count($redDiff) == 2 && count($blueDiff) == 1) || (count($redDiff) == 3 && count($blueDiff) == 0)) {
                    //五等奖
                    $awardLevel = 5;
                } elseif ((count($redDiff) == 6 && count($blueDiff) == 0)
                    || (count($redDiff) == 5 && count($blueDiff) == 0)
                    || (count($redDiff) == 4 && count($blueDiff) == 0)) {
                    //六等奖
                    $awardLevel = 6;
                }
                if ($awardLevel > 0) {
                    $awardHistory = new AwardHistory();
                    $awardHistory->load([
                        'user_id'=>$pick->user_id,
                        'betting_id'=>$pick->id,
                        'period'=>$pick->period,
                        'award_level'=>$awardLevel,
                        'deliver_time'=>date('Y-m-d H:i:s'),
                    ], '');
                    if (!$awardHistory->save()) {
                        var_dump($awardHistory->getErrors());
                        exit;
                    }
                }
                $pick->is_award = 1;
                $pick->save();


            }

            if (empty($periodAr->draw_time)) {
                $periodAr->draw_time = date('Y-m-d H:i:s');
                $periodAr->save();
            }
        }
    }

}
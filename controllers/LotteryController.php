<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/17
 * Time: 12:35
 */

namespace app\controllers;


use app\models\Period;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class LotteryController extends Controller
{
    public $layout = 'mobile';
    public function actionIndex()
    {
        $user = \Yii::$app->user->identity;
        return $this->render('index.tpl', ['user'=>$user]);
    }

    public function actionInit() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Yii::$app->user->identity;
        $periodAr = Period::find()->where(['status'=>1])->orderBy('period_id desc')->one();
        $lastPeriodAr = Period::find()->where(['status'=>2])->orderBy('period_id desc')->one();
        $ret['banners'] = [
            [
                'img'=>'http://placehold.it/300x100',
                'title'=>'直播吧',
                'link'=>'http://m.zhiboba.com',
            ],
            [
                'img'=>'http://placehold.it/600x200',
                'title'=>'百度',
                'link'=>'http://m.baidu.com',
            ],

        ];
        $ret['period'] = [
            'current'=>[
                'period'=>$periodAr->period_id,
                'endtime'=>$periodAr->sale_endtime,
                'drawtime'=>$periodAr->sale_drawtime,
                'starttime'=>$periodAr->sale_starttime,
            ],

        ];
        if ($lastPeriodAr) {
            $ret['period']['last'] = [
                'period'=>$lastPeriodAr->period_id,
                'endtime'=>$lastPeriodAr->sale_endtime,
                'drawtime'=>$lastPeriodAr->sale_drawtime,
                'starttime'=>$lastPeriodAr->sale_starttime,
                'awardNum'=>$lastPeriodAr->awardNum,
            ];
        } else {
            $ret['period']['last'] = null;
        }
        if (!empty($user)) {
            $ret['user'] = [
                'username'=>$user->user_name,
                'points'=>$user->points,
            ];
        } else {
            $ret['user'] = null;
        }
        return $ret;

    }
}
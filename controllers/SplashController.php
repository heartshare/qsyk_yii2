<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/20
 * Time: 9:35
 */

namespace app\controllers;

use Yii;
use app\models\ConfigInfo;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\Response;

class SplashController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->response->format = 'encrypt';
        $info = ConfigInfo::getConfigInfo();
        $minfo = ConfigInfo::getMobileInfo();
        if ($minfo['system'] != 'android') {
            $info[0]['ad']['ads'] = array();
        }
        else {
            $info[0]['rateEnable'] = 0;
            $info[0]['rateTitle'] = '给个好评后，会展示更多劲爆惊喜图片，你懂的！';
            $info[0]['rateConfirm'] = '赏你好评';
            $info[0]['rateRefuse'] = '残忍拒绝';

        }
        if ($minfo['app'] == 'beauty') {
            $info[0]['ad']['enable'] = 0;
        }
        if ($minfo['app'] == 'beauty') {
        }
        $info[0]['ad']['ads'] = array();
        //print_r($info);exit;
        return $info;
    }
}
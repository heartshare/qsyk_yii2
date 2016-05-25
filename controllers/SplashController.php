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
		if (!isset($_GET['debug'])) {
	        Yii::$app->response->format = 'encrypt';
		}
		else {
	        Yii::$app->response->format = 'json';
		}
        $info = ConfigInfo::getConfigInfo();
        $minfo = ConfigInfo::getMobileInfo();
		if ($minfo['system'] == 'android') {
			$info['config']['lotteryEnable'] = 1;
		}
		elseif ($minfo['appversion'] == '10004') {
			$info['config']['lotteryEnable'] = 1;
		}
        if ($minfo['system'] != 'android') {
            $info['ad']['ads'] = array();
        }
        else {
            $info['rateEnable'] = 0;
            $info['rateTitle'] = '给个好评后，会展示更多劲爆惊喜图片，你懂的！';
            $info['rateConfirm'] = '赏你好评';
            $info['rateRefuse'] = '残忍拒绝';
        }
		$info['config']['beautyEnable'] = 0;
        return $info;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/11
 * Time: 14:29
 */

namespace app\controllers;


use app\models\LoginForm;
use app\models\PushNotifyForm;
use Yii;
use yii\web\Controller;

class PushNotifyController extends Controller
{

    
    public function actionIndex() {
        echo "123";
    }




    public function actionPush()
    {
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
        $model = new PushNotifyForm();
        if ($model->load(Yii::$app->request->post()) && $model->push()) {
            return $this->render('push_success');
        }
        return $this->render('push', [
            'model' => $model,
        ]);
    }


}
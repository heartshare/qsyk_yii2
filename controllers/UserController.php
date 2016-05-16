<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/4/25
 * Time: 21:24
 */

namespace app\controllers;


use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\SignHistorySearch;
use app\models\User;
use app\models\UserTaskSearch;
use Yii;
use yii\base\Controller;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class UserController extends Controller
{
    

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['dashboard', 'sign', 'info', 'sign-task'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['dashboard', 'sign', 'info', 'sign-task'],
            'rules' => [
                [
                    'actions' => ['dashboard', 'sign-task', 'info'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionInfo() {
        $user = Yii::$app->user->identity;
        return $user;
    }

    public function actionSignTask() {
        $taskSearch = new UserTaskSearch();
        if ($taskSearch->load(Yii::$app->request->post(), '') && $taskSearch->sign()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $taskSearch->getFirstErrors())];
    }

    public function actionShareTask() {
        $taskSearch = new UserTaskSearch();
        if ($taskSearch->load(Yii::$app->request->post(), '') && $taskSearch->share()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $taskSearch->getFirstErrors())];
    }
    public function actionAllowsign()
    {
        $user = Yii::$app->user->identity;
        $signHistory = new SignHistorySearch();
        if ($signHistory->load(Yii::$app->request->post(), '')) {
            return  $signHistory->allowSign($user);
        } else {
            return false;
        }
    }
    public function actionSign() {
        $user = Yii::$app->user->identity;
        $signHistory = new SignHistorySearch();
        if ($signHistory->load(Yii::$app->request->post(), '') && $signHistory->sign($user)) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $signHistory->getFirstErrors())];
    }
    public function actionRegister() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->register()) {
            return ["status"=>0, "message"=>"", "user"=>$model->user];
        }
        return ["status"=>1, "message"=>implode(",", $model->getFirstErrors())];
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            return ['access_token' => Yii::$app->user->identity->getAuthKey()];
        } else {
            $model->validate();
            return $model;
        }
    }


    public function actionDashboard()
    {
        $response = [
            'username' => Yii::$app->user->identity->username,
            'access_token' => Yii::$app->user->identity->getAuthKey(),
        ];
        return $response;
    }


//    public function actionLogin() {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
//            return ["status"=>0, "message"=>""];
//        }
//        return ["status"=>1, "message"=>implode(",", $model->getFirstErrors())];
//    }

}
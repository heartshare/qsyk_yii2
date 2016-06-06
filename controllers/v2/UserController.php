<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/4/25
 * Time: 21:24
 */

namespace app\controllers\v2;


use app\models\v2\LoginForm;
use app\models\SignHistorySearch;
use app\models\User;
use app\models\UserTaskSearch;
use app\models\v2\BindForm;
use app\models\v2\MobileValidForm;
use app\models\v2\NameValidForm;
use app\models\v2\RegisterForm;
use app\models\v2\ThirdRegisterForm;
use app\models\v2\ThirdValidForm;
use app\models\v2\TokenForm;
use app\models\v2\UserInfoForm;
use app\models\v2\VerifyForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class UserController extends Controller
{
    

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['info',  'register', 'third-login', 'login', 'edit', 'bind', 'third-register'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['info', 'register', 'third-login', 'login', 'edit', 'bind', 'third-register'],
            'rules' => [
                [
                    'actions' => ['info', 'register', 'third-login', 'login', 'edit', 'bind', 'third-register'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionInfo() {

        $user = Yii::$app->user->identity;
        $user->client = Yii::$app->request->get('client', null);
        return $user;
    }

    public function actionRegister() {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->register()) {
            return ["status"=>0, "message"=>"", "user"=>$model->user];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }

    public function actionThirdRegister() {
        $model = new ThirdRegisterForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->thirdRegister()) {
            return ["status"=>0, "message"=>"", "user"=>$model->user];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }


    public function actionLogin() {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            return ["status"=>0, "message"=>"", "user"=>$model->user];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }

    public function actionThirdLogin() {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->thirdLogin()) {
            return ["status"=>0, "message"=>"", "user"=>$model->user];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }

    public function actionRequestCode() {
        $model = new VerifyForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->request()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }

    public function actionVerifyCode() {
        $model = new VerifyForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->verify()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }

    public function actionEdit() {
        $model = new UserInfoForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->edit()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }

    public function actionBind() {
        $model = new BindForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->bind()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }

    public function actionRefresh() {
        $model = new TokenForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->revoke()) {
            return ["status"=>0, "message"=>"", "user"=>$model->user];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }

    public function actionMobileValid() {
        $model = new MobileValidForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }

    public function actionThirdValid() {
        $model = new ThirdValidForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }

    public function actionNameValid() {
        $model = new NameValidForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode("\n", $model->getFirstErrors())];
    }
}
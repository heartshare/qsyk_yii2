<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/17
 * Time: 11:22
 */

namespace app\controllers;

use app\models\Resource;
use app\models\UserTask;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class UserTaskController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['index', 'view'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        return new ActiveDataProvider([
            'query' =>  UserTask::find()->where(['user_id'=>$user->id])->orderBy('finish_time desc')
        ]);
    }

    public function actionView($id)
    {
        return Resource::findOne($id);
    }

}
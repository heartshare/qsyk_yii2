<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/10
 * Time: 16:57
 */

namespace app\controllers;

use app\models\Resource;
use app\models\ResourceFavorite;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class FavoriteController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['index'],
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
            'query' => ResourceFavorite::find()
                ->where(['user_id'=>$user->id])
            ->andWhere(['>', 'resource_id', 0])
            ->orderBy('time desc')
        ]);
    }

    public function actionView($id)
    {
        return Resource::findOne($id);
    }

}
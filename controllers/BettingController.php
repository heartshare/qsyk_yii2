<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/4/22
 * Time: 14:39
 */

namespace app\controllers;


use app\models\UsersBetting;
use app\models\UsersBettingSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;

class BettingController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $behaviors['contentNegotiator'] = [
//            'class' => ContentNegotiator::className(),
//            'formats' => [
//                'application/javascript' => Response::FORMAT_JSONP,
//            ],
//        ];

        return $behaviors;
    }


    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSONP;
        $user = Yii::$app->user->identity;

        $callback = Yii::$app->request->get('callback', '');
        $dataProvider = new ActiveDataProvider([
            'query' => UsersBetting::find()
                ->where(['user_id'=>$user->id])
                ->orderBy("pick_time desc"),
        ]);
        return ['data'=>$dataProvider->getModels(), 'callback'=>$callback];
    }

    public function actionView($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSONP;
        $callback = Yii::$app->request->get('callback', '');
        return ['data'=>UsersBetting::findOne($id), 'callback'=>$callback];
    }


    public function actionBet()
    {

        $user = Yii::$app->user->identity;
        $bettingSearch = new UsersBettingSearch();
        if ($bettingSearch->load(Yii::$app->getRequest()->post(), '') && $bettingSearch->insert($user)) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $bettingSearch->getFirstErrors())];

    }



}
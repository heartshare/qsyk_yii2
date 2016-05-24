<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/4/22
 * Time: 10:36
 */

namespace app\controllers;


use app\models\PrizeHistory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class AwardController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/javascript' => Response::FORMAT_JSONP,
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        $callback = Yii::$app->request->get('callback', '');
        $dataProvider = new ActiveDataProvider([
            'query' => PrizeHistory::find()
                ->orderBy("period desc"),
        ]);
        return ['data'=>$dataProvider->getModels(), 'callback'=>$callback];
    }

    public function actionView($id)
    {

         return ['data'=>PrizeHistory::findOne($id), 'callback'=>'JSON_CALLBACK'];
    }
}
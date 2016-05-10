<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/6
 * Time: 14:46
 */

namespace app\controllers;


use app\components\QsEncodeHelper;
use app\models\Resource;
use app\models\ResourceLike;
use app\models\ResourceLikeForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class ToolsController extends Controller
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

    public function actionSid() {
        $id = Yii::$app->request->get('id');
        return QsEncodeHelper::setSid($id);
    }
}
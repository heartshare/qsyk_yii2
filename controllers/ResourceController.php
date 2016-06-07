<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/6
 * Time: 11:42
 */

namespace app\controllers;

use app\commands\DataController;
use app\components\QsEncodeHelper;
use app\models\Resource;
use app\models\ResourceFavoriteForm;
use app\models\ResourceLike;
use app\models\ResourceLikeForm;
use app\models\ResourceReportForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class ResourceController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['like', 'hate', 'fav', 'report'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
	$behaviors[] =  [
            'class' => 'yii\filters\HttpCache',
            'only' => ['index'],
            'lastModified' => function ($action, $params) {
                $q = new \yii\db\Query();
                return $q->from('random_cache')->max('updated_at');
            },
        ];

        return $behaviors;
    }

    public function actionLike()
    {
        $likeForm = new ResourceLikeForm();
        if ($likeForm->load(Yii::$app->getRequest()->post(), '') && $likeForm->like(ResourceLike::STATUS_LIKE)) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $likeForm->getFirstErrors())];
    }

    public function actionHate()
    {
        $likeForm = new ResourceLikeForm();
        if ($likeForm->load(Yii::$app->getRequest()->post(), '') && $likeForm->like(ResourceLike::STATUS_HATE)) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $likeForm->getFirstErrors())];
    }


    public function actionFav()
    {
        $favForm = new ResourceFavoriteForm();
        if ($favForm->load(Yii::$app->getRequest()->post(), '') && $favForm->fav()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $favForm->getFirstErrors())];
    }

    public function actionReport()
    {
        $reportForm = new ResourceReportForm();
        if ($reportForm->load(Yii::$app->getRequest()->post(), '') && $reportForm->report()) {
            return ["status"=>0, "message"=>""];
        }
        return ["status"=>1, "message"=>implode(",", $reportForm->getFirstErrors())];
    }



    public function actionIndex()
    {

		$request = Yii::$app->request;
		$type = $request->get('type', 0);


		$queryBuilder = Resource::find()
            ->leftJoin('random_cache', '`random_cache`.`resource_id` = `resource`.`id`')
            ->where([
                'status'=>Resource::STATUS_ACTIVE,
                '`random_cache`.`category`'=>DataController::CATEGORY_INDEX + $type * 100,
            ])
            ->orderBy('`random_cache`.`index` asc');

        return new ActiveDataProvider([
            'query' => $queryBuilder 
        ]);
    }

    public function actionView($sid)
    {
        return Resource::findOne(QsEncodeHelper::getSid($sid));
    }
}

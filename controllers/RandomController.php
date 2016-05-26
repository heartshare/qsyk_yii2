<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/26
 * Time: 18:27
 */

namespace app\controllers;


use app\commands\DataController;
use app\components\QsEncodeHelper;
use app\models\RandomCache;
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

class RandomController  extends Controller
{
    public function actionIndex()
    {

//        $request = Yii::$app->request;
//        $type = $request->get('type', 0);
//        $dynamic = $request->get('dynamic', 0);
//        $queryBuilder = Resource::find();
//        $where = ['status'=>Resource::STATUS_ACTIVE];
//        if ($type) {
//            $where['type'] = $type;
//        }
//        if ($type == Resource::TYPE_IMAGE) {
//            $where['resource_relation.dynamic'] = $dynamic;
//        }

//        $queryBuilder = $queryBuilder
//            ->leftJoin('resource_relation', '`resource_relation`.`resource_id` = `resource`.`id`')
//            ->where($where)
//            ->orderBy("pub_time desc");

        return new ActiveDataProvider([
            'query' => RandomCache::find()
            ->where(['category'=>DataController::CATEGORY_INDEX])
            ->orderBy(['index'=>SORT_ASC])
        ]);
    }

    public function actionView($id)
    {
        return Resource::findOne($id);
    }
}
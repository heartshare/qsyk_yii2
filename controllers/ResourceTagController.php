<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/24
 * Time: 15:13
 */

namespace app\controllers;

use app\components\QsEncodeHelper;
use app\models\Resource;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;

class ResourceTagController extends Controller
{
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $tag = $request->get('tag', '');
        $queryBuilder = Resource::find();
        $queryBuilder = $queryBuilder
            ->rightJoin('tag_rel', '`tag_rel`.`resource_id` = `resource`.`id`')
            ->where([
                'status'=>Resource::STATUS_ACTIVE,
                'tag_rel.tag_id'=>QsEncodeHelper::getSid($tag),
            ])
            ->andWhere(['>','userid',0])
            ->orderBy('pub_time desc');

        return new ActiveDataProvider([
            'query' => $queryBuilder
        ]);
    }

    public function actionView($id)
    {
        return Resource::findOne($id);
    }

}
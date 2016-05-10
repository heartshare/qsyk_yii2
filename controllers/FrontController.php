<?php
/**
 * Created by PhpStorm.
 * User: cx
 * Date: 2016/5/9
 * Time: 18:19
 */

namespace app\controllers;


use app\models\Resource;
use Yii;
use yii\web\Controller;

class FrontController extends Controller
{
    public function actionIndex() {
        $id = yii::$app->request->get('id');
        $resource = Resource::findOne($id);
        return $this->render('index.tpl', [
            'resource' => $resource,
            'username' => 'Alex',
        ]);
    }
}
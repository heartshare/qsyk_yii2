<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PushNotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Push Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="push-notification-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Push Notification', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => '设备类型',
                'attribute' => 'device_type',
                'value' => function ($model) {
                    return $model->deviceDesc;
                },
            ],
            'android_content',
            'ios_content',
            'android_title',
            [
                'label' => '资源',
                'attribute' => 'resource_id',
                'value' => function ($model) {
                    return '<a href="'. \yii\helpers\Url::base(true). '/resources/' . $model->resource_id.'">'.$model->resource_id.'</a>';
                },
                'format'=>'html'
            ],
            [
                'label' => '状态',
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusDesc;
                },
            ],
//             'status',
             'create_time',
            // 'creator',
             'jpush_msg_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

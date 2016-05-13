<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PushNotification */

$this->title = 'Create Push Notification';
$this->params['breadcrumbs'][] = ['label' => 'Push Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="push-notification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

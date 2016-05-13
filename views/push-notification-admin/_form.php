<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PushNotification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="push-notification-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'device_type')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'android_content')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ios_content')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'android_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'resource_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'creator')->textInput() ?>

    <?= $form->field($model, 'jpush_msg_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

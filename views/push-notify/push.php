<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$resourceNameArr = (new \yii\db\Query())
    ->select(['id AS value', 'desc AS label'])
    ->from('resource')
    ->where(['status' => \app\models\Resource::STATUS_ACTIVE])
    ->orderBy('id desc')
    ->limit(100)
    ->all();
foreach ($resourceNameArr as &$resourceName) {
    $resourceName['label'] = $resourceName['value'] . '_' . $resourceName['label'];
}

$this->title = '推送';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

<!--    <p>推送设置:</p>-->

    <?php $form = ActiveForm::begin([
        'id' => 'push-notify-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    <?= $form->errorSummary($model); ?>
        <?= $form->field($model, 'androidTitle')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'iosDesc')->textarea() ?>

        <?= $form->field($model, 'androidDesc')->textarea() ?>

        <?= $form->field($model, 'resourceId')->widget(\yii\jui\AutoComplete::classname(), [
            'clientOptions' => [
                'source' => $resourceNameArr,
//                'select' => 'function(event, ui) { alert(ui.item.idx); }'
            ],
            'clientEvents' => [
                'select' => 'function (event, ui) { console.log(event);console.log(ui);}'
            ],
            'options'=> ['class'=>'form-control'],
        ]) ?>

        <?= $form->field($model, 'deviceType')->radioList(
            [
                '全部',
                'android',
                'ios',
            ]
        )?>



        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('推送', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

<!--    <div class="col-lg-offset-1" style="color:#999;">-->
<!--        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>-->
<!--        To modify the username/password, please check out the code <code>app\models\User::$users</code>.-->
<!--    </div>-->
</div>

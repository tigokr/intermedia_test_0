<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use kartik\widgets\ActiveForm;
/* @var $this yii\web\View */
$this->title = \Yii::$app->name;

?>
<div class="site-index">
    <div class="page-header">
        <h1>Хотите отправить письмо?</h1>
    </div>

    <?php
    if($flash = \Yii::$app->session->getFlash('success'))
        echo \kartik\widgets\Alert::widget([
            'options' => [ 'class' => 'alert-info' ], 'body' => $flash,
        ]);
    ?>

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'recipient')->textInput() ?>

    <hr />

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'region')->widget(Select2::className(), [
        'data'=>ArrayHelper::map(\app\models\Region::find()->all(), 'id', 'title'),
        'options'=>['id'=>'region_id', 'placeholder'=>'Выберите регион...']
    ]); ?>

    <?= $form->field($model, 'city')->widget(DepDrop::classname(), [
        'data'=>[''=>''],
        'options'=>['id'=>'city_id'],
        'type' => DepDrop::TYPE_SELECT2,
        'pluginOptions'=>[
            'depends'=>['region_id'],
            'placeholder'=>'Выберите город или населённый пункт...',
            'url'=>Url::to(['/site/cities']),
            'initialize' => true
        ]
    ]); ?>

    <?php /* =$form->field($model, 'city')->widget(Select2::className(), [
        'data'=>ArrayHelper::map(\app\models\City::find()->all(), 'id', 'title'),
        'options'=>['placeholder'=>'Ваш город...']
    ]) */ ?>

    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
        'mask'=>'(999) 999-9999',
    ]) ?>

    <?= $form->field($model, 'text')->textarea(['rows'=>5]); ?>

    <?= $form->field($model, 'file')->fileInput(['accept'=>'image/*;capture=camera']) ?>

    <?= $form->field($model, 'verifyCode')->widget(yii\captcha\Captcha::className(), [
        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
    ])->label(\Yii::t('app', 'verifyCode')) ?>

    <hr />

    <div class="form-group text-right">
        <?= Html::submitButton(\Yii::t('app', 'Send'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

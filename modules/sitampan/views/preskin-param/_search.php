<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinParamSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="preskin-param-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'cari-form', 
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2 mb-3']], 
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'menit_pres') ?>

    <?= $form->field($model, 'menit_kin') ?>

    <?= $form->field($model, 'batas_pres') ?>

    <?= $form->field($model, 'batas_kin1') ?>

    <?php // echo $form->field($model, 'batas_kin2') ?>

    <?php // echo $form->field($model, 'batas_kin_nilai') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwAkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siasn-temp-rw-ak-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'cari-form', 
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2 mb-3']], 
    ]); ?>

    <?= $form->field($model, 'bulanMulaiPenailan') ?>

    <?= $form->field($model, 'bulanSelesaiPenailan') ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'isAngkaKreditPertama') ?>

    <?= $form->field($model, 'isIntegrasi') ?>

    <?php // echo $form->field($model, 'isKonversi') ?>

    <?php // echo $form->field($model, 'kreditBaruTotal') ?>

    <?php // echo $form->field($model, 'kreditPenunjangBaru') ?>

    <?php // echo $form->field($model, 'kreditUtamaBaru') ?>

    <?php // echo $form->field($model, 'nomorSk') ?>

    <?php // echo $form->field($model, 'pnsId') ?>

    <?php // echo $form->field($model, 'rwJabatanId') ?>

    <?php // echo $form->field($model, 'tahunMulaiPenailan') ?>

    <?php // echo $form->field($model, 'tahunSelesaiPenailan') ?>

    <?php // echo $form->field($model, 'tanggalSk') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <?php // echo $form->field($model, 'by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

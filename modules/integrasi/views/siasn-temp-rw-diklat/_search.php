<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwDiklatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siasn-temp-rw-diklat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'cari-form', 
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2 mb-3']], 
    ]); ?>

    <?= $form->field($model, 'bobot') ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'institusiPenyelenggara') ?>

    <?= $form->field($model, 'jenisKompetensi') ?>

    <?= $form->field($model, 'jumlahJam') ?>

    <?php // echo $form->field($model, 'latihanStrukturalId') ?>

    <?php // echo $form->field($model, 'nomor') ?>

    <?php // echo $form->field($model, 'pnsOrangId') ?>

    <?php // echo $form->field($model, 'tahun') ?>

    <?php // echo $form->field($model, 'tanggal') ?>

    <?php // echo $form->field($model, 'tanggalSelesai') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <?php // echo $form->field($model, 'by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

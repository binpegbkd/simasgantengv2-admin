<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\integrasi\models\SiasnTempRwKursusSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="siasn-temp-rw-kursus-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'instansiId') ?>

    <?= $form->field($model, 'institusiPenyelenggara') ?>

    <?= $form->field($model, 'jenisDiklatId') ?>

    <?= $form->field($model, 'jenisKursus') ?>

    <?php // echo $form->field($model, 'jenisKursusSertipikat') ?>

    <?php // echo $form->field($model, 'jumlahJam') ?>

    <?php // echo $form->field($model, 'lokasiId') ?>

    <?php // echo $form->field($model, 'namaKursus') ?>

    <?php // echo $form->field($model, 'nomorSertipikat') ?>

    <?php // echo $form->field($model, 'pnsOrangId') ?>

    <?php // echo $form->field($model, 'tahunKursus') ?>

    <?php // echo $form->field($model, 'tanggalKursus') ?>

    <?php // echo $form->field($model, 'tanggalSelesaiKursus') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\integrasi\models\SiasnTempRwKursus $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="siasn-temp-rw-kursus-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'instansiId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'institusiPenyelenggara')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenisDiklatId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenisKursus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenisKursusSertipikat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlahJam')->textInput() ?>

    <?= $form->field($model, 'lokasiId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'namaKursus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomorSertipikat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pnsOrangId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahunKursus')->textInput() ?>

    <?= $form->field($model, 'tanggalKursus')->textInput() ?>

    <?= $form->field($model, 'tanggalSelesaiKursus')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

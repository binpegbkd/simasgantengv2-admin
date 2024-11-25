<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\number\NumberControl;
//use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempDatautama */
/* @var $form yii\widgets\ActiveForm */

$dispOptions = ['class' => 'form-control kv-monospace']; 
$saveOptions = [
    'type' => 'hidden', 
    'readonly' => true, 
    'tabindex' => 1000
];
 
$saveCont = ['class' => 'kv-saved-cont'];
?>

<div class="siasn-temp-datautama-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'agama_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alamat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email_gov')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kabupaten_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'karis_karsu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kelas_jabatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kpkn_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lokasi_kerja_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomor_bpjs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomor_hp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomor_telpon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'npwp_nomor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'npwp_tanggal')->widget(DateControl::classname(), [
                'type'=>DateControl::FORMAT_DATE,
            ]) ?>

    <?= $form->field($model, 'pns_orang_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_taspen')->widget(DateControl::classname(), [
                'type'=>DateControl::FORMAT_DATE,
            ]) ?>

    <?= $form->field($model, 'tapera_nomor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'taspen_nomor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'flag')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'by')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\number\NumberControl;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwJabatan */
/* @var $form yii\widgets\ActiveForm */

$dispOptions = ['class' => 'form-control kv-monospace']; 
$saveOptions = [
    'type' => 'hidden', 
    'readonly' => true, 
    'tabindex' => 1000
];
 
$saveCont = ['class' => 'kv-saved-cont'];
?>

<div class="siasn-temp-rw-jabatan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'eselonId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'instansiId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jabatanFungsionalId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jabatanFungsionalUmumId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenisJabatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenisMutasiId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenisPenugasanId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomorSk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pnsId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'satuanKerjaId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subJabatanId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggalSk')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE,
    ]); ?>

    <?= $form->field($model, 'tmtJabatan')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE,
    ]); ?>

    <?= $form->field($model, 'tmtMutasi')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE,
    ]); ?>

    <?= $form->field($model, 'tmtPelantikan')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE,
    ]); ?>

    <?= $form->field($model, 'unorId')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

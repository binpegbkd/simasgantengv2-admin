<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\number\NumberControl;
//use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwAk */
/* @var $form yii\widgets\ActiveForm */

$dispOptions = ['class' => 'form-control kv-monospace']; 
$saveOptions = [
    'type' => 'hidden', 
    'readonly' => true, 
    'tabindex' => 1000
];
 
$saveCont = ['class' => 'kv-saved-cont'];
?>

<div class="siasn-temp-rw-ak-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bulanMulaiPenailan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bulanSelesaiPenailan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isAngkaKreditPertama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isIntegrasi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isKonversi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kreditBaruTotal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kreditPenunjangBaru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kreditUtamaBaru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomorSk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pnsId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rwJabatanId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahunMulaiPenailan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahunSelesaiPenailan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggalSk')->widget(DateControl::classname(), [
                'type'=>DateControl::FORMAT_DATE,
            ]) ?>

    <?= $form->field($model, 'flag')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'by')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

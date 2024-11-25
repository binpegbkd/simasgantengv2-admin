<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\number\NumberControl;
//use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwHukdis */
/* @var $form yii\widgets\ActiveForm */

$dispOptions = ['class' => 'form-control kv-monospace']; 
$saveOptions = [
    'type' => 'hidden', 
    'readonly' => true, 
    'tabindex' => 1000
];
 
$saveCont = ['class' => 'kv-saved-cont'];
?>

<div class="siasn-temp-rw-hukdis-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'akhirHukumanTanggal')->widget(DateControl::classname(), [
                'type'=>DateControl::FORMAT_DATE,
            ]) ?>

    <?= $form->field($model, 'alasanHukumanDisiplinId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'golonganId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'golonganLama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hukdisYangDiberhentikanId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hukumanTanggal')->widget(DateControl::classname(), [
                'type'=>DateControl::FORMAT_DATE,
            ]) ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenisHukumanId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenisTingkatHukumanId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kedudukanHukumId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keterangan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'masaBulan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'masaTahun')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomorPp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pnsOrangId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'skNomor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'skPembatalanNomor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'skPembatalanTanggal')->widget(DateControl::classname(), [
                'type'=>DateControl::FORMAT_DATE,
            ]) ?>

    <?= $form->field($model, 'skTanggal')->widget(DateControl::classname(), [
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

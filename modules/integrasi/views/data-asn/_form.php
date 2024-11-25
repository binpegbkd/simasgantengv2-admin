<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinAsn */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="preskin-asn-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-asn', 
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

    <?= $form->field($model, 'nip')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'idpns')->textInput(['disabled' => true, 'value' => $model['fipNama']]) ?>

    <?= $form->field($model, 'status')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($sta, 'KDSTAPEG', 'NMSTAPEG'),
            'options' => ['placeholder' => 'Pilih ....'],
            'pluginOptions' => ['allowClear' => true],
    ]);?>

    <?= $form->field($model, 'kode_kelas_jab')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($kelas, 'id', 'kelas'),
            'options' => ['placeholder' => 'Pilih ....'],
            'pluginOptions' => ['allowClear' => true],
    ]);?>

    <?= $form->field($model, 'kode_jadwal')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($jad, 'id', 'jenis'),
            'options' => ['placeholder' => 'Pilih ....'],
            'pluginOptions' => ['allowClear' => true],
    ]);?>

    <?php //echo $form->field($model, 'pres')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'kin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tpp')->widget(Select2::classname(), [
            'data' => [0 => 'Tidak Dapat TPP', 1 => 'Dapat TPP'],
            'options' => ['placeholder' => 'Pilih ....'],
            'pluginOptions' => ['allowClear' => true],
    ]);?>

    <?= $form->field($model, 'tmt_stop')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE,
    ]) ?>

    <?php //echo $form->field($model, 'updated')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

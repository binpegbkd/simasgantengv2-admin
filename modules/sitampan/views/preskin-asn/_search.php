<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinAsnSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="preskin-asn-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'cari-form', 
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

    <?= $form->field($model, 'nip') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'kode_jadwal')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($hk, "id", "jenis"),
        'options' => ['placeholder' => 'Pilih ....'],
        'pluginOptions' => ['allowClear' => true],
    ])->label('Hari Kerja');?>

    <?= $form->field($model, 'opd')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($opd, "KOLOK", "UNOR"),
        'options' => ['placeholder' => 'Pilih ....'],
        'pluginOptions' => ['allowClear' => true],
    ])->label('UNOR');?>

    <?= $form->field($model, 'status')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($sta, "KDSTAPEG", "STAPEG"),
            'options' => ['placeholder' => 'Pilih ....'],
            'pluginOptions' => ['allowClear' => true],
    ]);?>

    <?= $form->field($model, 'tmt_stop')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE
    ]);?>

    <?php // echo $form->field($model, 'kode_kelas_jab') ?>

    <?php // echo $form->field($model, 'kode_jadwal') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'idpns') ?>

    <?php // echo $form->field($model, 'pres') ?>

    <?php // echo $form->field($model, 'kin') ?>

    <?php // echo $form->field($model, 'tpp') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary mr-1']) ?>
        <?= Html::a('Clear', ['index'], ['class' => 'btn btn-danger', 'title' => 'Clear']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

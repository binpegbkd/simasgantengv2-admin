<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

$this->title = 'Post Data Pribadi';
$this->params['breadcrumbs'][] = $this->title;

$agama = [
    1 => 'Islam',
    2 => 'Kristen',
    3 => 'Katholik',
    4 => 'Hindu',
    5 => 'Budha',
    6 => 'Konghucu',
    7 => 'Lainnya',
];
?>

<div class="siasn-temp-rw-jabatan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'agama_id')->widget(Select2::classname(), [
        'data' => $agama,
        'language' => 'id',
        'options' => [
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]) ?>

    <?= $form->field($model, 'alamat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email_gov')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'karis_karsu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kelas_jabatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomor_bpjs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomor_hp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomor_telpon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'npwp_nomor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'npwp_tanggal')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>

    <?= $form->field($model, 'tanggal_taspen')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>
    
    <?= $form->field($model, 'taspen_nomor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tapera_nomor')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'nik')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'nomor_kk')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

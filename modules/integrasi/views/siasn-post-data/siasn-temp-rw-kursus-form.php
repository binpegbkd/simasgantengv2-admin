<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

$this->title = 'Post Riwayat Penghargaan';
$this->params['breadcrumbs'][] = $this->title;

$jdik = \app\modules\integrasi\models\SiasnRefJenisDiklat::find()->all();

?>

<div class="siasn-temp-rw-kursus-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'institusiPenyelenggara')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenisDiklatId')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($jdik, 'id', 'jenis_diklat'),
        'language' => 'id',
        'options' => [
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);?>

    <?php //echo $form->field($model, 'jenisKursus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlahJam')->textInput() ?>

    <?= $form->field($model, 'namaKursus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomorSertipikat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahunKursus')->textInput() ?>

    <?= $form->field($model, 'tanggalKursus')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>

    <?= $form->field($model, 'tanggalSelesaiKursus')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

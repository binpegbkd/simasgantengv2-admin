<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

$this->title = 'Post Riwayat Diklat';
$this->params['breadcrumbs'][] = $this->title;

$jkom = \app\modules\integrasi\models\SiasnRefJenisKompetensi::find()->all();

$stru = [
    1	=> "SEPADA",
    2	=> "SEPALA/ADUM/DIKLAT PIM TK.IV",
    3	=> "SEPADYA/SPAMA/DIKLAT PIM TK. III",
    4	=> "SPAMEN/SESPA/SESPANAS/DIKLAT PIM TK. II",
    5	=> "SEPATI/DIKLAT PIM TK. I",
    6	=> "SESPIM",
    7	=> "SESPATI",
    8	=> "Diklat Struktural Lainnya",
];
?>

<div class="siasn-temp-rw-diklat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bobot')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'institusiPenyelenggara')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'latihanStrukturalId')->widget(Select2::classname(), [
        'data' => $stru,
        'language' => 'id',
        'options' => [
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);?>

    <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jenisKompetensi')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($jkom, 'id', 'nama'),
        'language' => 'id',
        'options' => [
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);?>

    <?= $form->field($model, 'nomor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlahJam')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>

    <?= $form->field($model, 'tanggalSelesai')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>


    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

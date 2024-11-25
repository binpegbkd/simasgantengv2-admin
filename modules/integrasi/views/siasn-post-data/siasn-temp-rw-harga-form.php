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

$harga =[
    100	=> "BINTANG",
    101	=> "R.I  ADIPURNA",
    102	=> "R.I  ADIPRADANA",
    103	=> "R.I  UTAMA",
    104	=> "R.I  PRATAMA",
    105	=> "R.I  NARARYA",
    106	=> "MAHAPUTERA ADIPURNA",
    107	=> "MAHAPUTERA ADIPRADANA",
    108	=> "MAHAPUTERA UTAMA",
    109	=> "MAHAPUTERA PRATAMA",
    110	=> "MAHAPUTERA NARARYA",
    111	=> "TANDA PENGHARGAAN TRIKORA",
    112	=> "TP PEMBEBASAN IRIAN BARAT",
    201	=> "KARYA SATYA 10 TAHUN",
    202	=> "KARYA SATYA  20 TAHUN",
    203	=> "KARYA SATYA  30 TAHUN",
    204	=> "WIRA KARYA",
    300	=> "TANDA JASA LAINNYA",
    301	=> "TP  SIDHAKARYA ADHYAKSA",
    302	=> "TP  PURNABAKTI ADHYAKSA",
    303	=> "TP  DHARMA ADHYAKSA",
    304	=> "TP TELADAN  TK NASIONAL",
    305	=> "TP TELADAN  TK PROPINSI",
    306	=> "TP TELADAN TK KABUPATEN/KOTA",
    307	=> "TP PNS LUAR BIASA PRESTASINYA",
    308	=> "TP PENEMUAN BERMANFAAT BAGI NEGARA",
    309	=> "SISWA TELADAN",
    310	=> "OLIMPIADE",
    400	=> "TANDA PENGHARGAAN LAINNYA",
];

?>

<div class="siasn-temp-rw-harga-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hargaId')->widget(Select2::classname(), [
        'data' => $harga,
        'language' => 'id',
        'options' => [
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'skDate')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>

    <?= $form->field($model, 'skNomor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

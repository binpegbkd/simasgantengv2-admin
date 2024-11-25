<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

$this->title = 'Post Riwayat Jabatan';
$this->params['breadcrumbs'][] = $this->title;

$jmut = [
    'MU' => 'Mutasi Unor',
    'MJ' => 'Mutasi Jabatan',
];

$jjab = [
    1 => 'Struktural',
    2 => 'Fungsional',
    4 => 'Pelaksana',
];

$esel = [
    11 => 'I.a',
    12 => 'I.b',
    21 => 'II.a',
    22 => 'II.b',
    31 => 'III.a',
    32 => 'III.b',
    41 => 'IV.a',
    42 => 'IV.b',
];

$tugas = [
    'D' => 'Definitif',
    'TT' => 'Tugas tambahan',
    'PLT' => 'Pelaksana Tugas/ Plt',
    'PLH' => 'Pelaksana Harian/ Plh',
    'PJ' => 'Pejabat Non Definitif',
];

$unor = \app\modules\integrasi\models\SiasnRefUnor::find()
->select(['*', "CONCAT(simpeg,' ',\"namaUnor\",' - ',\"eselonId\") AS unor"])->asArray()
->where(['aktif' => 'A'])
->orderBy(['simpeg' => SORT_ASC])
->all();

$jf = \app\modules\integrasi\models\SiasnRefFungsional::find()
->all();

$jfu = \app\modules\integrasi\models\SiasnRefPelaksana::find()
->all();

$subjab = \app\modules\integrasi\models\SiasnRefSubjab::find()
->all();
?>

<div class="siasn-temp-rw-jabatan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jenisMutasiId')->widget(Select2::classname(), [
        'data' => $jmut,
        'language' => 'id',
        'options' => [
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);?>
    
    <?= $form->field($model, 'jenisJabatan')->widget(Select2::classname(), [
        'data' => $jjab,
        'language' => 'id',
        'options' => [
            'id' => 'jjab',
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);?>

    <?= $form->field($model, 'unorId')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($unor, 'id', 'unor'),
        'language' => 'id',
        'options' => [
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);?>

    <?= $form->field($model, 'eselonId')->textInput(['id' => 'eselid', 'maxlength' => true]) ?>

    <?= $form->field($model, 'jabatanFungsionalId')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($jf, 'id', 'nama'),
        'language' => 'id',
        'options' => [
            'id' => 'jf',
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'jabatanFungsionalUmumId')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($jfu, 'id', 'nama'),
        'language' => 'id',
        'options' => [
            'id' => 'jfu',
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'jenisPenugasanId')->widget(Select2::classname(), [
        'data' => $tugas,
        'language' => 'id',
        'options' => [
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);?>

    <?= $form->field($model, 'subJabatanId')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($subjab, 'id', 'nama'),
        'language' => 'id',
        'options' => [
            'id' => 'sjab',
            'placeholder' => '- Pilih -',  
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'nomorSk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggalSk')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>

    <?= $form->field($model, 'tmtJabatan')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>

    <?= $form->field($model, 'tmtMutasi')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>

    <?= $form->field($model, 'tmtPelantikan')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Pilih Tanggal'],
        'pluginOptions' => ['autoclose' => true, 'format' => 'dd-mm-yyyy']
    ]); ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
// $script = <<< JS

// $('#jjab').change(function(){
// 	var jj = $('#jjab').val();
//     if(jj == 1) $('#eselid').val();
// });
// JS;
// $this->registerJs($script);
?>
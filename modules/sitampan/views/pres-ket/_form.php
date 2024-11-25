<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\MasKet */
/* @var $form yii\widgets\ActiveForm */

$dispOptions = ['class' => 'form-control kv-monospace']; 
$saveOptions = [
    'type' => 'hidden', 
    'readonly' => true, 
    'tabindex' => 1000
];
 
$saveCont = ['class' => 'kv-saved-cont'];
//echo json_encode($asn->all());
?>

<div class="mas-ket-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jenis_ket')->widget(Select2::classname(), [
                'data' => ArrayHelper::map($jenis,'id','jenis_ket'),
                'language' => 'id',
                'options' => ['placeholder' => '-Pilih-', 'id' => 'jket'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>

    <?= $form->field($model, 'no_surat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_surat')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE,
    ]) ?>

    <?= $form->field($model, 'detail')->textArea(['cols' => 1]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'tgl_awal')->widget(DateControl::classname(), [
                'type'=>DateControl::FORMAT_DATE,
                'options' => ['id' => 'tglawal'],
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        // 'endDate' => "+31d",
                        // 'startDate' => "-33d",
                    ]
                ]
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'tgl_akhir')->widget(DateControl::classname(), [
                'type'=>DateControl::FORMAT_DATE,
                'options' => ['id' => 'tglakhir'],
            ]) ?>
        </div>
    </div>

    <div id="asn">   
        <?= $form->field($model, 'nip')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($asn->all(),'B_02','nipnama'),
            'options' => [
                'placeholder' => 'Pilih ASN . . . .', 
                'autocomplete' => 'off', 
                'multiple' => true,
                //'id' => 'asn',
            ],
            //'size' => Select2::MEDIUM,
            'theme' => Select2::THEME_BOOTSTRAP,
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label('Pegawai') ?>
    </div>

    <?= $form->field($model, 'opd', ['showLabels' => false])->hiddenInput() ?>
    <?= $form->field($model, 'flag', ['showLabels' => false])->hiddenInput(['value' => 0]) ?>

    <div id="pesan" align="center"></div>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success', 'id' => 'simpan']) ?>
        <?php // Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
        <?= Html::a('<i class=\"fas fa-minus-circle\"></i> Batal', Url::previous(), ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$urlData = Url::to(['/kinerja/tab-presensi/get-jadwal']);
$script = <<< JS
// untuk listrik padam
$(document).ready(function(){
	var jek = $('#jkets').val();
    if(jek == 3) $("#asn").hide();
    else $("#asn").show();

    $("#pesan").hide();
});

$('#jket').change(function(){	
    var jek = $('#jkets').val();
    if(jek == 3) $("#asn").hide();
    else $("#asn").show();
});

JS;
$this->registerJs($script);
?>

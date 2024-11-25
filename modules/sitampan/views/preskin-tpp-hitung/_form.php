<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\number\NumberControl;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinTppHitung */
/* @var $form yii\widgets\ActiveForm */

$dispOptions = ['class' => 'form-control kv-monospace']; 
$saveOptions = [
    'type' => 'hidden', 
    'readonly' => true, 
    'tabindex' => 1000
];
 
$saveCont = ['class' => 'kv-saved-cont'];
?>

<div class="preskin-tpp-hitung-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-horizontal', 
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

    <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

    <?=$form->field($model, 'bulan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nip',[
        'addon' => [
            'append' => [
                'content' => Html::button('Cari', ['class'=>'btn btn-primary', 'id' => 'nip_asn']), 
                'asButton' => true
            ],
        ],
    ])->textInput(['id' => 'nip']); ?>

    <?= $form->field($model, 'nama')->textInput(['id' => 'nama_asn', 'readonly' => true]); ?>
            
    <?= $form->field($model, 'nama_unor')->textArea(['cols' => 4, 'id' => 'tablok_asn', 'readonly' => true]); ?>

    <?= $form->field($model, 'persen_tpp')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$urlData = Url::to(['/sitampan/preskin-tpp-hitung/get-asn']);
$script = <<< JS

$(document).ready(function(){
	if($("#nama_asn").val() != '') $("#simpan").show();
	else $("#simpan").hide();	
});

$('#nip_asn').click(function(){
	var zipId = $('#nip').val();
    
	$.get("{$urlData}",{ zipId : zipId },function(data){
		if (data == '' ){

            Swal.fire({
                icon: 'error',
                title: 'Gagal !!!',
                text: "Data dengan NIP "+ zipId +" tidak ditemukan !",
                showConfirmButton: false,
                timer: 2000
            })

            $("#simpan").hide();

			$('#nama_asn').attr('value','');
			$('#tablok_asn').attr('value','');

		}else{
            $("#simpan").show();

            var data = $.parseJSON(data);
			$('#nama_asn').attr('value',data.namaAtasan);
			$('#tablok_asn').attr('placeholder',data.tablokAtasan);
		}
	});
});
JS;
$this->registerJs($script);
?>

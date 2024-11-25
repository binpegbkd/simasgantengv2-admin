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

    <?php 
        if($model->isNewRecord) { 
            echo $form->field($model, 'nip', [
                'addon' => [
                    'append' => [
                        'content' => Html::button('Cari', ['class'=>'btn btn-primary', 'id' => 'nip_asn']), 
                        'asButton' => true
                    ],
                ],
            ])->textInput(['id' => 'nip']);

            echo$form->field($model, 'idpns')->textInput(['disabled' => true, 'id' => 'nama_asn'])->label('Nama');
            
            echo $form->field($model, 'idpns')->textArea(['cols' => 4, 'id' => 'tablok_asn', 'disabled' => true])->label('Unor');

        }else{
            echo $form->field($model, 'nip')->textInput(['readonly' => true]);

            echo$form->field($model, 'idpns')->textInput(['disabled' => true, 'value' => $model['fipNama']])->label('Nama');
            
            echo $form->field($model, 'idpns')->textArea(['cols' => 4, 'value' => $model['fipUnor'], 'disabled' => true])->label('Unor');
        }
    ?>

    <?= $form->field($model, 'kode_jadwal')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($jad, 'id', 'jenis'),
            'options' => ['placeholder' => 'Pilih ....'],
            'pluginOptions' => ['allowClear' => true],
    ]);?>

    <?= $form->field($model, 'idpns', ['showLabels' => false])->hiddenInput() ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$urlData = Url::to(['/sitampan/kesra/get-asn']);
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

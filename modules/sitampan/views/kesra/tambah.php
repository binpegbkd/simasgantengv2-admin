<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\FpTbDaftarfingerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fp-tb-daftarfinger-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

    <?= $form->field($model, 'nip', [
		'addon' => [
			'append' => [
				'content' => Html::button('Cari', ['class'=>'btn btn-primary', 'id' => 'nip_asn']), 
				'asButton' => true
			],
		],
    ])->textInput(['id' => 'nip']) ?>

    <?= $form->field($model, 'nama')->textInput(['id' => 'nama_asn', 'readonly' => true]) ?>

    <?= $form->field($model, 'nama')->textArea(['cols' => 4, 'id' => 'tablok_asn', 'disabled' => true])->label('Unor') ?>

    <?= $form->field($model, 'kodealamat')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($lokasi, 'kodealamat', 'alamat'),
            'options' => ['placeholder' => 'Pilih ....'],
            'pluginOptions' => ['allowClear' => true],
    ]);?>

    <?php // echo $form->field($model, 'password') ?>
    
    <?php // echo $form->field($model, 'waktu') ?>

    <?php // echo $form->field($model, 'device') ?>

    <div class="form-group">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success', 'id' => 'simpan']) ?>
        <?= Html::a('Clear', ['reset'], ['class' => 'btn btn-danger', 'title' => 'Clear']) ?>
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
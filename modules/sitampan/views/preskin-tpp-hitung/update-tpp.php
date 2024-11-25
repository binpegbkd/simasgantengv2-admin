<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

$aksi =  Yii::$app->controller->action->id; 

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinTppHitung */

$this->title = 'Update Data TPP';
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preskin-tpp-hitung-update">

<?php $form = ActiveForm::begin([
        'id' => 'form-horizontal', 
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'action' => [Yii::$app->controller->action->id, 'id' => $model['id']],
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

    <?= $form->field($model, 'tahun')->textInput(['maxlength' => true, 'disabled' => true, 'value' => $model['bulan'].' - '.$model['tahun']])->label('Periode') ?>

    <?= $form->field($model, 'nip')->textInput(['maxlength' => true, 'disabled' => true]); ?>
    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]); ?>

    <?php
        if($aksi == 'jabatan'){
            echo $form->field($model, 'jenis_jab')->textInput(['maxlength' => true]);
            echo $form->field($model, 'kode_jab')->textInput(['maxlength' => true]);
            echo $form->field($model, 'nama_jab')->textInput(['maxlength' => true]);
        }

        if($aksi == 'kelas'){
            echo $form->field($model, 'kelas_jab_tpp')->textInput(['maxlength' => true]);
        }

        if($aksi == 'unor'){
            echo $form->field($model, 'tablok')->textInput(['maxlength' => true]);
            echo $form->field($model, 'nama_opd')->textInput(['maxlength' => true]);
            echo $form->field($model, 'tablokb')->textInput(['maxlength' => true]);
            echo $form->field($model, 'nama_unor')->textInput(['maxlength' => true]);
        }

        if($aksi == 'golongan'){
            echo $form->field($model, 'gol')->textInput(['maxlength' => true]);
        }

        if($aksi == 'cuti'){
            echo $form->field($model, 'cuti')->textInput(['maxlength' => true, 'id' => 'xcuti']);
            echo $form->field($model, 'presensi')->textInput(['maxlength' => true, 'id' => 'xpresensi']);
            echo $form->field($model, 'kinerja')->textInput(['maxlength' => true, 'id' => 'xkin']);
            // echo $form->field($model, 'beban_kerja')->textInput(['maxlength' => true, 'id' => 'xbeban']);
            // echo $form->field($model, 'jml_cuti')->textInput(['maxlength' => true, 'id' => 'jcuti']);
            // echo $form->field($model, 'jml_hrkerja')->textInput(['maxlength' => true, 'id' => 'jkerja']);
        }

        if($aksi == 'hukdis'){
            echo $form->field($model, 'hukdis')->textInput(['maxlength' => true, 'id' => 'xhukdis']);
            //echo $form->field($model, 'tpp_jumlah')->textInput(['maxlength' => true, 'id' => 'xtppjum']);
        }

        if($aksi == 'persen-tpp'){
            echo $form->field($model, 'persen_tpp')->textInput(['maxlength' => true]);
        }
    ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$script = <<< JS
$('#xcuti').change(function(){	
    var cuti = $('#xcuti').val();
    var pres = $('#xpresensi').val();
    var hasil = pres - cuti;
	$('#xpresensi').attr('value',hasil);
});

// $('#xhukdis').change(function(){	
//     var huk = $('#xhukdis').val();
//     var jum = $('#xtppjum').val();
//     var tpp = jum - huk * jum /100;
// 	$('#xtppjum').attr('value',tpp.toFixed(0));
// });

JS;
$this->registerJs($script);
?>

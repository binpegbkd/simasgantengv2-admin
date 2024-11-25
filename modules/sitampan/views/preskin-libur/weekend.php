<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\MasLibur */
/* @var $form yii\widgets\ActiveForm */

if($model['tanggal'] === null) $tahun = date('Y');

?>

<div class="mas-libur-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tanggal')->widget(Select2::classname(), [
        'data' => \app\models\Fungsi::cariTahun(),
        'language' => 'id',
        'options' => [
            'placeholder' => '- Pilih Tahun -',  
            //'autocomplete' => 'off',
            //'id'=>'tahun',
            'value' => $tahun,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])->label('Tahun');?>
    
    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Generate Sabtu Minggu', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

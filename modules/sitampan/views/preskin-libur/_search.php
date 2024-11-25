<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\MasLiburSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mas-libur-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'cari-form', 
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2 mb-3']], 
        'formConfig' => ['labelSpan' => 5, 'deviceSize' => ActiveForm::SIZE_SMALL],
    ]); ?>

    <?= $form->field($model, 'tanggal')->widget(Select2::classname(), [
            'data' => \app\models\Fungsi::cariTahun(),
            'options' => [
                'placeholder' => '- Pilih -',
                'id' => 'tahun',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'width' => '100px',
            ],
    ])->label('Tahun') ?>

    <?php ActiveForm::end(); ?>

</div>


<?php
$script = <<< JS

$('#tahun').change(function(){	
	$('#cari-form').submit();
});

JS;
$this->registerJs($script);
?>

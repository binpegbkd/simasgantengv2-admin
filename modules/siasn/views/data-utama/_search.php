<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;

?>

<div class="tb-daftarfinger-form">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2 me-0 mb-2']],
        'options' => ['style' => 'align-items: flex-start'] 
    ]); ?>

    <?= $form->field($model, 'nipBaru', ['showLabels' => true])->textInput(['placeholder' => 'NIP']) ?>
    <?= $form->field($model, 'nama', ['showLabels' => true])->textInput(['placeholder' => 'NAMA']) ?>
    <?= $form->field($model, 'unorIndukNama', ['showLabels' => true])->textInput(['placeholder' => 'UNOR INDUK']) ?>
    <?= $form->field($model, 'unorNama', ['showLabels' => true])->textInput(['placeholder' => 'UNOR']) ?>

    <div class="form-group mt-2">
        <?=  Html::submitButton('<i class="fas fa-search"></i> Cari Data', ['class' => 'btn btn-primary mr-1']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

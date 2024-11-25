<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;

?>

<div class="tb-daftarfinger-form">
    <?php $form = ActiveForm::begin([
        'action' => ['/detail-data-utama'],
        'type' => ActiveForm::TYPE_INLINE,
    ]); ?>
    
    <?=  Html::submitButton('<i class="fas fa-eye"></i>', ['class' => 'btn btn-link']) ?>
    <?= $form->field($model, 'id', ['showLabels' => false])->hiddenInput() ?>

    <?php ActiveForm::end(); ?>

</div>

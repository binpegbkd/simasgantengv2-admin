<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;

?>

<div class="tb-daftarfinger-form">
    <?php $form = ActiveForm::begin([
        'action' => ['/integrasi/data-asn/view'],
        'type' => ActiveForm::TYPE_INLINE,
    ]); ?>
    
    <?=  Html::submitButton('<i class="fas fa-eye"></i>', ['class' => 'btn btn-link']) ?>
    <?= $form->field($model, 'nip', ['showLabels' => false])->hiddenInput() ?>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\siasn\models\SiasnRefUnorSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="siasn-ref-unor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-0 me-0']],
        'options' => ['style' => 'align-items: flex-start'] 
    ]); ?>

    <?= $form->field($model, 'namaUnor', ['showLabels' => true])->textInput(['placeholder' => 'NAMA', 'size' => '70%']) ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class="fas fa-search"></i> Cari Data', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

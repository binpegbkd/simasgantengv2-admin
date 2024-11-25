<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\siasn\models\SiasnRefUnor $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="siasn-ref-unor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'instansiId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'diatasanId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'eselonId')->textInput() ?>

    <?= $form->field($model, 'namaUnor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'namaJabatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aktif')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

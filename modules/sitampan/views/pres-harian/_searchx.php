<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PresHarianSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pres-harian-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'cari-form', 
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2 mb-3']], 
    ]); ?>

    <?= $form->field($model, 'kode') ?>

    <?= $form->field($model, 'tgl') ?>

    <?= $form->field($model, 'idpns') ?>

    <?= $form->field($model, 'nip') ?>

    <?= $form->field($model, 'nama') ?>

    <?php // echo $form->field($model, 'tablokb') ?>

    <?php // echo $form->field($model, 'jd_masuk') ?>

    <?php // echo $form->field($model, 'jd_pulang') ?>

    <?php // echo $form->field($model, 'pr_masuk') ?>

    <?php // echo $form->field($model, 'pr_pulang') ?>

    <?php // echo $form->field($model, 'mnt_masuk') ?>

    <?php // echo $form->field($model, 'mnt_pulang') ?>

    <?php // echo $form->field($model, 'kd_pr_masuk') ?>

    <?php // echo $form->field($model, 'kd_pr_pulang') ?>

    <?php // echo $form->field($model, 'persen_masuk') ?>

    <?php // echo $form->field($model, 'persen_pulang') ?>

    <?php // echo $form->field($model, 'pot_masuk') ?>

    <?php // echo $form->field($model, 'pot_pulang') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

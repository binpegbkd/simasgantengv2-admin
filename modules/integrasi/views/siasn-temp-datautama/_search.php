<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempDatautamaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siasn-temp-datautama-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'cari-form', 
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2 mb-3']], 
    ]); ?>

    <?= $form->field($model, 'agama_id') ?>

    <?= $form->field($model, 'alamat') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'email_gov') ?>

    <?= $form->field($model, 'kabupaten_id') ?>

    <?php // echo $form->field($model, 'karis_karsu') ?>

    <?php // echo $form->field($model, 'kelas_jabatan') ?>

    <?php // echo $form->field($model, 'kpkn_id') ?>

    <?php // echo $form->field($model, 'lokasi_kerja_id') ?>

    <?php // echo $form->field($model, 'nomor_bpjs') ?>

    <?php // echo $form->field($model, 'nomor_hp') ?>

    <?php // echo $form->field($model, 'nomor_telpon') ?>

    <?php // echo $form->field($model, 'npwp_nomor') ?>

    <?php // echo $form->field($model, 'npwp_tanggal') ?>

    <?php // echo $form->field($model, 'pns_orang_id') ?>

    <?php // echo $form->field($model, 'tanggal_taspen') ?>

    <?php // echo $form->field($model, 'tapera_nomor') ?>

    <?php // echo $form->field($model, 'taspen_nomor') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <?php // echo $form->field($model, 'by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

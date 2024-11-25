<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwHukdisSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siasn-temp-rw-hukdis-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'cari-form', 
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2 mb-3']], 
    ]); ?>

    <?= $form->field($model, 'akhirHukumanTanggal') ?>

    <?= $form->field($model, 'alasanHukumanDisiplinId') ?>

    <?= $form->field($model, 'golonganId') ?>

    <?= $form->field($model, 'golonganLama') ?>

    <?= $form->field($model, 'hukdisYangDiberhentikanId') ?>

    <?php // echo $form->field($model, 'hukumanTanggal') ?>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'jenisHukumanId') ?>

    <?php // echo $form->field($model, 'jenisTingkatHukumanId') ?>

    <?php // echo $form->field($model, 'kedudukanHukumId') ?>

    <?php // echo $form->field($model, 'keterangan') ?>

    <?php // echo $form->field($model, 'masaBulan') ?>

    <?php // echo $form->field($model, 'masaTahun') ?>

    <?php // echo $form->field($model, 'nomorPp') ?>

    <?php // echo $form->field($model, 'pnsOrangId') ?>

    <?php // echo $form->field($model, 'skNomor') ?>

    <?php // echo $form->field($model, 'skPembatalanNomor') ?>

    <?php // echo $form->field($model, 'skPembatalanTanggal') ?>

    <?php // echo $form->field($model, 'skTanggal') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <?php // echo $form->field($model, 'by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

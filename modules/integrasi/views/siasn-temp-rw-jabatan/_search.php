<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwJabatanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="siasn-temp-rw-jabatan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'cari-form', 
        'type' => ActiveForm::TYPE_INLINE,
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2 mb-3']], 
    ]); ?>

    <?= $form->field($model, 'eselonId') ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'instansiId') ?>

    <?= $form->field($model, 'jabatanFungsionalId') ?>

    <?= $form->field($model, 'jabatanFungsionalUmumId') ?>

    <?php // echo $form->field($model, 'jenisJabatan') ?>

    <?php // echo $form->field($model, 'jenisMutasiId') ?>

    <?php // echo $form->field($model, 'jenisPenugasanId') ?>

    <?php // echo $form->field($model, 'nomorSk') ?>

    <?php // echo $form->field($model, 'pnsId') ?>

    <?php // echo $form->field($model, 'satuanKerjaId') ?>

    <?php // echo $form->field($model, 'subJabatanId') ?>

    <?php // echo $form->field($model, 'tanggalSk') ?>

    <?php // echo $form->field($model, 'tmtJabatan') ?>

    <?php // echo $form->field($model, 'tmtMutasi') ?>

    <?php // echo $form->field($model, 'tmtPelantikan') ?>

    <?php // echo $form->field($model, 'unorId') ?>

    <?php // echo $form->field($model, 'flag') ?>

    <?php // echo $form->field($model, 'updated') ?>

    <?php // echo $form->field($model, 'by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

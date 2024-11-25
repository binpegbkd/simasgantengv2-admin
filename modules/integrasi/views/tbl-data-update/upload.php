<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rekom */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Upload Data';
$this->params['breadcrumbs'][] = ['label' => 'Tambah Data ASN', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="rekom-form" align="center">

    <h3><b>Upload Data ASN Baru</b></h3>
    <p>&nbsp;</p>

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
        
    <?= $form->field($model, 'file')->fileInput(['maxlength' => true])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Upload', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

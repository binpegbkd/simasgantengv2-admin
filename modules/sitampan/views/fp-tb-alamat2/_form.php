<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\FpTbAlamat2 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fp-tb-alamat2-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kodealamat')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'alamat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tablokb')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($opd, 'KOLOK', 'UNOR'),
            'options' => ['placeholder' => 'Pilih ....'],
            'pluginOptions' => ['allowClear' => true],
    ]);?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\FpTbAlamat2Search */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fp-tb-alamat2-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'cari-form', 
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]); ?>

    <?= $form->field($model, 'kodealamat') ?>

    <?= $form->field($model, 'alamat') ?>

    <?= $form->field($model, 'tablokb')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($opd, 'KOLOK', 'UNOR'),
            'options' => ['placeholder' => 'Pilih ....'],
            'pluginOptions' => ['allowClear' => true],
    ]);?>

    <?php //echo $form->field($model, 'latitude') ?>

    <?php //echo $form->field($model, 'longitude') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Clear', ['index'], ['class' => 'btn btn-danger', 'title' => 'Clear']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

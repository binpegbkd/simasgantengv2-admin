<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\number\NumberControl;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\MasLibur */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="mas-libur-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php if(Yii::$app->controller->action->id == 'create') {
        echo $form->field($model, 'tanggal')->widget(DateControl::classname(), [
           'type'=>DateControl::FORMAT_DATE,
        ]);
    }else{
        echo $form->field($model, 'tanggal')->widget(DateControl::classname(), [
            'type'=>DateControl::FORMAT_DATE,
            'disabled'=>true,
         ]);
    }?>
            

    <?= $form->field($model, 'ket_libur')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($ket_libur,'id','ket_libur'),
        'language' => 'id',
        'options' => [
            'placeholder' => '- Pilih -',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])->label('Keterangan Libur') ?>

    <?= $form->field($model, 'detail')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

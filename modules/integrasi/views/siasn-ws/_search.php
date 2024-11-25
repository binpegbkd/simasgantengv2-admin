<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\kinerja\KinBulananSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="kin-bulanan-search">

<?php $form = ActiveForm::begin([
        //'action' => ['get-data'],
        'method' => 'post',
        'id' => 'cari-get', 
        'type' => ActiveForm::TYPE_INLINE,
        'options' => ['target'=>'_blank'],
        'fieldConfig' => ['options' => ['class' => 'form-group mr-2 mb-0']] 
    ]); ?>
        
        <?= $form->field($model, 'mode')->widget(Select2::classname(), [
                'data' => ['prod' => 'Production', 'train' => 'Training'],
                'language' => 'id',
                'options' => [
                    'id'=>'mode',
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                    'width' => '200px',
                ],
            ]);?>

        <?= $form->field($model, 'path')->widget(Select2::classname(), [
                'data' => ArrayHelper::map($ws, 'path', 'tampil'),
                'language' => 'id',
                'options' => [
                    'id' => 'path',
                ],
                'pluginOptions' => [
                    'allowClear' => false,
                    'width' => '200px',
                ],
            ]);?>

        <?= $form->field($model, 'nip')->textInput(['maxlength' => 18]) ?>


    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-search\"></i> Cari Data', ['class' => 'btn btn-info']) ?> &nbsp;
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
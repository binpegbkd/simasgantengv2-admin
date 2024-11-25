<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\FpTbDaftarfinger */

$this->title = 'Reset Device Presensi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fp-tb-daftarfinger-create">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kodealamat')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($lok, 'kodealamat', 'alamat'),
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]) ?>

    <div class="form-group">
        <?=  Html::submitButton('<i class=\"fas fa-save\"></i> Simpan', ['class' => 'btn btn-success']) ?>
        <?=  Html::a('<i class=\"fas fa-minus-circle\"></i> Batal',' ', ['class' => 'btn btn-danger', 'data-dismis' => 'modal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

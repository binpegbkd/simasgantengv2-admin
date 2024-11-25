<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinParam */

$this->title = 'Ubah Data Preskin Param: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Preskin Params', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preskin-param-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

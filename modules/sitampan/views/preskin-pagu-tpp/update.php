<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinPaguTpp */

$this->title = 'Ubah Data Preskin Pagu Tpp: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Preskin Pagu Tpps', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preskin-pagu-tpp-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

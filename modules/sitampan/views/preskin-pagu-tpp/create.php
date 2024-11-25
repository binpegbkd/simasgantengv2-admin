<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinPaguTpp */

$this->title = 'Tambah Data Preskin Pagu Tpp';
$this->params['breadcrumbs'][] = ['label' => 'Preskin Pagu Tpps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preskin-pagu-tpp-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

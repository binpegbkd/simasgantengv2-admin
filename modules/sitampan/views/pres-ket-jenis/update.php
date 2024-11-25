<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PresKetJenis */

$this->title = 'Ubah Data Pres Ket Jenis: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pres Ket Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pres-ket-jenis-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

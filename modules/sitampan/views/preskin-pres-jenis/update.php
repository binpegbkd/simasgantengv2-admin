<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinPresJenis */

$this->title = 'Ubah Data Preskin Pres Jenis: ' . $model->kd_presensi;
$this->params['breadcrumbs'][] = ['label' => 'Preskin Pres Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preskin-pres-jenis-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

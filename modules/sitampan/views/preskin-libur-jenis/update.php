<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinLiburJenis */

$this->title = 'Ubah Data Preskin Libur Jenis: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Preskin Libur Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preskin-libur-jenis-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

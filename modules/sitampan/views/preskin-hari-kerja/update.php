<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinHariKerja */

$this->title = 'Ubah Data Preskin Hari Kerja: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Preskin Hari Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preskin-hari-kerja-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

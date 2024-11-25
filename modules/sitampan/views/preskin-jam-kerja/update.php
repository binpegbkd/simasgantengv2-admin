<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinJamKerja */

$this->title = 'Ubah Data Preskin Jam Kerja: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Preskin Jam Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preskin-jam-kerja-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

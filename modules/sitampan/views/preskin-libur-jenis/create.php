<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinLiburJenis */

$this->title = 'Tambah Data Preskin Libur Jenis';
$this->params['breadcrumbs'][] = ['label' => 'Preskin Libur Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preskin-libur-jenis-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

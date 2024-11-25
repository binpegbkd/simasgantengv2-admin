<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinPresJenis */

$this->title = 'Tambah Data Preskin Pres Jenis';
$this->params['breadcrumbs'][] = ['label' => 'Preskin Pres Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preskin-pres-jenis-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

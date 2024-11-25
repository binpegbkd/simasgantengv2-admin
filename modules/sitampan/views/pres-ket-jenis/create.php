<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PresKetJenis */

$this->title = 'Tambah Data Pres Ket Jenis';
$this->params['breadcrumbs'][] = ['label' => 'Pres Ket Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pres-ket-jenis-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

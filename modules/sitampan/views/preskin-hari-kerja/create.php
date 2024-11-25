<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinHariKerja */

$this->title = 'Tambah Data Preskin Hari Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Preskin Hari Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preskin-hari-kerja-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

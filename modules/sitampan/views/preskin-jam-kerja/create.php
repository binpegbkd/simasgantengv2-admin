<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinJamKerja */

$this->title = 'Tambah Data Preskin Jam Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Preskin Jam Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preskin-jam-kerja-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

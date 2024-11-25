<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\MasLibur */

$this->title = 'Ubah Data Mas Libur: ' . $model->tanggal;
$this->params['breadcrumbs'][] = ['label' => 'Mas Liburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mas-libur-update">

    <?= $this->render('_form', [
        'model' => $model, 'ket_libur' => $ket_libur,
    ]) ?>

</div>

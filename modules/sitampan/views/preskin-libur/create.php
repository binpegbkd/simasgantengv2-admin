<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\MasLibur */

$this->title = 'Tambah Data Mas Libur';
$this->params['breadcrumbs'][] = ['label' => 'Mas Liburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mas-libur-create">

    <?= $this->render('_form', [
        'model' => $model, 'ket_libur' => $ket_libur,
    ]) ?>

</div>

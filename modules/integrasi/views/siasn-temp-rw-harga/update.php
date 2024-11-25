<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwHarga */

$this->title = 'Ubah Data Siasn Temp Rw Harga: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Hargas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siasn-temp-rw-harga-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

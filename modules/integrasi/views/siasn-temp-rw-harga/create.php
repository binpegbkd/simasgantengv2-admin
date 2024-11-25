<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwHarga */

$this->title = 'Tambah Data Siasn Temp Rw Harga';
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Hargas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-temp-rw-harga-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwJabatan */

$this->title = 'Ubah Data Siasn Temp Rw Jabatan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Jabatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siasn-temp-rw-jabatan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

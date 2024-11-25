<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwJabatan */

$this->title = 'Tambah Data Siasn Temp Rw Jabatan';
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Jabatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-temp-rw-jabatan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

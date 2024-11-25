<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwDiklat */

$this->title = 'Tambah Data Siasn Temp Rw Diklat';
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Diklats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-temp-rw-diklat-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

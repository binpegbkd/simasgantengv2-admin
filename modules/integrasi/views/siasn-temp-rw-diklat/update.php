<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwDiklat */

$this->title = 'Ubah Data Siasn Temp Rw Diklat: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Diklats', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siasn-temp-rw-diklat-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

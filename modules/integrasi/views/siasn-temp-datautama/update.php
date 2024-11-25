<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempDatautama */

$this->title = 'Ubah Data Siasn Temp Datautama: ' . $model->pns_orang_id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Datautamas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siasn-temp-datautama-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

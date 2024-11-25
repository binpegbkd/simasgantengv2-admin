<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempDatautama */

$this->title = 'Tambah Data Siasn Temp Datautama';
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Datautamas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-temp-datautama-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

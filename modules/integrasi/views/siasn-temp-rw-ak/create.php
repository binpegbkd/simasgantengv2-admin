<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwAk */

$this->title = 'Tambah Data Siasn Temp Rw Ak';
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Aks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-temp-rw-ak-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

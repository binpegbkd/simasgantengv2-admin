<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwAk */

$this->title = 'Ubah Data Siasn Temp Rw Ak: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Aks', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siasn-temp-rw-ak-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

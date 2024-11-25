<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwHukdis */

$this->title = 'Ubah Data Siasn Temp Rw Hukdis: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Hukdis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siasn-temp-rw-hukdis-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

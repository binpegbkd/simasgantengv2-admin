<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwHukdis */

$this->title = 'Tambah Data Siasn Temp Rw Hukdis';
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Hukdis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-temp-rw-hukdis-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

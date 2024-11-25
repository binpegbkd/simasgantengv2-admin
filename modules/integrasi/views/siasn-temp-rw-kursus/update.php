<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\integrasi\models\SiasnTempRwKursus $model */

$this->title = 'Update Siasn Temp Rw Kursus: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Kursuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siasn-temp-rw-kursus-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

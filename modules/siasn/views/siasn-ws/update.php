<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\integrasi\models\SiasnWs $model */

$this->title = 'Update Siasn Ws: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Ws', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siasn-ws-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

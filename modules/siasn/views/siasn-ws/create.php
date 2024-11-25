<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\integrasi\models\SiasnWs $model */

$this->title = 'Create Siasn Ws';
$this->params['breadcrumbs'][] = ['label' => 'Siasn Ws', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-ws-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

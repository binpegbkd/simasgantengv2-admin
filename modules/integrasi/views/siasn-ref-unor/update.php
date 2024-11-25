<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\siasn\models\SiasnRefUnor $model */

$this->title = 'Update Siasn Ref Unor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Ref Unors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siasn-ref-unor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\siasn\models\SiasnRefUnor $model */

$this->title = 'Create Siasn Ref Unor';
$this->params['breadcrumbs'][] = ['label' => 'Siasn Ref Unors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-ref-unor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

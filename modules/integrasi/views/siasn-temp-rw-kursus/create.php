<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\integrasi\models\SiasnTempRwKursus $model */

$this->title = 'Create Siasn Temp Rw Kursus';
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Kursuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-temp-rw-kursus-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

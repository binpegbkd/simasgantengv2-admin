<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinParam */

$this->title = 'Tambah Data Preskin Param';
$this->params['breadcrumbs'][] = ['label' => 'Preskin Params', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preskin-param-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

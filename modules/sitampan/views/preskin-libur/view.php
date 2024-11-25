<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\MasLibur */

$this->title = $model->tanggal;
$this->params['breadcrumbs'][] = ['label' => 'Mas Liburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mas-libur-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->tanggal], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->tanggal], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tanggal:date',
            'ket_libur:integer',
            'detail',
        ],
    ]) ?>

</div>

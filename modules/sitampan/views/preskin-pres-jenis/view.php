<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PreskinPresJenis */

$this->title = $model->kd_presensi;
$this->params['breadcrumbs'][] = ['label' => 'Preskin Pres Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="preskin-pres-jenis-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->kd_presensi], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->kd_presensi], [
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
            'kd_presensi',
            'keterangan',
            'selisih_waktu:integer',
            'persen_pot',
            'updated',
        ],
    ]) ?>

</div>

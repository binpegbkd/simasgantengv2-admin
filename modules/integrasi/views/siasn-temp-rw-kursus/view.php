<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\integrasi\models\SiasnTempRwKursus $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Kursuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="siasn-temp-rw-kursus-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'instansiId',
            'institusiPenyelenggara',
            'jenisDiklatId',
            'jenisKursus',
            'jenisKursusSertipikat',
            'jumlahJam',
            'lokasiId',
            'namaKursus',
            'nomorSertipikat',
            'pnsOrangId',
            'tahunKursus',
            'tanggalKursus',
            'tanggalSelesaiKursus',
        ],
    ]) ?>

</div>

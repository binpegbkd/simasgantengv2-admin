<?php

use app\modules\integrasi\models\SiasnTempRwKursus;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\integrasi\models\SiasnTempRwKursusSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Siasn Temp Rw Kursuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-temp-rw-kursus-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Siasn Temp Rw Kursus', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'instansiId',
            'institusiPenyelenggara',
            'jenisDiklatId',
            'jenisKursus',
            //'jenisKursusSertipikat',
            //'jumlahJam',
            //'lokasiId',
            //'namaKursus',
            //'nomorSertipikat',
            //'pnsOrangId',
            //'tahunKursus',
            //'tanggalKursus',
            //'tanggalSelesaiKursus',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SiasnTempRwKursus $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

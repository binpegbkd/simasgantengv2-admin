<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sitampan\models\PresHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Presensi Harian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pres-harian-index">
    <div class="row">
        <?php //echo  Html::button('<i class="fas fa-plus-circle"></i> Tambah', ['value' => Url::to(['create']), 'title' => 'Tambah Pres Harian', 'class' => 'showModalButton btn btn-success mr-auto mb-3 ml-2']); ?>

        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['style' => 'font-size:10pt'],
        'options' => [
            'class' => 'table-responsive',
            'style'=>'max-width:100%; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
        'summary' => false,
        'striped' => true,
        'hover' => true,
        'responsiveWrap' => false,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'B_02',
            [
                'attribute' => 'B_03',
                'value' => 'NamaPegawai',
            ],
            [
                'attribute' => 'E_04',
                'value' => 'golruAkhir.NAMA',
            ],
            [
                'attribute' => 'A_01',
                'value' => 'mastfipTablok.NM',
            ],
            [
                'attribute' => 'A_03',
                'value' => 'mastfipTablokb.NALOK',
            ],
            [
                'attribute' => 'B_10',
                'value' => 'pnsKedudukan.nama',
            ],
            // [
            //     'attribute' => 'B_08',
            //     'value' => 'pnsStatus.nama',
            // ],
            //'G_01',
            //'G_02',
            //'G_03:date',
            //'G_04:date',
            //'G_05',
            //'G_05A:integer',
            //'G_05B',
            //'G_06:integer',
            //'G_07',
            //'G_08',
            //'G_09:date',
            //'G_10:date',
            //'G_11',
            //'G_11A',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url) {
                        return Html::button('<span class="fas fa-pencil-alt"></span>',['value' => Url::to($url), 
                            'title' => 'Update', 'class' => 'showModalButton btn btn-link',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>

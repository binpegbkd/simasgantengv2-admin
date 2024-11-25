<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\select2\Select2;
use app\models\Fungsi;

//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sitampan\models\PreskinJamKerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jam Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preskin-jam-kerja-index">

    <p>
        <?=  Html::button('<i class="fas fa-plus-circle"></i> Tambah', ['value' => Url::to(['create']), 'title' => 'Tambah Preskin Jam Kerja', 'class' => 'showModalButton btn btn-success']); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['style' => 'font-size:10pt'],
        'options' => [
            'class' => 'table-responsive',
            'style'=>'max-width:100%; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
        'showPageSummary' => true,
        'striped' => true,
        'hover' => true,
        'responsiveWrap' => false,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'jenis_hari_kerja',
                'format' => 'raw',
                'value' => function($data){
                    if($data['jamHariKerja'] !== null) return $data['jamHariKerja']['jenis'];
                    else return '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'jenis_hari_kerja',
                    'data' => ArrayHelper::map($jenis,'id','jenis'),
                    'language' => 'id',
                    'options' => ['placeholder' => '-Pilih-'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                'attribute' => 'hari',
                'format' => 'raw',
                'value' => function($data){
                    return Fungsi::getHari($data['hari']);
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'hari',
                    'data' => Fungsi::CariHari(),
                    'language' => 'id',
                    'options' => ['placeholder' => '-Pilih-'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            'jam_masuk',
            'jam_pulang',
            //'updated',

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

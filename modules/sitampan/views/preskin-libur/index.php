<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\kinerja\models\MasLiburSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hari Libur';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mas-libur-index">

    <p>
        <div class="float-left">
            <?=  Html::button('<i class="fas fa-plus-circle"></i> Tambah', ['value' => Url::to(['create']), 'title' => 'Tambah Hari Libur', 'class' => 'showModalButton btn btn-success']); ?>

            <?=  Html::button('<i class="fas fa-calendar-minus"></i> Sabtu-Minggu', ['value' => Url::to(['sabtu-minggu']), 'title' => 'Generate Hari Sabtu Minggu', 'class' => 'showModalButton btn bg-maroon']); ?>
        </div>
        <div class="float-right"><?php // echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </p>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['style' => 'font-size:10pt'],
        'options' => [
            'class' => 'table-responsive',
            'style'=>'max-width:100%; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
        'striped' => true,
        //'summary' => '',
        'hover' => true,
        'responsiveWrap' => false,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'tanggal:date',
            [
                'attribute' => 'tanggal',
                'format' => 'date',
                //'value' => 'tanggal',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'tanggal',
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd',
                    ],
                ]),
            ],
            [
                'attribute' => 'ket_libur',
                'format' => 'raw',
                'value' => function($data){
                    if($data['liburKet']['ket_libur'] !== null) return $data['liburKet']['ket_libur']; 
                    else return '';
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'ket_libur',
                    'data' => ArrayHelper::map($ket_libur,'id','ket_libur'),
                    'language' => 'id',
                    'options' => ['placeholder' => '-Pilih-'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                 ])
             ],
            'detail',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'options' => ['style' => 'width:15%'],
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

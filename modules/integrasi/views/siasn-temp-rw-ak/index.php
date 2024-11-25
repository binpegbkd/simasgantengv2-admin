<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\integrasi\models\SiasnTempRwAkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Siasn Temp Rw Aks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-temp-rw-ak-index">

    <p>
        <?=  Html::button('<i class="fas fa-plus-circle"></i> Tambah', ['value' => Url::to(['create']), 'title' => 'Tambah Siasn Temp Rw Ak', 'class' => 'showModalButton btn btn-success']); ?>
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

            'bulanMulaiPenailan',
            'bulanSelesaiPenailan',
            'id',
            'isAngkaKreditPertama',
            'isIntegrasi',
            //'isKonversi',
            //'kreditBaruTotal',
            //'kreditPenunjangBaru',
            //'kreditUtamaBaru',
            //'nomorSk',
            //'pnsId',
            //'rwJabatanId',
            //'tahunMulaiPenailan',
            //'tahunSelesaiPenailan',
            //'tanggalSk:date',
            //'flag',
            //'updated',
            //'by',

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

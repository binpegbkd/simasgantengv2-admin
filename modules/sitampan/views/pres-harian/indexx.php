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

    <p>
        <?=  Html::button('<i class="fas fa-plus-circle"></i> Tambah', ['value' => Url::to(['create']), 'title' => 'Tambah Pres Harian', 'class' => 'showModalButton btn btn-success']); ?>
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

            'kode',
            'tgl:date',
            'idpns',
            'nip',
            'nama',
            //'tablokb',
            //'jd_masuk',
            //'jd_pulang',
            //'pr_masuk',
            //'pr_pulang',
            //'mnt_masuk:integer',
            //'mnt_pulang:integer',
            //'kd_pr_masuk',
            //'kd_pr_pulang',
            //'persen_masuk',
            //'persen_pulang',
            //'pot_masuk',
            //'pot_pulang',
            //'flag',
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

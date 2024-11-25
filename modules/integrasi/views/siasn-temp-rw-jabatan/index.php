<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\integrasi\models\SiasnTempRwJabatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Post Riwayat Jabatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-temp-rw-jabatan-index">

    <p>
        <?=  Html::button('<i class="fas fa-plus-circle"></i> Tambah', ['value' => Url::to(['create', 'nip' => $nip]), 'title' => 'Tambah Siasn Temp Rw Jabatan', 'class' => 'showModalButton btn btn-success']); ?>
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

            'pnsId',
            // 'eselonId',
            // 'id',
            // 'instansiId',
            // 'jabatanFungsionalId',
            // 'jabatanFungsionalUmumId',
            //'jenisJabatan',
            //'jenisMutasiId',
            //'jenisPenugasanId',
            'nomorSk',
            //'satuanKerjaId',
            //'subJabatanId',
            'tanggalSk',
            'tmtJabatan',
            'tmtMutasi',
            //'tmtPelantikan',
            'unorId',
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

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\integrasi\models\TblDataUpdateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sinkronisasi Data ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-data-update-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="row">
        <div class="col-lg-12">
            <?= Html::button('<i class="fas fa-plus-circle"></i> Tambah', ['value' => Url::to(['create']), 'title' => 'Tambah Data', 'class' => 'showModalButton btn btn-success mr-1 mb-2']); ?>
            
            <?= Html::button('<i class="fas fa-file-excel"></i> Upload', ['value' => Url::to(['excel']), 'title' => 'Tambah Data From Excel File', 'class' => 'showModalButton btn btn-outline-success mr-1 mb-2']); ?>
            
            <!--
            <?= Html::a('<i class="fas fa-sync"></i> Simgaji', ['simgaji'], ['title' => 'Sinkron data dari Simgaji', 'class' => 'btn btn-warning tombol-sync mr-1 mb-2']); ?>

            <?= Html::a('<i class="fas fa-sync"></i> Simpeg', ['simpeg'], ['title' => 'Sinkron data dari Simpeg', 'class' => 'btn btn-danger tombol-sync mr-1 mb-2']); ?>

            <?= Html::a('<i class="fas fa-search"></i> Data Baru', ['nip-id'], ['title' => 'Sinkron data dari Simpeg', 'class' => 'btn btn-secondary tombol-sync mr-1 mb-2']); ?>
            -->
            
            <?= Html::a('<i class="fas fa-times"></i> Clear Filter', ['index'], ['title' => 'Hapus Filter', 'class' => 'btn btn-outline-danger float-right mr-1 mb-2']); ?>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['style' => 'font-size:10pt'],
        'options' => [
            'class' => 'table-responsive',
            'style'=>'max-width:100%; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
        'summary' => '',
        'striped' => true,
        'hover' => true,
        'responsiveWrap' => false,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            //'id',
            //'nipBaru',
            [
                'attribute' => 'nama',
                'label' => 'ID/ NIP/ Nama',
                'format' => 'raw',
                'value' => function($dt){
                    if($dt['id'] === null) $dt['id'] = 'null';
                    if($dt['nipBaru'] === null) $dt['nipBaru'] = 'null';
                    if($dt['nama'] === null) $dt['nama'] = 'null';
                    return $dt['id'].'<br>'.$dt['nipBaru'].'<br>'.$dt['nama'];
                }
            ],
            [
                'attribute' =>  'dataUtama',
                'format' => 'raw',
                'value' => function($dt){
                    if($dt['dataUtama'] === null) $dt['dataUtama'] = '';
                    return Html::a('sinkron',['sync-data-utama', 'nip' => $dt['nipBaru']], ['class' => 'btn-sm btn-info']).'<br><br>'.$dt['dataUtama'];
                }
            ],
            [
                'attribute' =>  'simpeg',
                'format' => 'raw',
                'value' => function($dt){
                    if($dt['simpeg'] === null) $dt['simpeg'] = '';
                    return Html::a('sinkron',['sync-simpeg', 'nip' => $dt['nipBaru']], ['class' => 'btn-sm btn-info']).'<br><br>'.$dt['simpeg'];
                }
            ],
            [
                'attribute' =>  'auth',
                'format' => 'raw',
                'value' => function($dt){
                    if($dt['auth'] === null) $dt['auth'] = '';
                    return Html::a('sinkron',['sync-auth', 'nip' => $dt['nipBaru']], ['class' => 'btn-sm btn-info']).'<br><br>'.$dt['auth'];
                }
            ],
            [
                'attribute' =>  'preskin',
                'format' => 'raw',
                'value' => function($dt){
                    if($dt['preskin'] === null) $dt['preskin'] = '';
                    return Html::a('sinkron',['sync-preskin', 'nip' => $dt['nipBaru']], ['class' => 'btn-sm btn-info']).'<br><br>'.$dt['preskin'];
                }
            ],
            [
                'attribute' =>  'simgaji',
                'format' => 'raw',
                'value' => function($dt){
                    if($dt['simgaji'] === null) $dt['simgaji'] = '';
                    return Html::a('sinkron',['sync-simgaji', 'nip' => $dt['nipBaru']], ['class' => 'btn-sm btn-info']).'<br><br>'.$dt['simgaji'];
                }
            ],
            // 'rwJabatan',
            // 'rwGol',
            // 'rwDiklat',
            // 'rwPendidikan',
            // 'rwSkp',
            // 'rwAngkakredit',
            // 'rwPwk',
            // 'rwPnsunor',
            // 'rwKursus',
            // 'rwPemberhentian',
            // 'rwPenghargaan',
            // 'rwMasakerja',
            // 'rwHukdis',
            // 'rwDp3',
            // 'rwCltn',
            // 'rwPindahinstansi',
            // 'rwskp22',
            [
                'attribute' =>  'flag',
                'headerOptions' => ['style' => 'width:7%'],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'options' => ['style' => 'width:10%'],
                'template' => '{view} {update} {sync}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::button('<span class="fas fa-eye"></span>',['value' => Url::to($url), 
                            'title' => 'View', 'class' => 'showModalButton btn btn-link',
                        ]);
                    },
                    'sync' => function ($url, $model) {
                        return Html::button('<span class="fas fa-sync"></span>',['value' => Url::to($url), 
                            'title' => 'Sync', 'class' => 'showModalButton btn btn-link',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::button('<span class="fas fa-pencil-alt"></span>',['value' => Url::to($url), 
                            'title' => 'Update', 'class' => 'showModalButton btn btn-link',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
<?php
$script = <<< JS

$(".tombol-sync").click(function(e) {
    console.log = e;
    e.preventDefault(); // untuk menghentikan href
    e.stopImmediatePropagation();
    const href = $(this).attr('href');
    Swal.fire({
        title: 'Anda akan akan melanjutkan ???',
        text: 'Sinkronisasi data membutuhkan waktu cukup lama',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oke !',
        customClass: 'swal-wide',
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(href);
        }
    })
});
JS;
$this->registerJs($script);
?>

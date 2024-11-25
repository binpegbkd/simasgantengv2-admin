<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Fungsi;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\kinerja\models\MasKetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Keterangan Absensi';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mas-ket-index">

    <?php 
        if($searchModel['opd'] == '31000000'){
            echo Html::a('<i class="fas fa-sync"></i> SPPD', ['sppd-sync', 'bulan' => $searchModel['bulan'], 'tahun' => $searchModel['tahun']], ['title' => 'Sinkron SPPD', 'class' => 'btn btn-warning float-right mb-1 ml-1']);
        }
    ?>
    
    <?= Html::button('<i class="fas fa-plus-circle"></i> Tambah', ['value' => Url::to(['create', 'idu' => $searchModel['opd']]), 'title' => 'Tambah Data', 'class' => 'showModalButton btn btn-success float-right mb-1 ml-1']);
    ?>

    <?=  Html::a('<i class="fas fa-search"></i> Cari Data', '#', ['title' => 'Cari Data', 'class' => 'btn btn-primary float-right mb-1 ml-1', 'id' => 'search']); ?>

    <?= "<div class='float-left ml-1'><b>$opdname</b></div><br>"; ?>
    <?= "<div class='float-left ml-1'><b>Periode : $periode</b></div>"; ?>

    <?php
        $aksi = [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{ket-sinkrons} {update} {delete}',
            'buttons' => [
                'ket-sinkron' => function ($url, $mod){
                    if($mod['flag'] == 0){
                        return Html::a('<span class="fas fa-sync-alt"></span>',$url, 
                            ['title' => 'Sinkronkan Data', 'class' => 'btn btn-link',
                        ]);
                    }else return '';
                },
                'update' => function ($url){
                    return Html::button('<span class="fas fa-pencil-alt"></span>',['value' => Url::to($url), 
                        'title' => 'Update', 'class' => 'showModalButton btn btn-link',
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="fas fa-trash-alt"></span>', Url::to($url),
                    [ 
                        'title' => 'Hapus Target Harian', 
                        'class' => 'tombol-hapus',
                        'style' => 'color : #dc3545',
                        'data' => [
                            'method' => 'post',
                        ],
                    ]);
                },
            ],
        ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['style' => 'font-size:10pt'],
        'options' => [
            'class' => 'table-responsive',
            'style'=>'max-width:100%; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
        'showPageSummary' => true,
        'summary' => false,
        'striped' => true,
        'hover' => true,
        'responsiveWrap' => false,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'jenis_ket',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:10%'],
                'value' => function($data){
                    if($data['jenisSuket'] !== null) return $data['jenisSuket']['jenis_ket'];
                    else return '';
                },
            ],
            [
                'attribute' => 'tgl_surat',
                'label' => 'Surat Keterangan',
                'format' => 'raw',
                //'headerOptions' => ['style' => 'width:30%'],
                'value' => function($data){
                    return '<b>Nomor</b> : '.$data['no_surat'].
                        '<br><b>Tanggal</b> : '.\app\models\Fungsi::tglpanjang($data['tgl_surat']).
                        '<br><b>Tentang</b> : '.$data['detail'].
                        '<br><b>Tgl Entri</b> : '.date('d-m-Y H:i:s', explode('-',$data['id'])[2]);
                }
            ],
            [
                'attribute' => 'tgl_awal',
                'label' => 'Tanggal Berlaku',
                'contentOptions' => ['style' => 'text-align:center;'],
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:10%'],
                /*'filter' => DateControl::widget([
                    'type' => DateControl::FORMAT_DATE,
                    'name' => 'date_tgl_awal',
                ]),*/
                'value' => function($data){
                    if($data['tgl_awal'] == $data['tgl_akhir']){
                        return Fungsi::tgldmy($data['tgl_awal']);
                    }else{
                    return Fungsi::tgldmy($data['tgl_awal'])
                        .'<br>s.d<br>'.Fungsi::tgldmy($data['tgl_akhir']);
                    }
                }
            ],
            [
                'attribute' => 'nip',
                'label' => 'Pegawai',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:30%'],
                'value' => function($data){
                    if($data['nip'] !== null){
                        $nip = explode(";", $data['nip']);
                        $pegawai = '';
                        foreach($nip as $nips){
                            if($nips != '' && $nips != ' '){
                                $pns = \app\modules\simpeg\models\EpsMastfip::find()
                                    ->select(['B_02', 'B_03', 'B_03A', 'B_03B'])->where(['B_02' => $nips])->one();
                                    if($pns !== null){
                                        if($pns['namaPegawai'] !== null) $pegawai = $pegawai.$nips.' '.$pns['namaPegawai'].'<br>';
                                        else $pegawai = $pegawai.'-'.$nips.'<br>';
                                    }else $pegawai = $pegawai.'-'.$nips.'<br>';
                            }
                        }
                    }else{
                        //if($data['jenis_ket'] == 3 && $pegawai == '') $pegawai = 'Semua ASN pada OPD ini';
                        //else $pegawai = '';
                        $pegawai = '';
                    }

                    return $pegawai;
                }
            ],
            $aksi,
        ],
    ]); ?>

</div>

<div id="cari-block" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
            <h5 class="modal-title">Cari Data</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?= $this->render('_search', ['model' => $searchModel, 'opd' => $opd]); ?>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<< JS

$('#search').click(function(){
	$("#cari-block").modal('show');
});
JS;
$this->registerJs($script);
?>


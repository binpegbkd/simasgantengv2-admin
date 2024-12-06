<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;
use app\models\Angka;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\PresHarian */

$this->title = 'Pelaksana Tugas (Plt) Penerima TPP';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="pres-harian-view">

    <h4><?= Html::encode($this->title) ?></h4>

    <?=  Html::button('<i class="fas fa-plus-circle"></i> Tambah', ['value' => Url::to(['create', 'id' => 4]), 'title' => 'Tambah Data', 'class' => 'showModalButton btn btn-success mb-2']); ?>
    
    <?= Html::a('<i class="fas fa-search"></i> Cari Data', '#', ['title' => 'Cari Data', 'class' => 'btn btn-primary float-right mb-2', 'id' => 'search']); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['style' => 'font-size:9pt'],
        'options' => [
            'class' => 'table-responsive',
            'style'=>'max-width:100%; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
        'showPageSummary' => false,
        'summary' => '',
        'striped' => false,
        'hover' => true,
        'responsiveWrap' => false,
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => "$bul $thn", 'options' => ['colspan' => 2, 'class' => 'text-center', 'style' => 'font-size:10pt;']],
                    ['content' => "$namapd", 'options' => ['colspan' => 9, 'class' => 'text-center', 'style' => 'font-size:10pt;']],
                ],
            ]
        ],
        'columns' => [['headerOptions' => ['width:5%'], 'class' => 'kartik\grid\SerialColumn', 'header' => 'NO'],
            [
                'attribute' => 'id',
                'header' => 'NIP<br>NAMA<br>GOL<br>JABATAN<br>KELAS',
                'headerOptions' => ['width:23%'],
                'format' => 'raw',
                'value' => function($dt){
                    return $dt['nip'].'<br>'.$dt['nama'].'<br>'.$dt['nama_gol'].'<br>'.$dt['nama_jab'].'<br>'.$dt['nama_unor'].'<br>'.$dt['nama_kelas'];
                },
            ],
            [
                'header' => 'PAGU TPP<br>BEBAN KERJA<br>PRESTASI KERJA',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:right; width:12%; '],
                'contentOptions' => ['style' => 'text-align:right;'],
                'value' => function($dt){
                    return Angka::Ribuan($dt['pagu_tpp']).'<br>'.
                          //Angka::Ribuan($dt['beban_kerja']+$dt['produktivitas_kerja']).'<br>'.
                        Angka::Ribuan($dt['beban_kerja']).'<br>'.Angka::Ribuan($dt['produktivitas_kerja']);
                },
            ],
            [
                'header' => 'DISIPLIN<BR>KINERJA<BR>SAKIP',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:right; width:15%;'],
                'contentOptions' => ['style' => 'text-align:right;'],
                'value' => function($dt){
                    return $dt['presensi'].' %, '.Angka::Ribuan($dt['presensi_rp']).'<BR>'.
                        $dt['kinerja'].' %, '.Angka::Ribuan($dt['kinerja_rp']).'<BR>'.
                        Angka::Ribuan($dt['sakip_rp']);
                },
            ],
            [
                'header' => 'HUKDIS<BR>CUTI',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:right; width:5% '],
                'contentOptions' => ['style' => 'text-align:right;'],
                'value' => function($dt){
                    return Angka::Ribuan($dt['hukdis_rp']).'<br>'.Angka::Ribuan($dt['cuti']).'%, '.Angka::Ribuan($dt['cuti_rp']);
                },
            ],
            [
                'header' => 'TPP PEGAWAI<BR>BPJS 4%<BR>JUMLAH KOTOR',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:right; width:10% '],
                'contentOptions' => ['style' => 'text-align:right;'],
                'value' => function($dt){
                    return Angka::Ribuan($dt['tpp_jumlah']).'<br>'.
                            Angka::Ribuan($dt['bpjs4']).'<br>'.
                            Angka::Ribuan($dt['tpp_bruto']);
                },
            ],
            [
                'header' => 'BPJS 4%<BR>BPJS 1%<BR>PPH<BR>JUMLAH POT',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:right; width:10% '],
                'contentOptions' => ['style' => 'text-align:right;'],
                'value' => function($dt){
                    return Angka::Ribuan($dt['bpjs4']).'<br>'.
                    Angka::Ribuan($dt['bpjs1']).'<br>'.
                    Angka::Ribuan($dt['pph_rp']).' ('.$dt['pph'].'%)<br>'.
                    Angka::Ribuan($dt['pot_jml']);
                    //Angka::Ribuan($dt['tpp_net']).'</b>';
                },
            ],
            [
                'header' => 'TPP DITERIMA',
                'format' => 'raw',
                'headerOptions' => ['width:10%'],
                'contentOptions' => ['style' => 'text-align:right; font-size:12px;'],
                'value' => function($dt){
                    $tombol = '<div class="dropdown">
                        <button class="btn-sm btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            '.Html::button('Update Nama/ Jabatan', ['value' => Url::to(['jabatan', 'id' => $dt['id']]),'class' => 'showModalButton dropdown-item']).'
                            '.Html::button('Update Unor', ['value' => Url::to(['unor', 'id' => $dt['id']]),'class' => 'showModalButton dropdown-item']).'
                            '.Html::button('Update Kelas', ['value' => Url::to(['kelas', 'id' => $dt['id']]),'class' => 'showModalButton dropdown-item']).'
                            '.Html::button('Update Golongan', ['value' => Url::to(['golongan', 'id' => $dt['id']]),'class' => 'showModalButton dropdown-item']).'
                            '.Html::button('Update Cuti', ['value' => Url::to(['cuti', 'id' => $dt['id']]),'class' => 'showModalButton dropdown-item']).'
                            '.Html::button('Update Hukdis', ['value' => Url::to(['hukdis', 'id' => $dt['id']]),'class' => 'showModalButton dropdown-item']).'
                            '.Html::button('Update Persen TPP', ['value' => Url::to(['persen-tpp', 'id' => $dt['id']]),'class' => 'showModalButton dropdown-item']).'
                            '.Html::a('Hitung Ulang', Url::to(['hitung', 'id' => $dt['id']]),['class' => 'dropdown-item', 'title' => 'Hitung Ulang']).'
                            '.Html::a('Status Final', Url::to(['final', 'id' => $dt['id']]),
                            ['class' => 'dropdown-item konfirmasi', 'id' => 'tombol-final', 'data-method' => 'post', 'title' => 'Finalisasi']).'
                        </div>
                    </div>';

                    $hapus = Html::a('Hapus', Url::to(['delete', 'id' => $dt['id']]),
                        ['class' => 'btn-sm btn-danger tombol-hapus', 'id' => 'tombol-hapus', 'data-method' => 'post', 'title' => 'Hapus']);
                    
                    if($dt['status'] == 0) {
                        return '<b>'.Angka::Ribuan($dt['tpp_net']).'</b><br><br>
                        <div class="row float-right">'.$tombol.' &nbsp; '.$hapus.'</div>';
                    }else return '<b>'.Angka::Ribuan($dt['tpp_net']).'</b><br><br> STATUS FINAL';
                },
            ],
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
                <?= $this->render('_search-tpp-plt', ['model' => $searchModel, 'opd' => $opd]); ?>
            </div>
        </div>
    </div>
</div>

<?php
    Modal::begin([
        'title' => Html::encode($this->title),
        'headerOptions' => ['class' => 'bg-primary'],
        'id' => 'modal',
        'size' => 'modal-lg',
    ]);

    echo "<div id='modalContent'></div>";
    Modal::end();


$script = <<< JS

$('#search').click(function(){
	$("#cari-block").modal('show');
});

$(".konfirmasi").click(function(e) {
    console.log = e;
    e.preventDefault(); // untuk menghentikan href
    e.stopImmediatePropagation();
    const href = $(this).attr('href');
    Swal.fire({
        title: 'Apakah anda yakin ?',
        text: 'Setelah Final, data tidak dapat diubah',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya Final',
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

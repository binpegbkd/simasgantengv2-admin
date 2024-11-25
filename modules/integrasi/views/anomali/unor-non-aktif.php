<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\integrasi\models\SiasnDataUtamaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'SIASN Anomali Unor Non Aktif';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siasn-data-utama-index">

    <h4><?= Html::encode($this->title) ?></h4>

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
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            'nipBaru',
            [
                'attribute' => 'nama',
                'value' => 'NamaPegawai',
            ],
            'kedudukanPnsNama',
            // 'statusPegawai',
            'jenisJabatan',
            'jabatanNama','unorIndukNama',
            'unorNama',
            //'nipLama',
            //'nama',
            //'gelarDepan',
            //'gelarBelakang',
            //'tempatLahir',
            //'tempatLahirId',
            //'tglLahir',
            //'agama',
            //'jenisPegawaiNama',
            //'agamaId',
            //'email:email',
            //'emailGov:email',
            //'nik',
            //'alamat',
            //'noHp',
            //'noTelp',
            //'jenisPegawaiId',
            //'kedudukanPnsId',
            //'jenisKelamin',
            //'jenisIdDokumenId',
            //'jenisIdDokumenNama',
            //'nomorIdDocument',
            //'noSeriKarpeg',
            //'tkPendidikanTerakhirId',
            //'tkPendidikanTerakhir',
            //'pendidikanTerakhirId',
            //'pendidikanTerakhirNama',
            //'tahunLulus',
            //'tmtPns',
            //'tglSkPns',
            //'tmtCpns',
            //'tglSkCpns',
            //'tmtPensiun',
            //'bupPensiun:integer',
            //'latihanStrukturalNama',
            //'instansiIndukId',
            //'instansiIndukNama',
            //'satuanKerjaIndukId',
            //'satuanKerjaIndukNama',
            //'kanregId',
            //'kanregNama',
            //'instansiKerjaId',
            //'instansiKerjaNama',
            //'instansiKerjaKodeCepat',
            //'satuanKerjaKerjaId',
            //'satuanKerjaKerjaNama',
            //'unorId',
            //'unorIndukId',
            //'jenisJabatanId',
            //'jabatanStrukturalId',
            //'jabatanStrukturalNama',
            //'jabatanFungsionalId',
            //'jabatanFungsionalNama',
            //'jabatanFungsionalUmumId',
            //'jabatanFungsionalUmumNama',
            //'tmtJabatan',
            //'lokasiKerjaId',
            //'lokasiKerja',
            //'golRuangAwalId',
            //'golRuangAwal',
            //'golRuangAkhirId',
            //'golRuangAkhir',
            //'tmtGolAkhir',
            //'nomorSptm',
            //'masaKerja',
            //'eselon',
            //'eselonId',
            //'eselonLevel',
            //'tmtEselon',
            //'gajiPokok',
            //'kpknId',
            //'kpknNama',
            //'ktuaId',
            //'ktuaNama',
            //'taspenId',
            //'taspenNama',
            //'jenisKawinId',
            //'statusPerkawinan',
            //'statusHidup',
            //'tglSuratKeteranganDokter',
            //'noSuratKeteranganDokter',
            //'jumlahIstriSuami:integer',
            //'jumlahAnak:integer',
            //'noSuratKeteranganBebasNarkoba',
            //'tglSuratKeteranganBebasNarkoba',
            //'skck',
            //'tglSkck',
            //'akteKelahiran',
            //'akteMeninggal',
            //'tglMeninggal',
            //'noNpwp',
            //'tglNpwp',
            //'noAskes',
            //'bpjs',
            //'kodePos',
            //'noSpmt',
            //'tglSpmt',
            //'noTaspen',
            //'bahasa',
            //'kppnId',
            //'kppnNama',
            //'pangkatAkhir',
            //'tglSttpl',
            //'nomorSttpl',
            //'nomorSkCpns',
            //'nomorSkPns',
            //'jenjang',
            //'jabatanAsn',
            //'kartuAsn',
            //'mkTahun:integer',
            //'mkBulan:integer',
            //'flag',
            //'updated',
            //'validNik',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view-simpeg} {get-siasn} {update}',
                'options' => ['style' => 'width: 15%'],
                'buttons' => [
                    'view-simpeg' => function ($url, $dt) {
                        return Html::button('<span class="fas fa-eye"></span>',[
                            'value' => Url::to(['view-simpeg', 'id' => $dt['nipBaru']]), 
                            'title' => 'Update', 'class' => 'showModalButton btn-sm bg-maroon',
                        ]);
                    },
                    'get-siasn' => function ($url, $dt) {
                        return Html::button('<span class="fas fa-cloud-download-alt"></span>',[
                            'value' => Url::to(['get-siasn', 'id' => $dt['nipBaru']]), 
                            'title' => 'Update', 'class' => 'showModalButton btn-sm bg-warning',
                        ]);
                    },
                    'update' => function ($url, $dt) {
                        return Html::button('<span class="fas fa-pencil-alt"></span>',[
                            'value' => Url::to(['update', 'id' => $dt['nipBaru']]), 
                            'title' => 'Update', 'class' => 'showModalButton btn-sm bg-olive',
                        ]);
                    },
                    'view-gaji' => function ($url, $dt) {
                        return Html::button('<span class="fas fa-eye"></span> gaji',[
                            'value' => Url::to(['view-gaji', 'id' => $dt['nipBaru']]), 
                            'title' => 'Update', 'class' => 'showModalButton btn-sm bg-warning',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>

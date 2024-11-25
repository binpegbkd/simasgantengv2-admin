<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;

$this->title = 'Data Utama';
$ses = Yii::$app->session;
?>
<div class="site-index">

    <div class="row">
        <div class="ml-auto mr-auto">
            <?= Html::a('<i class="fas fa-cloud-download-alt"></i>', ['get-data-utama'], ['title' => 'Unduh dari SIASN', 'class' => 'btn btn-success']); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <table id="table1" class="table table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th width="20%" style="background-color: #00BFFF; text-align: center">Atribut Data</th>
                        <th width="40%" style="background-color: #00BFFF; text-align: center">Data Simpeg</th>
                        <th width="40%" style="background-color: #00BFFF; text-align: center">Data SIASN</th>
                    </tr>
                </thead>
                <tbody style="font-size:13px">
            
                    <?= cekData('NIP', $ses['simpeg']['B_02'], $ses['siasn']['nipBaru'], 'B_02', 'nipBaru') ?>

                    <?= cekData('Nama', $ses['simpeg']['B_03'], $ses['siasn']['nama'], 'B_03', 'nama') ?>

                    <?= cekData('Gelar Depan', $ses['simpeg']['B_03A'], $ses['siasn']['gelarDepan'], 'B_03A', 'gelarDepan') ?>

                    <?= cekData('Gelar Belakang', $ses['simpeg']['B_03B'], $ses['siasn']['gelarBelakang'], 'B_03B', 'gelarBelakang') ?>

                    <?= cekData('Tempat Lahir', $ses['simpeg']['B_04'], $ses['siasn']['tempatLahir'], 'B_04', 'tempatLahir') ?>

                    <?= cekData('Tgl Lahir', $ses['simpeg']['B_05'], $ses['siasn']['tglLahir'], 'B_05', 'tglLahir') ?>

                    <?= cekData('Jenis Kelamin', $ses['simpeg']['jk'], $ses['siasn']['jenisKelamin'], 'B_06', 'jenisKelamin') ?>

                    <?= cekData('Agama', $ses['simpeg']['agama'], $ses['siasn']['agama'], 'B_07', 'agamaId') ?>

                    <?= cekData('Alamat', $ses['simpeg']['B_11'], $ses['siasn']['alamat'], 'B_11', 'alamat' ) ?>

                    <?= cekData('Status Pegawai', $ses['simpeg']['stapeg'], $ses['siasn']['stasn'], 'B_10', 'kedudukanPnsId') ?>

                    <?= cekData('Status Perkawinan', $ses['simpeg']['marital'], $ses['siasn']['statusPerkawinan'], 'B_09', 'jenisKawinId') ?>

                    <?= cekData('E-mail', $ses['simpeg']['email'], $ses['siasn']['email'], 'email', 'email') ?>

                    <?= cekData('No. Telp/ HP', $ses['simpeg']['no_telp'], $ses['siasn']['noHp'], 'no_telp', 'noHp') ?>

                    <?= cekData('NIK/ No. KTP', $ses['simpeg']['nik'], $ses['siasn']['nik'], 'nik', 'nik') ?>

                    <?= cekData('No. Kartu BPJS/ KIS', $ses['simpeg']['B_13'], $ses['siasn']['bpjs'], 'B_13', 'bpjs') ?>

                    <?= cekData('NPWP', $ses['simpeg']['B_16'], $ses['siasn']['noNpwp'], 'B_16', 'noNpwp') ?>

                    <?= cekData('No. Karpeg', $ses['simpeg']['B_12'], $ses['siasn']['noSeriKarpeg'], 'B_12', 'noSeriKarpeg') ?>

                    <?= cekData('Kartu ASN', $ses['simpeg']['kartu_asn'], $ses['siasn']['kartuAsn'], 'kartu_asn', 'kartuAsn') ?>
                    <!--
                    <tr>
                        <td>Pangkat/ Gol</td>
                        <td><?= $ses['simpeg']['E_04']?></td>
                        <td><?= $ses['siasn']['golRuangAkhir']?></td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td><?= $ses['simpeg']['G_05B']?></td>
                        <td><?= $ses['siasn']['jabatanNama']?></td>
                    </tr>
                    <tr>
                        <td>OPD</td>
                        <td><?= $ses['simpeg']['opd'] ?></td>
                        <td><?= $ses['siasn']['unorIndukNama']?></td>
                    </tr>
                    <tr>
                        <td>Unor</td>
                        <td><?= $ses['simpeg']['unor']?></td>
                        <td><?= $ses['siasn']['unorNama']?></td>
                    </tr>
                    -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
function cekData($label, $simpeg, $siasn, $atpeg, $atsia){
    if(strtolower($simpeg) != strtolower($siasn)){
        $sinc = Html::a('<i class="fas fa-sync"></i>', 
            ['sinkron-simpeg-one', 'simpeg' => $atpeg, 'siasn' => $atsia], 
            ['title' => 'Sinkron data dari SIASN', 'class' => 'btn-xs btn-primary']);
        $tampil = '<tr class="table-danger">
                    <td>'.$label.'</td>
                    <td><div class="row"><div class="col-sm-9 text-left">'.$simpeg.'</div><div class="col-sm-3 text-right">'.$sinc.'</div></div></td>
                    <td>'.$siasn.'</td>
                </tr>';
    }else{
        $tampil = '<tr>
                    <td>'.$label.'</td>
                    <td>'.$simpeg.'</td>
                    <td>'.$siasn.'</td>
                </tr>';
    }

    return $tampil;
}
?>

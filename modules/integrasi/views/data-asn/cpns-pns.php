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

                    <?= cekData('No. SK CPNS', $ses['simpeg']['C_02'], $ses['siasn']['nomorSkCpns'], 'C_02', 'nomorSkCpns') ?>

                    <?= cekData('Tgl SK CPNS', $ses['simpeg']['C_01'], $ses['siasn']['tglSkCpns'], 'C_01', 'tglSkCpns') ?>

                    <?= cekData('TMT CPNS', $ses['simpeg']['C_03'], $ses['siasn']['tmtCpns'], 'C_03', 'tmtCpns') ?>

                    <?= cekData('No. SK PNS', $ses['simpeg']['D_01'], $ses['siasn']['nomorSkPns'], 'D_01', 'nomorSkPns') ?>

                    <?= cekData('Tgl SK PNS', $ses['simpeg']['D_02'], $ses['siasn']['tglSkPns'], 'D_02', 'tglSkPns') ?>

                    <?= cekData('TMT PNS', $ses['simpeg']['D_04'], $ses['siasn']['tmtPns'], 'B_07', 'tmtPns') ?>

                    <?= cekData('Pangkat-Gol', $ses['simpeg']['golPns'], $ses['siasn']['golRuangAwal'], 'D_03', 'golRuangAwalId') ?>
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
                    <td><div class="row"><div class="col-sm-9 text-left">'.$simpeg.'</div><!--<div class="col-sm-3 text-right">'.$sinc.'</div>--></div></td>
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

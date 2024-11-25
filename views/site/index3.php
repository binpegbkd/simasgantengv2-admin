<?php

/** @var yii\web\View $this */

$this->title = 'Dashboard';
use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;

?>
<div class="site-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="row">
        <?= Card(4, 'light', 'info', 'fa-users', 'Jumlah ASN (Simpeg)', $data['jml'], 0) ?>
        <?= Card(4, 'light', 'danger', 'fa-users', 'Jumlah ASN (Gaji)', $data['asn-gaji'], 0) ?>
        <?= Card(4, 'light', 'success', 'fa-users', 'Jumlah ASN (SIASN)', $data['asn-siasn'], 0) ?>
        
    </div>
    <div class="row">
        <?= Card(4, 'light', 'maroon', 'fa-users', 'Jumlah PNS (Simpeg)', $data['pns'], 0) ?>
        <?= Card(4, 'light', 'warning', 'fa-users', 'Jumlah PNS (Gaji)', $data['pns-gj'], 0) ?>
        <?= Card(4, 'light', 'secondary', 'fa-users', 'Jumlah PNS (SIASN)', $data['pns-si'], 0) ?>
        
    </div>

    <div class="row">
        <?= Card(4, 'light', 'navy', 'fa-users', 'Jumlah PPPK (Simpeg)', $data['p3k'], 0) ?>
        <?= Card(4, 'light', 'olive', 'fa-users', 'Jumlah PPPK (Gaji)', $data['p3k-gj'], 0) ?>
        <?= Card(4, 'light', 'pink', 'fa-users', 'Jumlah PPPK (SIASN)', $data['p3k-si'], 0) ?>
        
    </div>
    
</div>
<?php
function Card($row, $warna, $bg, $icon, $text, $val, $persen){
    if($persen == 0) $nilai = number_format($val, '0', ',', '.');
    else $nilai = number_format($val, '0', ',', '.')." ( ". number_format($persen, '2', ',', '.'). "% )";

    echo "<div class=\"col-12 col-sm-6 col-md-$row\">
    <div class=\"info-box bg-$warna\">
        <span class=\"info-box-icon bg-$bg elevation-1\"><i class=\"fas $icon\"></i></span>
        <div class=\"info-box-content\">
            <span class=\"info-box-text\">$text</span>
            <span class=\"info-box-number\">$nilai</span>
        </div>
    </div>
</div>";
}
?>
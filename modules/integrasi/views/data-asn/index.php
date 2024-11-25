<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use dosamigos\chartjs\ChartJs;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sitampan\models\PreskinAsnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data ASN';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

<h3><?= Html::encode($this->title) ?></h3>

<div class="row">
    <?= Card(4, 'light', 'info', 'fa-users', 'Jumlah ASN', $data['jml'], 0) ?>
    <?= Card(4, 'light', 'danger', 'fa-users', 'Jumlah CPNS/PNS', $data['pns'], ($data['pns']/$data['jml']*100)) ?>
    <?= Card(4, 'light', 'success', 'fa-users', 'Jumlah PPPK', $data['p3k'], ($data['p3k']/$data['jml']*100)) ?>
    
</div>

<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title">Berdasarkan Jenis Kelamin</h3>
        <div class="card-tools">
        <!-- Buttons, labels, and many other things can be placed here! -->
        <!-- Here is a label for example -->
        <span class="badge badge-primary"></span>
        </div>
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <?php 
                $persenlk = number_format($data['lk']/$data['jml']*100,2);
                $persenpr = 100 - $persenlk;
                $lk = number_format($data['lk'], 0, ',', '.');
                $pr = number_format($data['pr'], 0, ',', '.');
                echo PieChart(4, 'ASN', 'asn', 200, 400,['Pria: '.$lk, 'Wanita: '.$pr], [$persenlk, $persenpr],['#00BFFF', '#DDA0DD'], true);

                $persenpnslk = number_format($data['pnslk']/$data['pns']*100,2);
                $persenpnspr = 100 - $persenpnslk;
                $pnslk = number_format($data['pnslk'], 0, ',', '.');
                $pnspr = number_format($data['pnspr'], 0, ',', '.');
                echo PieChart(4, 'PNS', 'pns', 200, 400,['Pria: '.$pnslk, 'Wanita: '.$pnspr], [$persenpnslk, $persenpnspr],['#98FB98', '#FFA500'], true);

                $persenp3klk = number_format($data['p3klk']/$data['p3k']*100,2);
                $persenp3kpr = number_format(100 - $persenp3klk,2);
                $p3klk = number_format($data['p3klk'], 0, ',', '.');
                $p3kpr = number_format($data['p3kpr'], 0, ',', '.');
                echo PieChart(4, 'PPPK', 'p3k', 200, 400,['Pria: '.$p3klk, 'Wanita: '.$p3kpr], [$persenp3klk, $persenp3kpr],['#FFE4B5', '#DA70D6'], true);
            ?>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

</div>

<?php
$script = <<< JS
$('#search').click(function(){
	$("#cari-block").modal('show');
});
JS;
$this->registerJs($script);

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

function Piechart($col, $judul, $idchart, $height, $width, $labels, $datas, $bgcolor, $legend){
    echo "<div class=\"col-md-$col\">";
    echo ChartJs::widget([
            'type' => 'pie',
            'id' => $idchart,
            'options' => [
                'height' => $height,
                'width' => $width,
            ],
            'data' => [
                'radius' =>  "90%",
                'labels' => $labels, // Your labels
                'datasets' => [
                    [
                        'data' => $datas, // Your dataset
                        'label' => '',
                        'backgroundColor' => $bgcolor,
                        'borderColor' =>  [
                                '#fff',
                                '#fff',
                                '#fff'
                        ],
                        'borderWidth' => 2,
                        'hoverBorderColor'=>["#999","#999","#999"],                
                    ],
                ],
            ],
            'clientOptions' => [
                'title' => [
                    'display' => true,
                    'text' => $judul,
                ],
                'legend' => [
                    'display' => $legend,
                    'position' => 'bottom',
                    'labels' => [
                        'fontSize' => 12,
                        'fontColor' => "#425062",
                    ]
                ],
                'tooltips' => [
                    'enabled' => false,
                    'intersect' => true
                ],
                'hover' => [
                    'mode' => true
                ],
                'maintainAspectRatio' => false,
        
            ],
            'plugins' =>
            new \yii\web\JsExpression('
                [{
                    afterDatasetsDraw: function(chart, easing) {
                        var ctx = chart.ctx;
                        chart.data.datasets.forEach(function (dataset, i) {
                            var meta = chart.getDatasetMeta(i);
                            if (!meta.hidden) {
                                meta.data.forEach(function(element, index) {
                                    // Draw the text in black, with the specified font
                                    ctx.fillStyle = "rgb(0, 0, 0)";
        
                                    var fontSize = 12;
                                    var fontStyle = "bold";
                                    var fontFamily = "Helvetica";
                                    ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
        
                                    // Just naively convert to string for now
                                    var dataString = dataset.data[index].toString() + " %";
        
                                    // Make sure alignment settings are correct
                                    ctx.textAlign = "center";
                                    ctx.textBaseline = "middle";
        
                                    var padding = 5;
                                    var position = element.tooltipPosition();
                                    ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                                });
                            }
                        });
                    }
                }]
            ')
        ]); 
    echo "</div>";    
}
?>



<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap4\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sitampan\models\PreskinAsnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Presensi ASN';
$this->params['breadcrumbs'][] = $this->title;

$grid = [
    [
        'class' => 'kartik\grid\SerialColumn', 
        'header' => 'No', 
    ],
    [
        'attribute' => 'nip',
        'header' => 'NIP<br>NAMA<br>UNOR',
        'format' => 'raw',
        'options' => ['style' => 'width:40%;'],
        'value' => function($dt){
            return $dt['nip'].'<br>'.$dt['nama'].'<br>'.$dt['unor'];
        }
    ],
    [
        'header' => 'Pot Masuk<BR>Pot Pulang',
        'headerOptions' => ['style' => 'vertical-align: middle;'],
        'options' => ['style' => 'text-align:center;font-size:9pt;vertical-align: middle;'],
        'format' => 'raw',
        'value' => function($dt){
            $pm = $dt['pot_masuk'];
            $pp = $dt['pot_pulang'];
            return $pm.'<br>'.$pp;
        }
    ],
    [
        'header' => 'Jml Pot<BR>Kehadiran',
        'headerOptions' => ['style' => 'vertical-align: middle;'],
        'options' => ['style' => 'text-align:center;font-size:8pt;vertical-align: middle;'],
        'format' => 'raw',
        'value' => function($dt){
            $pm = $dt['pot_masuk'];
            $pp = $dt['pot_pulang'];
            $jmp = $pm+$pp;
            $kh = 100 - $jmp;
            return $jmp.'<br><b>'.$kh.'</b>';
        }
    ],
    [
        'label' => 'Data Tersimpan',
        'headerOptions' => ['style' => 'vertical-align: middle;'],
        'options' => ['style' => 'font-size:9pt;vertical-align: middle;'],
        'format' => 'raw',
        'value' => function ($dt) use ($thn, $bln){
            $hp = Html::button('<span class="fas fa-calendar-alt"></span>',[
                'value' => Url::to([
                    'checkinout', 'id' => $dt['nip'], 'bulan' => $bln, 'tahun' => $thn
                ]),
                'title' => 'Data Presensi Tersimpan', 
                'class' => 'showModalButton btn link', 'style' => 'color : #dc3545; text-align:center;'
            ]);

            return $hp;
        }
    ],
    [
        'label' => 'Data ADMS',
        'headerOptions' => ['style' => 'vertical-align: middle;'],
        'options' => ['style' => 'font-size:9pt;vertical-align: middle;'],
        'format' => 'raw',
        'value' => function ($dt) use ($thn, $bln){
            $adms = Html::button('<span class="fas fa-desktop"></span>',[
                'value' => Url::to([
                    'adms', 'id' => $dt['nip'], 'bulan' => $bln, 'tahun' => $thn
                ]),
                'title' => 'Data ADMS', 
                'class' => 'showModalButton btn btn-link', 'style' => 'color : #dc3545;'
            ]);
            return $adms;
        }
    ],
    [
        'label' => 'Hasil Presensi',
        'headerOptions' => ['style' => 'vertical-align: middle;'],
        'options' => ['style' => 'font-size:9pt;vertical-align: middle;'],
        'format' => 'raw',
        'value' => function ($dt) use ($thn, $bln){
            return Html::button('<span class="fas fa-clock"></span>',[
                'value' => Url::to([
                    'detail', 'id' => $dt['nip'], 'bulan' => $bln, 'tahun' => $thn
                ]),
                'title' => 'Data Hasil Presensi', 
                'class' => 'showModalButton btn btn-link', 'style' => 'color : #dc3545'
            ]);
        }
    ],
    // [
    //     'label' => 'Update Data',
    //     'headerOptions' => ['style' => 'vertical-align: middle;'],
    //     'options' => ['style' => 'font-size:9pt;vertical-align: middle;'],
    //     'format' => 'raw',
    //     'value' => function ($dt) use ($thn, $bln){
    //         return Html::button('<span class="fas fa-pencil-alt"></span>',[
    //             'value' => Url::to([
    //                 'update-pres', 'id' => $dt['nip'], 'bulan' => $bln, 'tahun' => $thn
    //             ]),
    //             'title' => 'Update Data Presensi', 
    //             'class' => 'showModalButton btn btn-link', 'style' => 'color : #dc3545'
    //         ]);
    //     }
    // ],
];

?>
<div class="preskin-asn-index">
    <?= "<b>$namapd</b>" ?>
    <?=  Html::a('<i class="fas fa-search"></i> Cari Data', '#', ['title' => 'Cari Data', 'class' => 'btn btn-danger float-right mb-2', 'id' => 'search']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['style' => 'font-size:9pt'],
        'options' => [
            'class' => 'table-responsive',
            'style'=>'max-width:100%; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
        'showPageSummary' => false,
        'summary' => '',
        'striped' => true,
        'hover' => true,
        'responsiveWrap' => false,
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => "$bul $thn", 'options' => ['colspan' => 7, 'class' => 'text-center', 'style' => 'font-size:10pt; background-color: #dc3545; color: #FFFFFF;']],
                ],
            ]
        ],
        'columns' => $grid,
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
                <?= $this->render('_search-pres-asn', ['model' => $searchModel, 'opd' => $opd]); ?>
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
JS;
$this->registerJs($script);
?>

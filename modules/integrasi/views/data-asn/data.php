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

<div class="preskin-asn-index">

    <?=  Html::a('<i class="fas fa-search"></i> Cari Data', '#', ['title' => 'Cari Data', 'class' => 'btn btn-primary mb-1', 'id' => 'search']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['style' => 'font-size:10pt'],
        'options' => [
            'class' => 'table-responsive',
            'style'=>'max-width:100%; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
        'showPageSummary' => false,
        'summary' => false,
        'striped' => true,
        'hover' => true,
        'responsiveWrap' => false,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'nip',
                'header' => 'ID<br>NIP<br>NAMA',
                'format' => 'raw',
                'options' => ['style' => 'width:20%'],
                'value' => function($dt){
                    return $dt['idpns'].'<br>'.$dt['nip'].'<br>'.$dt['fipNama'];
                }
            ],
            [
                'attribute' => 'nip',
                'header' => 'GOL<br>JABATAN',
                'format' => 'raw',
                'options' => ['style' => 'width:25%'],
                'value' => function($dt){
                    return $dt['fipGol'].'<br>'.$dt['fipJabatan'];
                }
            ],
            [
                'attribute' => 'status',
                'header' => 'STATUS<br>TMT STOP',
                'format' => 'raw',
                'options' => ['style' => 'width:15%'],
                'value' => function($dt){
                    if($dt['status'] == 0) $st = 0;
                    else $st = $dt['stapeg']['NMSTAPEG'];
                    return $st.'<br>'.$dt['tmt_stop'];
                }
            ],
            [
                'header' => 'UNOR',
                'value' => 'fipUnor',
                'options' => ['style' => 'width:30%'],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $dt) {
                        return $this->render('_grid-form', ['model' => $dt]);
                    },
                ],
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
                <?= $this->render('_search', ['model' => $searchModel, 'opd' => $opd, 'sta' => $sta]); ?>
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



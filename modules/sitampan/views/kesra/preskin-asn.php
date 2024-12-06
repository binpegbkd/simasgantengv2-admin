<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sitampan\models\PreskinAsnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data ASN';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preskin-asn-index">

<?=  Html::a('<i class="fas fa-search"></i> Cari Data', '#', ['title' => 'Cari Data', 'class' => 'btn btn-primary float-right mb-2', 'id' => 'search']); ?>
        
<?=  Html::button('<i class="fas fa-plus"></i> Tambah', ['value' => Url::to(['preskin-create']), 'title' => 'Tambah Preskin Asn', 'class' => 'showModalButton btn btn-success float-right mb-1 mr-1']); ?>

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
                'header' => 'NIP<br>NAMA<br>GOL',
                'format' => 'raw',
                'options' => ['style' => 'width:20%'],
                'value' => function($dt){
                    return $dt['nip'].'<br>'.$dt['fipNama'].'<br>'.$dt['fipGol'];
                }
            ],
            [
                'attribute' => 'nip',
                'header' => 'JABATAN<br>KELAS<br>JADWAL KERJA',
                'format' => 'raw',
                'options' => ['style' => 'width:25%'],
                'value' => function($dt){
                    if($dt['asnJadwalKerja'] === null) $jk = $dt['kode_jadwal'];
                    else $jk = $dt['asnJadwalKerja']['jenis'];
                    return $dt['fipJabatan'].'<br>'.$dt['asnKelas']['kelas'].'<br>'.$jk;
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
                'template' => '{update}',
                'buttons' => [
                    'update' => function ($url, $dt) {
                        return Html::button('<span class="fas fa-pencil-alt"></span>',[
                            'value' => Url::to(['preskin-update', 'id' => $dt['nip']]), 
                            'title' => 'Update', 'class' => 'showModalButton btn btn-link',
                        ]);
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
                <?= $this->render('_search-preskin', ['model' => $searchModel, 'opd' => $opd, 'sta' => $sta]); ?>
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

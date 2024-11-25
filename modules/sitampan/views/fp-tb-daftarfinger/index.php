<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\grid\GridView;
//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sitampan\models\FpTbDaftarfingerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar ASN Presensi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fp-tb-daftarfinger-index">

    <?=  Html::a('<i class="fas fa-search"></i> Cari Data', '#', ['title' => 'Cari Data', 'class' => 'btn btn-primary', 'id' => 'search']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['style' => 'font-size:10pt'],
        'options' => [
            'class' => 'table-responsive',
            'style'=>'max-width:100%; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
        'striped' => true,
        'hover' => true,
        'responsiveWrap' => false,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'nip',
            // 'nama',
            // 'device',
            [
                'header' => 'NIP<br>Nama<br>Device',
                'options' => ['style' => 'width: 20%'],
                'format' => 'raw',
                'value' => function($dt){
                    return $dt['nip'].'<br>'.$dt['nama'].'<br>'.$dt['device'];
                }
            ],
            [
                'attribute' => 'kodealamat',
                'format' => 'raw',
                'options' => ['style' => 'width: 30%'],
                //'value' => 'lokasi.alamat',
                'value' => function($dt){
                    return $dt['lokasi']['tablokb'].'<br>'.$dt['lokasi']['alamat'];
                }
                // 'filter' => Select2::widget([
                //     'attribute' => 'kodealamat',
                //     'model' => $searchModel,
                //     'data' => ArrayHelper::map($lokasi, 'kodealamat', 'alamat'),
                //     'pluginOptions' => ['allowClear' => true],
                //     'options' => ['placeholder' => ''],
                // ]),
            ],
            [
                'header' => 'Unit Kerja',
                'format' => 'raw',
                'options' => ['style' => 'width: 30%'],
                'value' => function($dt){
                    return $dt['fpTablokb'].'<br>'.$dt['fpUnor'];
                }
            ],
            //'password',
            //'kodealamat:integer',
            //'waktu',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{device} {lokasi} {password} ',
                'options' => ['style' => 'width: 20%'],
                'buttons' => [
                    'device' => function ($url) {
                        return Html::a('<span class="fas fa-calculator"></span>',$url,[ 
                            'title' => 'Reset Device/ HP', 'class' => 'btn btn-link device-reset',
                            'data' => ['method' => 'post'],
                        ]);
                    },
                    'lokasi' => function ($url) {
                        return Html::button('<span class="fas fa-map-marker-alt"></span>',['value' => Url::to($url), 
                            'title' => 'Update Lokasi', 'class' => 'showModalButton btn btn-link',
                        ]);
                    },
                    'password' => function ($url) {
                        return Html::a('<span class="fas fa-lock"></span>',$url,[ 
                            'title' => 'Reset Password', 'class' => 'btn btn-link password-reset',
                            'data' => ['method' => 'post'],
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
                <?= $this->render('_search', ['model' => $searchModel, 'lokasi' => $lokasi]); ?>
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

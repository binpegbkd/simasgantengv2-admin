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

    <?=  Html::a('<i class="fas fa-search"></i> Cari Data', '#', ['title' => 'Cari Data', 'class' => 'btn btn-primary float-right mb-2', 'id' => 'search']); ?>

    <?=  Html::button('<i class="fas fa-plus"></i> Tambah', ['value' => Url::to(['tambah']), 'title' => 'Tambah Data', 'class' => 'showModalButton btn btn-success float-right mb-2 mr-1']); ?>

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
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'header' => 'NIP<br>Nama<br>Device',
                'options' => ['style' => 'width: 15%'],
                'format' => 'raw',
                'value' => function($dt){
                    return $dt['nip'].'<br>'.$dt['nama'].'<br>'.$dt['device'];
                }
            ],
            [
                'attribute' => 'kodealamat',
                'format' => 'raw',
                'options' => ['style' => 'width: 25%'],
                //'value' => 'lokasi.alamat',
                'value' => function($dt){
                    return $dt['lokasi']['tablokb'].'<br>'.$dt['lokasi']['alamat'];
                }
            ],
            [
                'header' => 'Unit Kerja',
                'format' => 'raw',
                'options' => ['style' => 'width: 25%'],
                'value' => function($dt){
                    return $dt['fpTablokb'].'<br>'.$dt['fpUnor'];
                }
            ],
            [
                'header' => 'Simasganteng Password',
                'format' => 'raw',
                'options' => ['style' => 'width: 25%'],
                'value' => function($dt){
                    $user = \app\models\User::findByNip($dt['nip'], $_SESSION['iduser']);
                    if($user === null) return 'User simasganteng tidak ditemukan';
                    else{
                    if($user['flag'] == 0) $pass = 'Default';
                        else $pass = 'Update';
                        return $pass.' per '.date('d-m-Y H:i:s', strtotime($user['modified'])).
                            '<br>By : '.$user['updateby'];
                    }
                }
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{device} {password} {simasganteng} ',
                'options' => ['style' => 'width: 10%'],
                'header' => 'Reset',
                'buttons' => [
                    'device' => function ($url) {
                        return Html::a('Device/ HP',$url,[ 
                            'title' => 'Reset Device/ HP', 'class' => 'btn-xs btn-primary device-reset',
                            'data' => ['method' => 'post'],
                        ]).'<br>';
                    },
                    'password' => function ($url) {
                        return Html::a('Password APK',$url,[ 
                            'title' => 'Reset Password APK', 'class' => 'btn-xs btn-warning password-reset',
                            'data' => ['method' => 'post'],
                        ]).'<br>';
                    },
                    'simasganteng' => function ($url) {
                        return Html::a('Simasganteng',$url,[ 
                            'title' => 'Reset Password Simasganteng', 'class' => 'btn-xs btn-danger password-reset',
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
                <?= $this->render('_search-reset', ['model' => $searchModel, 'lokasi' => $lokasi]); ?>
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

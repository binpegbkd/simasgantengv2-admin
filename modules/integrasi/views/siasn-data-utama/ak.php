<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnDataUtama */

$this->title = strtoupper(Yii::$app->controller->action->id);
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="siasn-data-utama-view">

    <div class="mb-3">
        <?= $this->render('../layout/menu.php', ['nip' => $model['nipBaru']]) ?>
    </div>
    
    <?= DetailView::widget([
        'template' => "<tr><th style='width:30%;'>{label}</th><td>{value}</td></tr>",
        'options' => ['class' => 'table table-striped', 'style' => 'font-size:10pt;'],
        'model' => $model,
        'attributes' => [
            'nipBaru',
            'nama',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
        'tableOptions' => ['class' => 'table table-hover', 'style' => 'font-size:10pt;'],
        'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'namaJabatan',
            'nomorSk',
            'tanggalSk',
            'bulanSelesaiPenailan',
            'tahunSelesaiPenailan',
            [
                'class' => 'kartik\grid\ActionColumn',
                'width' => '15%',
                'template' => '{view} {upload} {file} {hapus}',
                'buttons' => [
                    'hapus' => function ($url, $model){
                        return Html::a('<span class="fas fa-trash-alt"></span>',
                            Url::to(['hapus-ak', 'id' => $model['id']]), 
                            [
                                'title' => 'Hapus', 'class' => 'btn-sm btn-link btn-sm tombol-hapus',
                                'data-method' => 'post'
                        ]);                            
                    },
                    'upload' => function ($url, $model){
                        return Html::button('<span class="fas fa-file-upload"></span>',[
                            'value' => Url::to(['/upload-dok-rw', 'id' => $model['id'], 'nip' => 879]), 
                            'title' => 'Upload Dokumen', 'class' => 'showModalButton btn btn-link btn-sm', 
                        ]);                            
                    },
                    'file' => function ($url, $model){
                        $tahun = $model['tanggalSk'];
                        $file = 'PAK_'.$tahun.'_'.$model['pns'].'.pdf';
                        $dok = 'efi/'.$model['pns'].'/'.$file;
                        if(!file_exists($dok)){    
                            return Html::a('<span class="fas fa-file-alt"></span>',
                                Url::to(['/siasn-get-dok', 'id' => $file, 'nip' => $model['pns']]),
                               ['title' => 'Download SK', 'class' => 'btn btn-danger btn-sm']);                    
                        }else{
                            return Html::button('<span class="fas fa-file-alt"></span>',[
                                'value' => Url::to(['view-dok', 'id' => $dok]), 
                                'title' => 'Preview SK', 'class' => 'showModalButton btn btn-link btn-sm', 
                            ]);  
                        }          
                    },
                    'view' => function ($url, $model){
                        return Html::button('<span class="fas fa-eye"></span>',[
                            'value' => Url::to(['view-ak', 'id' => $model['id']]), 
                            'title' => 'Detail', 'class' => 'showModalButton btn btn-link btn-sm', 
                        ]);                            
                    },
                ],
            ],
        ]
    ]); ?>

</div>
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
            // 'nipLama',
            'nama',
            'unorNama',
            // 'unorIndukId',
            'unorIndukNama',
            // 'jenisJabatanId',
            'jenisJabatan',
            'jabatanNama',
            'tmtJabatan',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'tableOptions' => ['style' => 'font-size:10pt;'],
        'options' => [
            'class' => 'table-hover table-reponsive',
            'style'=>'overflow: auto; word-wrap: break-word;'
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'header' => 'Jabatan<br>Unor - OPD',
                'attribute' => 'namaJabatan',
                'format' => 'raw',
                'value' => function($dt){
                    if($dt['namaJabatan'] != '') $ja = $dt['namaJabatan'];
                    elseif($dt['jabatanFungsionalNama'] != '') $ja = $dt['jabatanFungsionalNama'];
                    elseif($dt['jabatanFungsionalUmumNama'] != '') $ja = $dt['jabatanFungsionalUmumNama'];
                    else $ja = '';

                    if($dt['unorNama'] !== null) $uno = '<br>'.$dt['unorNama']; else $uno = '';
                    
                    if($dt['unorIndukNama'] !== null) $induk = '<br>'.$dt['unorIndukNama']; else $induk = '';
                    
                    return $ja.$uno.$induk;
                }
            ],
            [
                'header' => 'No. SK<br>Tanggal SK<br>TMT',
                'attribute' => 'nomorSk',
                'format' => 'raw',
                'value' => function($dt){
                    if($dt['nomorSk'] !== null) $nosk = $dt['nomorSk']; else $nosk = '';
                    if($dt['tanggalSk'] !== null) $tglsk = '<br>'.$dt['tanggalSk']; else $tglsk = '';
                    if($dt['tmtJabatan'] !== null) $tmtjab = '<br>'. \app\models\Fungsi::tgldmy($dt['tmtJabatan']); else $tmtjab = '';
                    return $nosk.$tglsk.$tmtjab;
                }
            ],
            
            [
                'class' => 'kartik\grid\ActionColumn',
                'width' => '15%',
                'template' => '{view} {upload} {file} {hapus}',
                'buttons' => [
                    'hapus' => function ($url, $model){
                        return Html::a('<span class="fas fa-trash-alt"></span>',
                            ['hapus-jab', 'id' => $model['id']], [
                            'title' => 'Hapus', 'class' => 'btn-sm btn-link btn-sm tombol-hapus',
                            'data-method' => 'post'
                        ]);                            
                    },
                    'upload' => function ($url, $model){                        
                        return Html::button('<span class="fas fa-file-upload"></span>',[
                            'value' => Url::to(['/upload-dok-rw', 'id' => $model['id'], 'nip' => $model['idPns'], 'id_dok' => 872]), 
                            'title' => 'Upload Dokumen', 'class' => 'showModalButton btn btn-link btn-sm', 
                        ]);                            
                    },
                    'file' => function ($url, $model){
                        $tahun = substr($model['tmtJabatan'],6,4);
                        $file = 'SK_JABATAN_'.$tahun.'_'.$model['nipBaru'].'.pdf';
                        $dok = 'efi/'.$model['idPns'].'/'.$file;
                        if(!file_exists($dok)){    
                            return Html::a('<span class="fas fa-file-alt"></span>',
                                Url::to(['/siasn-get-dok', 'id' => $file, 'nip' => $model['idPns']]),
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
                            'value' => Url::to(['view-jab', 'id' => $model['id']]), 
                            'title' => 'Detail', 'class' => 'showModalButton btn btn-link btn-sm', 
                        ]);                            
                    },
                ],
            ],
        ],
    ]) ?>

</div>

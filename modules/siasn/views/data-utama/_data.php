<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

?>
<div class="data-pns-index">

<?php 
$gridColumns = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'header' => 'NO',
    ],
    [
        'attribute' => 'nipBaru',
        'label' => "NIP",
    ],
    [
        'attribute' => 'nama',
        'label' => 'NAMA',
        'value' => function($data){
            if($data['nama'] === null) return '';
            else return $data['namaPegawai'];
        },
    ],
    [
        'attribute' => 'kedudukanPnsNama',
        'label' => 'KEDUDUKAN',
    ],
    [
        'attribute' => 'golRuangAkhir',
        'label' => 'GOL',
    ],
    [
        'attribute' => 'unorIndukNama',
        'label' => 'ORGANISASI INDUK',
    ],
    [  
    'attribute' => 'unorNama',
    'label' => 'UNIT ORGANISASI',
    ],
    [
        'attribute' => 'jabatanNama',
        'label' => 'JABATAN',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view}',
        'options' => ['style' => 'width: 5%'],
          'buttons' => [
            'view' => function ($url, $dt) {
                return $this->render('_grid-form', ['model' => $dt]);
            },
        ],
    ],
];

echo GridView::widget([
    'dataProvider' => $data,
    'headerRowOptions' => ['style' => 'font-size:10pt;'],
    'columns' =>  $gridColumns,
    'summary' => '',
    'tableOptions' => ['style' => 'font-size:10pt'],
    'options' => [
        'style'=>'min-width: 100%; max-width:100%; min-height:100px; overflow: auto; word-wrap: break-word;'
    ],
    'striped' => false,
    'hover' => true,
    'responsiveWrap' => false,
]); 
?>

</div>
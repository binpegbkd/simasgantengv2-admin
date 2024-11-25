<?php

/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\helpers\Url;
use \yii\bootstrap4\Modal;

$this->title = 'Data Pegawai';
\app\assets\AppAsset::register($this);

?>
<div class="site-index">
    
    <div class="row">
        <div class="mr-auto">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="ml-auto">
            <?=  Html::a('<i class="fas fa-search"></i> Cari Data', '#', [
                'title' => 'Cari Data', 
                'class' => 'btn btn-primary mb-3', 
                'id' => 'search',
            ]); ?>
            <?= Html::submitButton('<i class="fas fa-plus-circle"></i> Tambah Data', [
                'value' => Url::to(['/tambah-data-utama']),
                'title' => 'Tambah Data Pegawai', 
                'class' => 'showModalButton btn btn-success mb-3',
                'id'=>'tambah-data-utama',
            ]);?>
        </div>
    </div>
    <div class="row">        
        <?= $this->render('_data', [
            'data' => $dataProvider,
        ]) ?>        
    </div>
</div>
<div id="cari-block" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
            <h5 class="modal-title">Cari Data</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
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
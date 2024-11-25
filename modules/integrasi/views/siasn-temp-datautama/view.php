<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempDatautama */

$this->title = $model->pns_orang_id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Datautamas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="siasn-temp-datautama-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pns_orang_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pns_orang_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'agama_id',
            'alamat',
            'email:email',
            'email_gov:email',
            'kabupaten_id',
            'karis_karsu',
            'kelas_jabatan',
            'kpkn_id',
            'lokasi_kerja_id',
            'nomor_bpjs',
            'nomor_hp',
            'nomor_telpon',
            'npwp_nomor',
            'npwp_tanggal:date',
            'pns_orang_id',
            'tanggal_taspen:date',
            'tapera_nomor',
            'taspen_nomor',
            'flag',
            'updated',
            'by',
        ],
    ]) ?>

</div>

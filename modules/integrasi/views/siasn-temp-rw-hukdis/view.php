<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnTempRwHukdis */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Siasn Temp Rw Hukdis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="siasn-temp-rw-hukdis-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'akhirHukumanTanggal:date',
            'alasanHukumanDisiplinId',
            'golonganId',
            'golonganLama',
            'hukdisYangDiberhentikanId',
            'hukumanTanggal:date',
            'id',
            'jenisHukumanId',
            'jenisTingkatHukumanId',
            'kedudukanHukumId',
            'keterangan',
            'masaBulan',
            'masaTahun',
            'nomorPp',
            'pnsOrangId',
            'skNomor',
            'skPembatalanNomor',
            'skPembatalanTanggal:date',
            'skTanggal:date',
            'flag',
            'updated',
            'by',
        ],
    ]) ?>

</div>

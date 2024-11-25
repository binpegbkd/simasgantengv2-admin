<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\FpTbDaftarfinger */

$this->title = 'Ubah Data Fp Tb Daftarfinger: ' . $model->nip;
$this->params['breadcrumbs'][] = ['label' => 'Fp Tb Daftarfingers', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fp-tb-daftarfinger-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

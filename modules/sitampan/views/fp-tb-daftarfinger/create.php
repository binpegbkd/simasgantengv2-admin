<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\FpTbDaftarfinger */

$this->title = 'Tambah Data Fp Tb Daftarfinger';
$this->params['breadcrumbs'][] = ['label' => 'Fp Tb Daftarfingers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fp-tb-daftarfinger-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

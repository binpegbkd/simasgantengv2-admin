<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\FpTbAlamat2 */

$this->title = 'Tambah Data Fp Tb Alamat2';
$this->params['breadcrumbs'][] = ['label' => 'Fp Tb Alamat2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fp-tb-alamat2-create">

    <?= $this->render('_form', [
        'model' => $model, 'opd' => $opd,
    ]) ?>

</div>

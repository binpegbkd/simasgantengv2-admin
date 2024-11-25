<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\FpTbAlamat2 */

$this->title = 'Ubah Data Fp Tb Alamat2: ' . $model->kodealamat;
$this->params['breadcrumbs'][] = ['label' => 'Fp Tb Alamat2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fp-tb-alamat2-update">

    <?= $this->render('_form', [
        'model' => $model, 'opd' => $opd,
    ]) ?>

</div>

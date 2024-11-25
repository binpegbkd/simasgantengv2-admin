<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sitampan\models\FpTbAlamat2 */

$this->title = $model->kodealamat;
$this->params['breadcrumbs'][] = ['label' => 'Fp Tb Alamat2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fp-tb-alamat2-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->kodealamat], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->kodealamat], [
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
            'kodealamat:integer',
            'alamat',
            'latitude',
            'longitude',
        ],
    ]) ?>

</div>

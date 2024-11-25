<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\siasn\models\SiasnRefUnorSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Siasn Ref Unors';

?>
<div class="siasn-ref-unor-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        //'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'dataProvider' => $dta,
        'columns' => [
            //['class' => 'kartik\grid\SerialColumn'],
            'NO',
            //'ID',
            'HIERARKI',
            //'NAMA',
            'ESELON-ID',
            //'NAMA-JABATAN',
            //'UNOR-ATASAN-ID',
            'UNOR-ATASAN',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}'
            ],

            //'updated',
            /*[
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SiasnRefUnor $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],*/
        ],
    ]); ?>


</div>

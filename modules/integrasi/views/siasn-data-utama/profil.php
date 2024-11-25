<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\integrasi\models\SiasnDataUtama */

$this->title = strtoupper(Yii::$app->controller->action->id);
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="siasn-data-utama-view">

    <div class="mb-3">
        <?= $this->render('../layout/menu.php', ['nip' => $model['nipBaru']]) ?>
    </div>

    <div class="row">
        <div class="col-lg-4 mt-4" style="margin:auto;">
            <?php 
            $path = 'efi/'.$model['id'].'/FOTO_'.$model['nipBaru'].'.jpg';
           
            if(file_exists($path)) echo "<div class='img'><img src=".$path." alt='User Image' width='230px' height='300px' class='img2'></div>" ;
            else echo '<img src="'.Yii::getAlias('@web/administrator.png').'" alt="User Image" width="250px" height="280px" class="img-circle"/>';
            ?>
            
        </div>
        <div class="col-lg-8">
            <?= DetailView::widget([
                'template' => "<tr><th style='width:30%;'>{label}</th><td>{value}</td></tr>",
                'options' => ['class' => 'table table-striped', 'style' => 'font-size:10pt;'],
                'model' => $model,
                'attributes' => [
                    'nipBaru',
                    'nama',
                    'gelarDepan',
                    'gelarBelakang',
                    'tempatLahir',
                    'tglLahir',
                    'jenisKelamin',
                    'alamat',
                ],
            ]) ?>
        </div>
    </div>
</div>

<?php
$css = <<< CSS
.img {
    background:  #94fcc3 ;
    position: relative;
    text-align: center;
    transform: rotate(10deg);
}

.img:before{
    position: relative;
    text-align: center;
    transform: rotate(20deg);
}  

.img2{
      position: relative;
      text-align: center;
      transform: rotate(-10deg);
      /*border-radius: 5px;*/
    }  
    
CSS;
$this->registerCss($css);
?>
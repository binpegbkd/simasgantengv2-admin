<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

$this->title = 'Response WS SIASN';

?>
<div class="site-error">
    <br> 
    <?= $data = Json::encode($result, $asArray = true); ?>

</div>
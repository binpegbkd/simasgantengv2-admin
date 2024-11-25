<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Json;

?>
<div class="site-error">
<div class="row">
    <div class="col-lg-12">
    <?= '<pre>'.Json::encode($model, JSON_PRETTY_PRINT).'</pre>'; ?>
    </div>
</div>
</div>
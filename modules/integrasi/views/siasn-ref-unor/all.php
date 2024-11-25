<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;

$this->title = 'Data Unor';

?>
<div class="siasn-ref-unor-index">

    <div class="row">
        <div class="mr-auto">
            <?= $this->render('_search-all', ['model' => $searchModel]); ?>
        </div>
        <div class="ml-auto">
            <?= Html::a('<i class="fas fa-angle-double-left"></i> Kembali',Url::previous(), ['class' => 'btn btn-primary', 'title' => 'Kembali']); ?>
        </div>
    </div>
    <br>
    <table id="example1" class="table table-bordered table-hover" style="font-size: 10pt" width="100%">
        <tr>
            <th>NO</th>
            <th>HIERARKI</th>
            <!--<th>NAMA</th>-->
            <th>ESELON ID</th>
            <!--<th>NAMA JABATAN</th>-->
            <!--<th>UNOR ATASAN ID</th>-->
            <th>UNOR ATASAN</th>
            <th>ID</th>
            <th>UNOR SIMPEG</th>
            <th>AKTIF</th>
        </tr>
    <?php 
    foreach($dta as $dt){        
        echo "<tr>
            <td>".$dt['NO']."</td>
            <td>".$dt['HIERARKI']."</td>
            <!--<td>".$dt['NAMA']."</td>-->
            <td>".$dt['ESELON-ID']."</td>
            <!--<td>".$dt['NAMA-JABATAN']."</td>-->
            <!--<td>".$dt['UNOR-ATASAN-ID']."</td>-->
            <td>".$dt['UNOR-ATASAN']."</td>
            <td>".$dt['ID']."</td>
            <td><div class='row'>"
                .Html::beginForm(['update', 'id' => $dt['ID']], 'post')
                .Html::hiddenInput('aktif', $dt['AKTIF']) 
                .Html::textInput('simpeg', $dt['UNOR-SIMPEG'], 
                ['maxlength' => '10', 'size' => '5px', 'class' => 'form-control', 'onchange'=>'this.form.submit()'])
                .Html::endForm()
            ."</div></td>
            <td><div class='row'>"
                .Html::beginForm(['update', 'id' => $dt['ID']], 'post')
                .Html::hiddenInput('simpeg', $dt['UNOR-SIMPEG'])
                .Html::textInput('aktif', $dt['AKTIF'], 
                ['maxlength' => '1', 'size' => '3px', 'class' => 'form-control', 'onchange'=>'this.form.submit()'])
                .Html::endForm()
            ."</div></td>
        </tr>";
    }
    ?>
    </table>
</div>


<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;

$this->title = 'Data Unor';

?>
<div class="siasn-ref-unor-index">

    <div class="row">
        <div class="col-md-8 text-left">
            <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div>
        <div class="col-md-4 text-right">
            <?= Html::a('<span class="fas fa-pencil-alt"></span>',Url::to(['all-unor']),['class' => 'btn btn-primary', 'id' => 'all-unor']);?>
            
            <?= Html::a('<span class="fas fa-download"></span>',['download'],['class' => 'btn btn-success', 'id' => 'download-unor']) ?>
        </div>
    </div>
    <br>
    <table id="example1" class="table table-bordered table-striped" style="font-size: 10pt" width="100%">
        <tr>
            <th>NO</th>
            <!--<th>ID</th>-->
            <th>HIERARKI</th>
            <!--<th>NAMA</th>-->
            <th>ESELON ID</th>
            <!--<th>NAMA JABATAN</th>-->
            <!--<th>UNOR ATASAN ID</th>-->
            <th>UNOR ATASAN</th>
            <th>UNOR SIMPEG</th>
        </tr>
    <?php 
    foreach($dta as $dt){        
        echo "<tr>
            <td>".$dt['NO']."</td>
            <!--<td>".$dt['ID']."</td>-->
            <td>".$dt['HIERARKI']."</td>
            <!--<td>".$dt['NAMA']."</td>-->
            <td>".$dt['ESELON-ID']."</td>
            <!--<td>".$dt['NAMA-JABATAN']."</td>-->
            <!--<td>".$dt['UNOR-ATASAN-ID']."</td>-->
            <td>".$dt['UNOR-ATASAN']."</td>
            <td>".$dt['UNOR-SIMPEG']."</div></td>
        </tr>";
    }
    ?>
    </table>
</div>
<?php
$urlData = Url::to(['/download-unor']);
$script = <<< JS
$('#download-unor').click(function(e){
    e.preventDefault();
	Swal.fire({
    title: "Yakin Mendownload?",
    text: "Hasil download akan mengupdate data lama!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, download",
    cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            //return true;    
            $.get("{$urlData}");
        }
    });
});
JS;
$this->registerJs($script);
?>


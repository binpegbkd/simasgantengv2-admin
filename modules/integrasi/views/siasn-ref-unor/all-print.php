<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;

$this->title = 'Data Unor';

?>
<div class="siasn-ref-unor-index">

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
            <td>".$dt['UNOR-SIMPEG']."</div></td>
            <td>".$dt['AKTIF']."</div></td>
        </tr>";
    }
    ?>
    </table>
</div>


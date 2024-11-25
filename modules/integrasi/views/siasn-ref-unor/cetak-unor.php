<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;

$this->title = 'Data Unor';

?>
<div class="siasn-ref-unor-index">

    <table id="example1" style="font-size: 10pt" border="1" width="100%" cellpadding="5" cellspacing="0" borderColor="#000">
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>ESELON ID</th>
                <th>UNOR ATASAN</th>
            </tr>
        </thead>
    <?php 
    foreach($dta as $dt){        
        echo "<tr>
            <td>".$dt['NO']."</td>
            <td>".$dt['HIERARKI']."</td>
            <td>".$dt['ESELON-ID']."</td>
            <td>".$dt['UNOR-ATASAN']."</td>
        </tr>";
    }
    ?>
    </table>
</div>


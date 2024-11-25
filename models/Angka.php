<?php
namespace app\models;

use Yii;
use yii\base\Model;


class Angka extends Model {    
    
    public static function Ribuan($angka)
    {
        $ribuan = number_format($angka,0,",",".");

        return $ribuan;
    }
}
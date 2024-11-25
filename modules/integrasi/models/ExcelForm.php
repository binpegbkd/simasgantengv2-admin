<?php

namespace app\modules\integrasi\models;

use Yii;
use yii\base\Model;

class ExcelForm extends Model{
    public $file;
    
    public function rules(){
        return [
            [['file'],'required'],
            [['file'],'file','extensions'=>'xls, xlsx','maxSize'=>1024 * 1024],
        ];
    }
    
    public function attributeLabels(){
        return [
            'file'=>'Pilih File',
        ];
    }
}
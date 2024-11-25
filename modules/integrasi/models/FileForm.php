<?php

namespace app\modules\integrasi\models;

use Yii;
use yii\base\Model;

class FileForm extends Model{
    
    public $file;
    
    public function rules(){
        return [
            [['file'],'required'],
            [['file'],'file','extensions'=>'pdf','maxSize'=>1024 * 1024],
        ];
    }
    
    public function attributeLabels(){
        return [
            'file'=>'Pilih File',
        ];
    }
}
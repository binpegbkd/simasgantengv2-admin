<?php

namespace app\modules\sitampan\models;

use Yii;
use app\modules\simpeg\models\EpsMastfip;

/**
 * This is the model class for table "tb_daftarfinger".
 *
 * @property string $nip
 * @property string|null $password
 * @property string|null $nama
 * @property int|null $kodealamat
 * @property string $waktu
 * @property string|null $device
 */
class FpTbDaftarfinger extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_daftarfinger';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db7');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nip'], 'required'],
            [['kodealamat'], 'default', 'value' => 0],
            [['kodealamat'], 'integer'],
            [['waktu'], 'safe'],
            [['nip', 'device'], 'string', 'max' => 50],
            [['password', 'nama'], 'string', 'max' => 100],
            [['nip'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nip' => 'NIP',
            'password' => 'Password',
            'nama' => 'Nama',
            'kodealamat' => 'Lokasi',
            'waktu' => 'Waktu',
            'device' => 'Device',
        ];
    }

    public function getLokasi()    
    {  
        return $this->hasOne(FpTbAlamat2::className(), ['kodealamat' => 'kodealamat']);  

    }

    public function getFpFip()  
    {  
        return $this->hasOne(EpsMastfip::className(), ['B_02' => 'nip']);  
    }

    public function getFpUnor()  
    {  
        if($this->fpFip === null) return '-';
        else return $this->fpFip['unorDetail'];  
    }

    public function getFpTablokb()  
    {  
        if($this->fpFip === null) return '-';
        else return $this->fpFip['unorUnit'];  ;  
    }
}

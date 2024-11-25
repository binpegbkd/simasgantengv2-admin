<?php

namespace app\modules\sitampan\models;

use Yii;

/**
 * This is the model class for table "log_aktivitas".
 *
 * @property string $kode
 * @property string $nip
 * @property string $tgl
 * @property int $aktivitas
 */
class FpLogAktivitas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_aktivitas';
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
            [['kode', 'nip'], 'required'],
            [['tgl'], 'safe'],
            [['aktivitas'], 'default', 'value' => null],
            [['aktivitas'], 'integer'],
            [['kode'], 'string', 'max' => 100],
            [['nip'], 'string', 'max' => 21],
            [['kode'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'nip' => 'Nip',
            'tgl' => 'Tgl',
            'aktivitas' => 'Aktivitas',
        ];
    }

    public function getLogAktivitas()    
    {  
        return $this->hasOne(FpAktivitas::className(), ['kode' => 'aktivitas']);  

    }

    public function getResetDevice()    
    {  
        $log = $this::find()
        ->select(['nip'])
        ->where(['nip' => $this->nip, 'aktivitas' => 3])
        ->count();

        return $log;  
    }
}

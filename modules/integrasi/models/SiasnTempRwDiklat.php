<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_temp_rw_diklat".
 *
 * @property int|null $bobot
 * @property string|null $id
 * @property string|null $institusiPenyelenggara
 * @property string|null $jenisKompetensi
 * @property int|null $jumlahJam
 * @property string|null $latihanStrukturalId
 * @property string|null $nomor
 * @property string|null $pnsOrangId
 * @property int|null $tahun
 * @property string|null $tanggal
 * @property string|null $tanggalSelesai
 * @property int|null $flag
 * @property string|null $updated
 * @property string|null $by
 */
class SiasnTempRwDiklat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_temp_rw_diklat';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bobot', 'jumlahJam', 'tahun'], 'default', 'value' => 0],
            [['bobot', 'jumlahJam', 'tahun'], 'integer'],
            [['tanggal', 'tanggalSelesai'], 'safe'],
            [['id', 'latihanStrukturalId', 'pnsOrangId'], 'string', 'max' => 128],
            [['institusiPenyelenggara'], 'string', 'max' => 150],
            [['jenisKompetensi'], 'string', 'max' => 10],
            [['nomor'], 'string', 'max' => 100],
            // [['by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bobot' => 'Bobot',
            'id' => 'ID',
            'institusiPenyelenggara' => 'Institusi Penyelenggara',
            'jenisKompetensi' => 'Jenis Kompetensi',
            'jumlahJam' => 'Jumlah Jam',
            'latihanStrukturalId' => 'Latihan Struktural ID',
            'nomor' => 'Nomor',
            'pnsOrangId' => 'Pns Orang ID',
            'tahun' => 'Tahun',
            'tanggal' => 'Tanggal',
            'tanggalSelesai' => 'Tanggal Selesai',
            // 'flag' => 'Flag',
            // 'updated' => 'Updated',
            // 'by' => 'By',
        ];
    }

    // public static function primaryKey()
    // {
    //     return ["id"];
    // }
}

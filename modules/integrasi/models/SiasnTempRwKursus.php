<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_temp_rw_kursus".
 *
 * @property string|null $id
 * @property string|null $instansiId
 * @property string|null $institusiPenyelenggara
 * @property string|null $jenisDiklatId
 * @property string|null $jenisKursus
 * @property string|null $jenisKursusSertipikat
 * @property int|null $jumlahJam
 * @property string|null $lokasiId
 * @property string|null $namaKursus
 * @property string|null $nomorSertipikat
 * @property string|null $pnsOrangId
 * @property int|null $tahunKursus
 * @property string|null $tanggalKursus
 * @property string|null $tanggalSelesaiKursus
 * @property int|null $flag
 * @property string|null $updated
 * @property string|null $by
 */
class SiasnTempRwKursus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_temp_rw_kursus';
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
            [['jumlahJam', 'tahunKursus'], 'default', 'value' => 0],
            [['jumlahJam', 'tahunKursus'], 'integer'],
            [['tanggalKursus', 'tanggalSelesaiKursus'], 'safe'],
            [['id', 'instansiId', 'jenisDiklatId', 'jenisKursus', 'jenisKursusSertipikat', 'lokasiId', 'pnsOrangId'], 'string', 'max' => 128],
            [['institusiPenyelenggara', 'namaKursus', 'nomorSertipikat'], 'string', 'max' => 100],
            // [['by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'instansiId' => 'Instansi ID',
            'institusiPenyelenggara' => 'Institusi Penyelenggara',
            'jenisDiklatId' => 'Jenis Diklat ID',
            'jenisKursus' => 'Jenis Kursus',
            'jenisKursusSertipikat' => 'Jenis Kursus Sertipikat',
            'jumlahJam' => 'Jumlah Jam',
            'lokasiId' => 'Lokasi ID',
            'namaKursus' => 'Nama Kursus',
            'nomorSertipikat' => 'Nomor Sertipikat',
            'pnsOrangId' => 'Pns Orang ID',
            'tahunKursus' => 'Tahun Kursus',
            'tanggalKursus' => 'Tanggal Kursus',
            'tanggalSelesaiKursus' => 'Tanggal Selesai Kursus',
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

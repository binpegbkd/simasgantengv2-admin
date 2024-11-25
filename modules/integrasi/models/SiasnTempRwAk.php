<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_temp_rw_ak".
 *
 * @property int|null $bulanMulaiPenailan
 * @property int|null $bulanSelesaiPenailan
 * @property string|null $id
 * @property int|null $isAngkaKreditPertama
 * @property int|null $isIntegrasi
 * @property int|null $isKonversi
 * @property float|null $kreditBaruTotal
 * @property float|null $kreditPenunjangBaru
 * @property float|null $kreditUtamaBaru
 * @property string|null $nomorSk
 * @property string|null $pnsId
 * @property string|null $rwJabatanId
 * @property int|null $tahunMulaiPenailan
 * @property int|null $tahunSelesaiPenailan
 * @property string|null $tanggalSk
 * @property int|null $flag
 * @property string|null $updated
 * @property string|null $by
 */
class SiasnTempRwAk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_temp_rw_ak';
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
            [['bulanMulaiPenailan', 'bulanSelesaiPenailan', 'isAngkaKreditPertama', 'isIntegrasi', 'isKonversi', 'tahunMulaiPenailan', 'tahunSelesaiPenailan'], 'default', 'value' => 0],
            [['bulanMulaiPenailan', 'bulanSelesaiPenailan', 'isAngkaKreditPertama', 'isIntegrasi', 'isKonversi', 'tahunMulaiPenailan', 'tahunSelesaiPenailan'], 'integer'],
            [['kreditBaruTotal', 'kreditPenunjangBaru', 'kreditUtamaBaru'], 'number'],
            [['tanggalSk'], 'safe'],
            [['id', 'pnsId', 'rwJabatanId'], 'string', 'max' => 128],
            [['nomorSk'], 'string', 'max' => 100],
            // [['by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bulanMulaiPenailan' => 'Bulan Mulai Penailan',
            'bulanSelesaiPenailan' => 'Bulan Selesai Penailan',
            'id' => 'ID',
            'isAngkaKreditPertama' => 'Is Angka Kredit Pertama',
            'isIntegrasi' => 'Is Integrasi',
            'isKonversi' => 'Is Konversi',
            'kreditBaruTotal' => 'Kredit Baru Total',
            'kreditPenunjangBaru' => 'Kredit Penunjang Baru',
            'kreditUtamaBaru' => 'Kredit Utama Baru',
            'nomorSk' => 'Nomor Sk',
            'pnsId' => 'Pns ID',
            'rwJabatanId' => 'Rw Jabatan ID',
            'tahunMulaiPenailan' => 'Tahun Mulai Penailan',
            'tahunSelesaiPenailan' => 'Tahun Selesai Penailan',
            'tanggalSk' => 'Tanggal Sk',
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

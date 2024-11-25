<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_temp_rw_hukdis".
 *
 * @property string|null $akhirHukumanTanggal
 * @property string|null $alasanHukumanDisiplinId
 * @property string|null $golonganId
 * @property string|null $golonganLama
 * @property string|null $hukdisYangDiberhentikanId
 * @property string|null $hukumanTanggal
 * @property string|null $id
 * @property string|null $jenisHukumanId
 * @property string|null $jenisTingkatHukumanId
 * @property string|null $kedudukanHukumId
 * @property string|null $keterangan
 * @property int|null $masaBulan
 * @property int|null $masaTahun
 * @property string|null $nomorPp
 * @property string|null $pnsOrangId
 * @property string|null $skNomor
 * @property string|null $skPembatalanNomor
 * @property string|null $skPembatalanTanggal
 * @property string|null $skTanggal
 * @property int|null $flag
 * @property string|null $updated
 * @property string|null $by
 */
class SiasnTempRwHukdis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_temp_rw_hukdis';
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
            [['akhirHukumanTanggal', 'hukumanTanggal', 'skPembatalanTanggal', 'skTanggal'], 'safe'],
            [['masaBulan', 'masaTahun'], 'default', 'value' => 0],
            [['masaBulan', 'masaTahun'], 'integer'],
            [['alasanHukumanDisiplinId', 'keterangan'], 'string', 'max' => 255],
            [['golonganId', 'golonganLama', 'kedudukanHukumId'], 'string', 'max' => 2],
            [['hukdisYangDiberhentikanId', 'id', 'jenisHukumanId', 'jenisTingkatHukumanId', 'pnsOrangId'], 'string', 'max' => 128],
            [['nomorPp', 'skNomor', 'skPembatalanNomor'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'akhirHukumanTanggal' => 'Akhir Hukuman Tanggal',
            'alasanHukumanDisiplinId' => 'Alasan Hukuman Disiplin ID',
            'golonganId' => 'Golongan ID',
            'golonganLama' => 'Golongan Lama',
            'hukdisYangDiberhentikanId' => 'Hukdis Yang Diberhentikan ID',
            'hukumanTanggal' => 'Hukuman Tanggal',
            'id' => 'ID',
            'jenisHukumanId' => 'Jenis Hukuman ID',
            'jenisTingkatHukumanId' => 'Jenis Tingkat Hukuman ID',
            'kedudukanHukumId' => 'Kedudukan Hukum ID',
            'keterangan' => 'Keterangan',
            'masaBulan' => 'Masa Bulan',
            'masaTahun' => 'Masa Tahun',
            'nomorPp' => 'Nomor Pp',
            'pnsOrangId' => 'Pns Orang ID',
            'skNomor' => 'Sk Nomor',
            'skPembatalanNomor' => 'Sk Pembatalan Nomor',
            'skPembatalanTanggal' => 'Sk Pembatalan Tanggal',
            'skTanggal' => 'Sk Tanggal',
        //     'flag' => 'Flag',
        //     'updated' => 'Updated',
        //     'by' => 'By',
        ];
    }

    // public static function primaryKey()
    // {
    //     return ["id"];
    // }
}

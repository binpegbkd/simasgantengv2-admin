<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_temp_rw_skp21".
 *
 * @property string|null $atasanPejabatPenilai
 * @property string|null $atasanPenilaiGolongan
 * @property string|null $atasanPenilaiJabatan
 * @property string|null $atasanPenilaiNama
 * @property string|null $atasanPenilaiNipNrp
 * @property string|null $atasanPenilaiTmtGolongan
 * @property string|null $atasanPenilaiUnorNama
 * @property float|null $disiplin
 * @property string|null $id
 * @property float|null $inisiatifKerja
 * @property float|null $integritas
 * @property string|null $jenisJabatan
 * @property string|null $jenisPeraturanKinerjaKd
 * @property float|null $jumlah
 * @property float|null $kepemimpinan
 * @property float|null $kerjasama
 * @property float|null $komitmen
 * @property float|null $konversiNilai
 * @property float|null $nilaiIntegrasi
 * @property float|null $nilaiPerilakuKerja
 * @property float|null $nilaiPrestasiKerja
 * @property float|null $nilaiSkp
 * @property float|null $nilairatarata
 * @property float|null $orientasiPelayanan
 * @property string|null $pejabatPenilai
 * @property string|null $penilaiGolongan
 * @property string|null $penilaiJabatan
 * @property string|null $penilaiNama
 * @property string|null $penilaiNipNrp
 * @property string|null $penilaiTmtGolongan
 * @property string|null $penilaiUnorNama
 * @property string|null $pnsDinilaiOrang
 * @property string|null $statusAtasanPenilai
 * @property string|null $statusPenilai
 * @property int|null $tahun
 * @property int|null $flag
 * @property string|null $updated
 * @property string|null $by
 */
class SiasnTempRwSkp21 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_temp_rw_skp21';
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
            [['disiplin', 'inisiatifKerja', 'integritas', 'jumlah', 'kepemimpinan', 'kerjasama', 'komitmen', 'konversiNilai', 'nilaiIntegrasi', 'nilaiPerilakuKerja', 'nilaiPrestasiKerja', 'nilaiSkp', 'nilairatarata', 'orientasiPelayanan'], 'number'],
            [['penilaiTmtGolongan'], 'safe'],
            [['tahun'], 'default', 'value' => 0],
            [['tahun'], 'integer'],
            [['atasanPejabatPenilai', 'id', 'pnsDinilaiOrang'], 'string', 'max' => 128],
            [['atasanPenilaiGolongan'], 'string', 'max' => 10],
            [['atasanPenilaiJabatan', 'atasanPenilaiNama', 'atasanPenilaiUnorNama', 'penilaiUnorNama'], 'string', 'max' => 150],
            [['atasanPenilaiNipNrp', 'penilaiNipNrp'], 'string', 'max' => 21],
            [['atasanPenilaiTmtGolongan'], 'string', 'max' => 12],
            [['jenisJabatan', 'penilaiGolongan'], 'string', 'max' => 20],
            [['jenisPeraturanKinerjaKd', 'pejabatPenilai', 'penilaiJabatan', 'penilaiNama', 'statusAtasanPenilai', 'statusPenilai'], 'string', 'max' => 100],
            // [['by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'atasanPejabatPenilai' => 'Atasan Pejabat Penilai',
            'atasanPenilaiGolongan' => 'Atasan Penilai Golongan',
            'atasanPenilaiJabatan' => 'Atasan Penilai Jabatan',
            'atasanPenilaiNama' => 'Atasan Penilai Nama',
            'atasanPenilaiNipNrp' => 'Atasan Penilai Nip Nrp',
            'atasanPenilaiTmtGolongan' => 'Atasan Penilai Tmt Golongan',
            'atasanPenilaiUnorNama' => 'Atasan Penilai Unor Nama',
            'disiplin' => 'Disiplin',
            'id' => 'ID',
            'inisiatifKerja' => 'Inisiatif Kerja',
            'integritas' => 'Integritas',
            'jenisJabatan' => 'Jenis Jabatan',
            'jenisPeraturanKinerjaKd' => 'Jenis Peraturan Kinerja Kd',
            'jumlah' => 'Jumlah',
            'kepemimpinan' => 'Kepemimpinan',
            'kerjasama' => 'Kerjasama',
            'komitmen' => 'Komitmen',
            'konversiNilai' => 'Konversi Nilai',
            'nilaiIntegrasi' => 'Nilai Integrasi',
            'nilaiPerilakuKerja' => 'Nilai Perilaku Kerja',
            'nilaiPrestasiKerja' => 'Nilai Prestasi Kerja',
            'nilaiSkp' => 'Nilai Skp',
            'nilairatarata' => 'Nilairatarata',
            'orientasiPelayanan' => 'Orientasi Pelayanan',
            'pejabatPenilai' => 'Pejabat Penilai',
            'penilaiGolongan' => 'Penilai Golongan',
            'penilaiJabatan' => 'Penilai Jabatan',
            'penilaiNama' => 'Penilai Nama',
            'penilaiNipNrp' => 'Penilai Nip Nrp',
            'penilaiTmtGolongan' => 'Penilai Tmt Golongan',
            'penilaiUnorNama' => 'Penilai Unor Nama',
            'pnsDinilaiOrang' => 'Pns Dinilai Orang',
            'statusAtasanPenilai' => 'Status Atasan Penilai',
            'statusPenilai' => 'Status Penilai',
            'tahun' => 'Tahun',
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

<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_temp_rw_skp22".
 *
 * @property string|null $hasilKinerjaNilai
 * @property string|null $id
 * @property string|null $kuadranKinerjaNilai
 * @property string|null $penilaiGolongan
 * @property string|null $penilaiJabatan
 * @property string|null $penilaiNama
 * @property string|null $penilaiNipNrp
 * @property string|null $penilaiUnorNama
 * @property string|null $perilakuKerjaNilai
 * @property string|null $pnsDinilaiOrang
 * @property string|null $statusPenilai
 * @property int|null $tahun
 * @property int|null $flag
 * @property string|null $updated
 * @property string|null $by
 */
class SiasnTempRwSkp22 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_temp_rw_skp22';
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
            [['tahun'], 'default', 'value' => 0],
            [['tahun'], 'integer'],
            // [['updated'], 'safe'],
            [['hasilKinerjaNilai', 'kuadranKinerjaNilai', 'penilaiGolongan', 'perilakuKerjaNilai'], 'string', 'max' => 5],
            [['id', 'pnsDinilaiOrang'], 'string', 'max' => 128],
            [['penilaiJabatan'], 'string', 'max' => 150],
            [['penilaiNama', 'penilaiUnorNama', 'statusPenilai'], 'string', 'max' => 100],
            [['penilaiNipNrp'], 'string', 'max' => 21],
            // [['by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hasilKinerjaNilai' => 'Hasil Kinerja Nilai',
            'id' => 'ID',
            'kuadranKinerjaNilai' => 'Kuadran Kinerja Nilai',
            'penilaiGolongan' => 'Penilai Golongan',
            'penilaiJabatan' => 'Penilai Jabatan',
            'penilaiNama' => 'Penilai Nama',
            'penilaiNipNrp' => 'Penilai Nip Nrp',
            'penilaiUnorNama' => 'Penilai Unor Nama',
            'perilakuKerjaNilai' => 'Perilaku Kerja Nilai',
            'pnsDinilaiOrang' => 'Pns Dinilai Orang',
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

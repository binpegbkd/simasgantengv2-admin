<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_temp_rw_jabatan".
 *
 * @property string|null $eselonId
 * @property string|null $id
 * @property string|null $instansiId
 * @property string|null $jabatanFungsionalId
 * @property string|null $jabatanFungsionalUmumId
 * @property string|null $jenisJabatan
 * @property string|null $jenisMutasiId
 * @property string|null $jenisPenugasanId
 * @property string|null $nomorSk
 * @property string|null $pnsId
 * @property string|null $satuanKerjaId
 * @property string|null $subJabatanId
 * @property string|null $tanggalSk
 * @property string|null $tmtJabatan
 * @property string|null $tmtMutasi
 * @property string|null $tmtPelantikan
 * @property string|null $unorId
 * @property int|null $flag
 * @property string|null $updated
 * @property string|null $by
 */
class SiasnTempRwJabatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_temp_rw_jabatan';
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
            // [['flag'], 'default', 'value' => null],
            // [['flag'], 'integer'],
            // [['updated'], 'safe'],
            [['eselonId', 'jenisJabatan'], 'string', 'max' => 5],
            [['id', 'instansiId', 'jabatanFungsionalId', 'jabatanFungsionalUmumId', 'jenisMutasiId', 'jenisPenugasanId', 'pnsId', 'satuanKerjaId', 'subJabatanId', 'unorId'], 'string', 'max' => 128],
            [['nomorSk'], 'string', 'max' => 100],
            [['tanggalSk', 'tmtJabatan', 'tmtMutasi', 'tmtPelantikan'], 'string', 'max' => 12],
            // [['by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'eselonId' => 'Eselon ID',
            'id' => 'ID',
            'instansiId' => 'Instansi ID',
            'jabatanFungsionalId' => 'Jabatan Fungsional ID',
            'jabatanFungsionalUmumId' => 'Jabatan Fungsional Umum ID',
            'jenisJabatan' => 'Jenis Jabatan',
            'jenisMutasiId' => 'Jenis Mutasi ID',
            'jenisPenugasanId' => 'Jenis Penugasan ID',
            'nomorSk' => 'Nomor Sk',
            'pnsId' => 'Pns ID',
            'satuanKerjaId' => 'Satuan Kerja ID',
            'subJabatanId' => 'Sub Jabatan ID',
            'tanggalSk' => 'Tanggal Sk',
            'tmtJabatan' => 'Tmt Jabatan',
            'tmtMutasi' => 'Tmt Mutasi',
            'tmtPelantikan' => 'Tmt Pelantikan',
            'unorId' => 'Unor ID',
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

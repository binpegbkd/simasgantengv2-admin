<?php

namespace app\modules\sitampan\models;

use Yii;

/**
 * This is the model class for table "preskin_tambah_tpp_opd".
 *
 * @property string $kolok id_unor
 * @property float $beban beban_kerja
 * @property float $prestasi prestasi_kerja
 * @property float $kondisi kondisi_kerja
 * @property float $tempat tempat_bertugas
 * @property float $pol pertimbangan_objektif_alinnya
 * @property int $flag
 * @property string $updated
 */
class PreskinTambahTppOpd extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preskin_tambah_tpp_opd';
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
            [['kolok'], 'required'],
            [['beban', 'prestasi', 'kondisi', 'tempat', 'pol'], 'number'],
            [['flag'], 'default', 'value' => null],
            [['flag'], 'integer'],
            [['updated'], 'safe'],
            [['kolok'], 'string', 'max' => 10],
            [['kolok'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kolok' => 'Kolok',
            'beban' => 'Beban',
            'prestasi' => 'Prestasi',
            'kondisi' => 'Kondisi',
            'tempat' => 'Tempat',
            'pol' => 'Pol',
            'flag' => 'Flag',
            'updated' => 'Updated',
        ];
    }
}

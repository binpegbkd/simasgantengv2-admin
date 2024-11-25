<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_ref_gol".
 *
 * @property string $ID
 * @property string $NAMA
 * @property string $NAMA_PANGKAT
 * @property string|null $GOL_P3K
 * @property string|null $GOL_P3K_ROM
 * @property string|null $NAMA_PANGKAT_P3K
 * @property string $GOL_GAJI
 */
class SiasnRefGol extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_ref_gol';
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
            [['ID', 'NAMA', 'NAMA_PANGKAT'], 'required'],
            [['ID', 'GOL_P3K', 'GOL_GAJI'], 'string', 'max' => 2],
            [['NAMA', 'GOL_P3K_ROM'], 'string', 'max' => 5],
            [['NAMA_PANGKAT', 'NAMA_PANGKAT_P3K'], 'string', 'max' => 30],
            [['ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NAMA' => 'Nama',
            'NAMA_PANGKAT' => 'Nama Pangkat',
            'GOL_P3K' => 'Gol P3k',
            'GOL_P3K_ROM' => 'Gol P3k Rom',
            'NAMA_PANGKAT_P3K' => 'Nama Pangkat P3k',
            'GOL_GAJI' => 'Gol Gaji',
        ];
    }
}

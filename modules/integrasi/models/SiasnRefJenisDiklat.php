<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_ref_jenis_diklat".
 *
 * @property string|null $id
 * @property string|null $jenis_diklat
 * @property string|null $jenis_kursus_sertipikat
 */
class SiasnRefJenisDiklat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_ref_jenis_diklat';
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
            [['id', 'jenis_kursus_sertipikat'], 'string', 'max' => 5],
            [['jenis_diklat'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis_diklat' => 'Jenis Diklat',
            'jenis_kursus_sertipikat' => 'Jenis Kursus Sertipikat',
        ];
    }
}

<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_ref_jenis_kursus".
 *
 * @property string|null $id
 * @property string|null $nama
 */
class SiasnRefJenisKursus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_ref_jenis_kursus';
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
            [['id'], 'string', 'max' => 128],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
        ];
    }
}
<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_ref_subjab".
 *
 * @property string|null $id
 * @property string|null $kel_jabatan_id
 * @property string|null $nama
 * @property string|null $status_aktif
 */
class SiasnRefSubjab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_ref_subjab';
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
            [['id', 'kel_jabatan_id'], 'string', 'max' => 128],
            [['nama'], 'string', 'max' => 255],
            [['status_aktif'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kel_jabatan_id' => 'Kel Jabatan ID',
            'nama' => 'Nama',
            'status_aktif' => 'Status Aktif',
        ];
    }
}

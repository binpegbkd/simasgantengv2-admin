<?php

namespace app\modules\efi\models;

use Yii;

/**
 * This is the model class for table "efi_files".
 *
 * @property string $nama_file
 * @property string $nip
 * @property int $id_dok
 * @property int $flag
 * @property string|null $siasn_id
 * @property string|null $siasn_path
 * @property string $updated
 * @property string|null $ket
 */
class EfiFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'efi_files';
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
            [['nama_file', 'nip', 'id_dok'], 'required'],
            [['id_dok', 'flag'], 'default', 'value' => null],
            [['id_dok', 'flag'], 'integer'],
            [['updated'], 'safe'],
            [['ket'], 'string'],
            [['nama_file', 'siasn_path'], 'string', 'max' => 255],
            [['nip'], 'string', 'max' => 18],
            [['siasn_id'], 'string', 'max' => 128],
            [['nama_file'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nama_file' => 'Nama File',
            'nip' => 'Nip',
            'id_dok' => 'Id Dok',
            'flag' => 'Flag',
            'siasn_id' => 'Siasn ID',
            'siasn_path' => 'Siasn Path',
            'updated' => 'Updated',
            'ket' => 'Ket',
        ];
    }
}

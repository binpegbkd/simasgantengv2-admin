<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tblasn".
 *
 * @property string $id
 * @property string|null $nipBaru
 * @property string|null $nipLama
 * @property string|null $nama
 * @property string|null $gelarDepan
 * @property string|null $gelarBelakang
 * @property string|null $email
 * @property int|null $flag
 * @property string $updated
 */
class Tblasn extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblasn';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['flag'], 'default', 'value' => null],
            [['flag'], 'integer'],
            [['updated'], 'safe'],
            [['id'], 'string', 'max' => 128],
            [['nipBaru'], 'string', 'max' => 18],
            [['nipLama'], 'string', 'max' => 9],
            [['nama'], 'string', 'max' => 200],
            [['gelarDepan', 'gelarBelakang', 'email'], 'string', 'max' => 100],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nipBaru' => 'Nip Baru',
            'nipLama' => 'Nip Lama',
            'nama' => 'Nama',
            'gelarDepan' => 'Gelar Depan',
            'gelarBelakang' => 'Gelar Belakang',
            'email' => 'Email',
            'flag' => 'Flag',
            'updated' => 'Updated',
        ];
    }
}

<?php

namespace app\modules\efi\models;

use Yii;

/**
 * This is the model class for table "efi_dok".
 *
 * @property int $id
 * @property string $dokumen
 * @property string|null $nama nama/format file default
 * @property string $ext file extension
 * @property int $max ukuran maximal (KB)
 * @property int $urutan
 * @property int $flag
 * @property string $updated
 * @property int $tahun
 */
class EfiDok extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'efi_dok';
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
            [['id', 'dokumen', 'ext', 'max'], 'required'],
            [['id', 'max', 'urutan', 'flag', 'tahun'], 'default', 'value' => null],
            [['id', 'max', 'urutan', 'flag', 'tahun'], 'integer'],
            [['updated'], 'safe'],
            [['dokumen', 'nama'], 'string', 'max' => 100],
            [['ext'], 'string', 'max' => 50],
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
            'dokumen' => 'Dokumen',
            'nama' => 'Nama',
            'ext' => 'Ext',
            'max' => 'Max',
            'urutan' => 'Urutan',
            'flag' => 'Flag',
            'updated' => 'Updated',
            'tahun' => 'Tahun',
        ];
    }
}

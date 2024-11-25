<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_ws".
 *
 * @property int $id
 * @property string|null $method
 * @property string|null $path
 * @property string|null $param
 * @property string $name
 * @property int $flag
 * @property string $updated
 */
class SiasnWs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_ws';
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
            [['id', 'name'], 'required'],
            [['id', 'flag'], 'default', 'value' => null],
            [['id', 'flag'], 'integer'],
            [['updated'], 'safe'],
            [['method'], 'string', 'max' => 20],
            [['path', 'name'], 'string', 'max' => 150],
            [['param'], 'string', 'max' => 50],
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
            'method' => 'Method',
            'path' => 'Path',
            'param' => 'Param',
            'name' => 'Name',
            'flag' => 'Flag',
            'updated' => 'Updated',
        ];
    }
}

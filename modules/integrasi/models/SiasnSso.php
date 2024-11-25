<?php

namespace app\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "siasn_sso".
 *
 * @property string $user
 * @property string|null $token_sso
 * @property string|null $expired
 */
class SiasnSso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_sso';
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
            [['user'], 'required'],
            [['token_sso'], 'string'],
            [['token_sso_exp'], 'safe'],
            [['user'], 'string', 'max' => 21],
            [['user'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user' => 'User',
            'token_sso' => 'Token Sso',
            'token_sso_esp' => 'Expired',
        ];
    }
}

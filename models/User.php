<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $IdUser
 * @property string $nome
 * @property string $cognome
 * @property int $ntelefono
 * @property string $descrizione
 * @property string $email
 * @property string $pass
 * @property string|null $username
 * @property string|null $accessToken
 * @property string|null $authKey
 * @property int|null $tipo
 *
 * @property Assegnaesercizio[] $assegnaesercizios
 * @property Esercizio[] $esercizios
 * @property Esercizio[] $idEsercizios
 * @property Esercizio[] $idEsercizios0
 * @property Esercizio[] $idEsercizios1
 * @property Esercizio[] $idEsercizios2
 * @property Paziente[] $pazientes
 * @property Paziente[] $pazientes0
 * @property Valutaesercizio[] $valutaesercizios
 * @property Valutaesercizio[] $valutaesercizios0
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'cognome', 'ntelefono', 'descrizione', 'email', 'pass'], 'required'],
            [['ntelefono', 'tipo'], 'integer'],
            [['nome', 'cognome', 'email', 'pass', 'username', 'accessToken', 'authKey'], 'string', 'max' => 255],
            [['descrizione'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdUser' => 'Id User',
            'nome' => 'Nome',
            'cognome' => 'Cognome',
            'ntelefono' => 'Ntelefono',
            'descrizione' => 'Descrizione',
            'email' => 'Email',
            'pass' => 'Pass',
            'username' => 'Username',
            'accessToken' => 'Access Token',
            'authKey' => 'Auth Key',
            'tipo' => 'Tipo',
        ];
    }

    /**
     * Gets query for [[Assegnaesercizios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssegnaesercizios()
    {
        return $this->hasMany(Assegnaesercizio::className(), ['IdLogoP' => 'IdUser']);
    }

    /**
     * Gets query for [[Esercizios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEsercizios()
    {
        return $this->hasMany(Esercizio::className(), ['IdLogoP' => 'IdUser']);
    }

    /**
     * Gets query for [[IdEsercizios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdEsercizios()
    {
        return $this->hasMany(Esercizio::className(), ['IdEsercizio' => 'IdEsercizio'])->viaTable('valutaesercizio', ['IdCaregiver' => 'IdUser']);
    }

    /**
     * Gets query for [[IdEsercizios0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdEsercizios0()
    {
        return $this->hasMany(Esercizio::className(), ['IdEsercizio' => 'IdEsercizio'])->viaTable('valutaesercizio', ['IdCaregiver' => 'IdUser']);
    }

    /**
     * Gets query for [[IdEsercizios1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdEsercizios1()
    {
        return $this->hasMany(Esercizio::className(), ['IdEsercizio' => 'IdEsercizio'])->viaTable('valutaesercizio', ['IdCaregiver' => 'IdUser']);
    }

    /**
     * Gets query for [[IdEsercizios2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdEsercizios2()
    {
        return $this->hasMany(Esercizio::className(), ['IdEsercizio' => 'IdEsercizio'])->viaTable('valutaesercizio', ['IdCaregiver' => 'IdUser']);
    }

    /**
     * Gets query for [[Pazientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPazientes()
    {
        return $this->hasMany(Paziente::className(), ['IdLogoP' => 'IdUser']);
    }

    /**
     * Gets query for [[Pazientes0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPazientes0()
    {
        return $this->hasMany(Paziente::className(), ['IdCaregiver' => 'IdUser']);
    }

    /**
     * Gets query for [[Valutaesercizios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValutaesercizios()
    {
        return $this->hasMany(Valutaesercizio::className(), ['IdCaregiver' => 'IdUser']);
    }

    /**
     * Gets query for [[Valutaesercizios0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValutaesercizios0()
    {
        return $this->hasMany(Valutaesercizio::className(), ['IdCaregiver' => 'IdUser']);
    }
    public static function findIdentity($IdUser)
    {
        return self::findOne($IdUser); 
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::find()->where (['access_token'=>$token])->one(); 
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return self::findOne(['email'=>$email]); 
    }
    public static function findCaregiver()
    {
    return User::find()->where(['<>', 'tipo', 0]);
    }
   
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->IdUser;
    }
    public function getNome()
    {
        return $this->nome;
    }


    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($pass)
    {
        return Yii::$app->security->validatePassword($pass,$this->pass);
    }
    public function isCaregiver()
    {
        if($this->tipo == 0)
        {
            return FALSE;
        }
        return TRUE;
    }
    public static function findThisUser($id)
    {
        
        return User::find()->where(['=', 'IdUser', $id]);
   
    }
}

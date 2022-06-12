<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "paziente".
 *
 * @property int $IdPaziente
 * @property string $nome
 * @property int $livello
 * @property int $IdLogoP
 * @property int $IdCaregiver
 *
 * @property Assegnaesercizio[] $assegnaesercizios
 * @property User $idCaregiver
 * @property Esercizio[] $idEsercizios
 * @property User $idLogoP
 * @property Svolgeesercizio[] $svolgeesercizios
 */
class Paziente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paziente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome','IdCaregiver'], 'required'],
            [['livello', 'IdLogoP', 'IdCaregiver'], 'integer'],
            [['nome'], 'string', 'max' => 255],
            [['IdLogoP'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdLogoP' => 'IdUser']],
            [['IdCaregiver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdCaregiver' => 'IdUser']],
        ];
    }
   

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdPaziente' => 'Id Paziente',
            'nome' => 'Nome',
            'livello' => 'Livello',
            'IdLogoP' => 'Logopedista',
            'IdCaregiver' => 'Caregiver',
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'IdLogoP',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * Gets query for [[Assegnaesercizios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssegnaesercizios()
    {
        return $this->hasMany(Assegnaesercizio::className(), ['IdPaziente' => 'IdPaziente']);
    }
    public function getId()
    {
        return $this->IdPaziente;
    }

    public static function findMypaziente()
    {
        $user = Yii::$app->user->identity;
        $id = $user->getId();
        return Paziente::find()->where(['=', 'IdCaregiver', $id]);
    }
    public static function findMypazi()
    {
        $user = Yii::$app->user->identity;
        $id = $user->getId();
        return Paziente::find()->where(['=', 'IdLogoP', $id])->all();
    }
    
    public function getLivello()
    {
        $id = $this ->IdPaziente;
        $somma = 0;
        $count = 0;
        $idsvolto = 0;
        $rows = (new \yii\db\Query())
        ->select(['ID'])
        ->from('svolgeesercizio')
        ->where(['IdPaziete' => $id])
        ->all();
        foreach($rows as $result) {
            $idsvolto = $result['ID'];
            $count ++;
        }
    
        $count = 0; 
        $rows = (new \yii\db\Query())
        ->select(['valutazione'])
        ->from('valutasvolto')
        ->where(['IdSvolto' => $idsvolto])
        ->all();
        foreach($rows as $result) {
            $somma = $somma + $result['valutazione'];
            $count ++;
        }
        if($count==0)
        {
            return 'Nessuna valutazione';
        }else{
            return $somma/$count;
        }
    }
   

    /**
     * Gets query for [[IdCaregiver]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCaregiver()
    {
        return $this->hasOne(User::className(), ['IdUser' => 'IdCaregiver']);
    }

    /**
     * Gets query for [[IdEsercizios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdEsercizios()
    {
        return $this->hasMany(Esercizio::className(), ['IdEsercizio' => 'IdEsercizio'])->viaTable('svolgeesercizio', ['IdPaziente' => 'IdPaziente']);
    }

    /**
     * Gets query for [[IdLogoP]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdLogoP()
    {
        return $this->hasOne(User::className(), ['IdUser' => 'IdLogoP']);
    }

    /**
     * Gets query for [[Svolgeesercizios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSvolgeesercizios()
    {
        return $this->hasMany(Svolgeesercizio::className(), ['IdPaziente' => 'IdPaziente']);
    }
}

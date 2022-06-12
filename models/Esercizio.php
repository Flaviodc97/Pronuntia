<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;
use yii\behaviors\BlameableBehavior;
use app\models\ValutaEsercizio;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "esercizio".
 *
 * @property int $IdEsercizio
 * @property string $nome
 * @property string $testo
 * @property int $IdLogoP
 * @property string|null $image
 *
 * @property Assegnaesercizio[] $assegnaesercizios
 * @property User[] $idCaregivers
 * @property User[] $idCaregivers0
 * @property User[] $idCaregivers1
 * @property User[] $idCaregivers2
 * @property User $idLogoP
 * @property Valutaesercizio[] $valutaesercizios
 * @property Valutaesercizio[] $valutaesercizios0
 */
class Esercizio extends \yii\db\ActiveRecord
{  
     /**
    * @var \yii\web\UploadedFile
    */
   public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'esercizio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'testo'], 'required'],
            [['IdLogoP'], 'integer'],
            [['nome', 'testo', 'image'], 'string', 'max' => 255],
            [['IdLogoP'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['IdLogoP' => 'IdUser']],
            [['imageFile'], 'image', 'maxSize' => 10 * 1024 * 1024],
           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdEsercizio' => 'Id Esercizio',
            'nome' => 'Nome',
            'testo' => 'Testo',
            'IdLogoP' => 'Id Logo P',
            'image' => 'Immagine Esercizio',
            'imageFile' => 'Immage Esercizio',
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
    public function save($runValidation = true, $attributeNames = null)
    {
        
        if ($this->imageFile) {
            $this->image = '/img/' . Yii::$app->security->generateRandomString() . '/' . $this->imageFile->name;
        }

        $transaction = Yii::$app->db->beginTransaction();
        $ok = parent::save($runValidation, $attributeNames);

        if ($ok && $this->imageFile) {
            $fullPath = Yii::getAlias('@app/web/uploads' . $this->image);
            $dir = dirname($fullPath);
            if (!FileHelper::createDirectory($dir) | !$this->imageFile->saveAs($fullPath)) {
                $transaction->rollBack();

                return false;
            }
        }

        $transaction->commit();

        return $ok;
    }

    public function getImageUrl()
    {
        return self::formatImageUrl($this->image);
    }

    public static function formatImageUrl($imagePath)
    {
        if ($imagePath) {
            return Yii::$app->params['pronuntia'] . '/uploads' . $imagePath;
        }

    }
    public function afterDelete()
    {
        parent::afterDelete();
        if ($this->image) {
            $dir = Yii::getAlias('@app/web/uploads'). dirname($this->image);
            FileHelper::removeDirectory($dir);
        }
    }

    public function getValutazione()
    {   
        $id = $this ->IdEsercizio;
        $somma = 0;
        $count = 0;
        $rows = (new \yii\db\Query())
        ->select(['valutazione'])
        ->from('valutaesercizio')
        ->where(['IdEsercizio' => $id])
        ->all();
        foreach($rows as $result) {
            $somma= $somma + $result['valutazione'];
            $count ++;
        }
        if($count==0)
        {
            return 'Nessuna valutazione';
        }else{
            return $somma/$count;
        }
      
    }
    public static function findMyEsercizioSvolto()
    {      
        $user = Yii::$app->user->identity;
        $id = $user->getId();
        $somma = 0;
        $count = 0;

        $rows = (new \yii\db\Query())
        ->select(['IdPaziente'])
        ->from('paziente')
        ->where(['IdCaregiver' => $id])
        ->all();
        foreach($rows as $result) {
            $idpaziente[$somma] = $result['IdPaziente'];
            $somma ++;
        }
       
        $rows = (new \yii\db\Query())
        ->select(['IdEsercizio'])
        ->from('svolgeesercizio')
        ->where(['IdPaziete' => $idpaziente])
        ->all();
        foreach($rows as $result) {
            $idesercizio[$count] = $result['IdEsercizio'];
            $count++;
            }
            $subQuery = Valutaesercizio::find()->select(['IdEsercizio'])->where(['IdEsercizio' => $idesercizio]); //fetch the customers whos posts are inactive - subquery
        return Esercizio::find()->andwhere(['NOT IN','IdEsercizio', $subQuery])->andWhere(['IdEsercizio'=>$idesercizio])->all();
        
    }
    public static function findBy()
    {
        $userid = Yii::$app->user->identity->getId();
        $somma = 0;
        $rows = (new \yii\db\Query())
        ->select(['IdEsercizio'])
        ->from('esercizio')
        ->where(['IdLogoP' => $userid])
        ->all();

        foreach($rows as $result) {
            $ides[$somma] = $result['IdEsercizio'];
            $somma ++;
        }
        
        return Esercizio::find()->where(['IdEsercizio'=>$ides])->all();
  
    }
    /**
     * Gets query for [[Assegnaesercizios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssegnaesercizios()
    {
        return $this->hasMany(Assegnaesercizio::className(), ['IdEsercizio' => 'IdEsercizio']);
    }

    /**
     * Gets query for [[IdCaregivers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCaregivers()
    {
        return $this->hasMany(User::className(), ['IdUser' => 'IdCaregiver'])->viaTable('valutaesercizio', ['IdEsercizio' => 'IdEsercizio']);
    }

    /**
     * Gets query for [[IdCaregivers0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCaregivers0()
    {
        return $this->hasMany(User::className(), ['IdUser' => 'IdCaregiver'])->viaTable('valutaesercizio', ['IdEsercizio' => 'IdEsercizio']);
    }

    /**
     * Gets query for [[IdCaregivers1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCaregivers1()
    {
        return $this->hasMany(User::className(), ['IdUser' => 'IdCaregiver'])->viaTable('valutaesercizio', ['IdEsercizio' => 'IdEsercizio']);
    }

    /**
     * Gets query for [[IdCaregivers2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCaregivers2()
    {
        return $this->hasMany(User::className(), ['IdUser' => 'IdCaregiver'])->viaTable('valutaesercizio', ['IdEsercizio' => 'IdEsercizio']);
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
     * Gets query for [[Valutaesercizios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValutaesercizios()
    {
        return $this->hasMany(Valutaesercizio::className(), ['IdEsercizio' => 'IdEsercizio']);
    }

    /**
     * Gets query for [[Valutaesercizios0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValutaesercizios0()
    {
        return $this->hasMany(Valutaesercizio::className(), ['IdEsercizio' => 'IdEsercizio']);
    }
}

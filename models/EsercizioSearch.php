<?php

namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Esercizio;
use app\models\User;

/**
 * EsercizioSearch represents the model behind the search form of `app\models\Esercizio`.
 */
class EsercizioSearch extends Esercizio
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdEsercizio', 'IdLogoP'], 'integer'],
            [['nome', 'testo', 'image'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        
        $user = Yii::$app->user->identity;
        $id = $user->getId();
        $query = Esercizio::find()->where(['=','IdLogoP', $id ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'IdEsercizio' => $this->IdEsercizio,
            'IdLogoP' => $this->IdLogoP,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'testo', $this->testo])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}

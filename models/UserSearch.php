<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\user;

/**
 * UserSearch represents the model behind the search form of `app\models\user`.
 */
class UserSearch extends user
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IdUser', 'ntelefono', 'tipo'], 'integer'],
            [['nome', 'cognome', 'descrizione', 'email', 'pass', 'username', 'accessToken', 'authKey'], 'safe'],
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
        $query = user::find();

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
            'IdUser' => $this->IdUser,
            'ntelefono' => $this->ntelefono,
            'tipo' => $this->tipo,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'cognome', $this->cognome])
            ->andFilterWhere(['like', 'descrizione', $this->descrizione])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'pass', $this->pass])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'accessToken', $this->accessToken])
            ->andFilterWhere(['like', 'authKey', $this->authKey]);

        return $dataProvider;
    }
}

<?php

namespace yuncms\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yuncms\user\models\BankCard;

/**
 * BankCardSearch represents the model behind the search form about `yuncms\user\models\BankCard`.
 */
class BankCardSearch extends BankCard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['bank', 'bank_city', 'bank_username', 'bank_name', 'bankcard_number'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = BankCard::find()->where(['user_id' => Yii::$app->user->id]);

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
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'bank_city', $this->bank_city])
            ->andFilterWhere(['like', 'bank_username', $this->bank_username])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'bankcard_number', $this->bankcard_number]);

        return $dataProvider;
    }
}

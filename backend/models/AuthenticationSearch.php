<?php

namespace yuncms\user\backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yuncms\user\models\Authentication;

/**
 * AuthenticationSearch represents the model behind the search form about `yuncms\user\models\Authentication`.
 */
class AuthenticationSearch extends Model
{
    public $user_id;
    public $real_name;
    public $id_card;
    public $status;
    public $created_at;
    public $updated_at;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['real_name', 'id_card', 'status', 'created_at', 'updated_at'], 'safe'],
            [['status','created_at','updated_at'], 'default', 'value' => null],
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
        $query = Authentication::find()->with('user');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'status' => $this->status,
        ]);

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        if ($this->updated_at !== null) {
            $date = strtotime($this->updated_at);
            $query->andWhere(['between', 'updated_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'real_name', $this->real_name])
            ->andFilterWhere(['like', 'id_card', $this->id_card]);

        return $dataProvider;
    }

    /**
     * 下拉筛选
     * @param string $column
     * @param null|string $value
     * @return bool|mixed
     */
    public static function dropDown($column, $value = null)
    {
        $dropDownList = [
            "status" => [
                0 => Yii::t('user', 'Pending review'),
                1 => Yii::t('user', 'Rejected'),
                2 => Yii::t('user', 'Authenticated'),
            ],
            "redirect_code" => [
                "301" => "301",
                "302" => "302",
            ],
        ];
        //根据具体值显示对应的值
        if ($value !== null) {
            return array_key_exists($column, $dropDownList) ? $dropDownList[$column][$value] : false;
        } else {//返回关联数组，用户下拉的filter实现
            return array_key_exists($column, $dropDownList) ? $dropDownList[$column] : false;
        }
    }
}

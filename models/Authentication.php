<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yuncms\user\ModuleTrait;

/**
 * This is the model class for table "user_authentications".
 *
 * @property integer $user_id
 * @property string $real_name
 * @property string $id_card
 * @property int $status
 * @property string $failed_reason
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Authentication extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @var \yii\web\UploadedFile 头像上传字段
     */
    public $imageFile;

    /**
     * @var string 验证码
     */
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_authentications}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior'
            ],

            'blameable' => [
                'class' => 'yii\behaviors\BlameableBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            'create' => ['real_name', 'id_card', 'imageFile'],
            'update' => ['real_name', 'id_card', 'imageFile'],
            'verify' => ['real_name', 'id_card', 'status', 'failed_reason'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['real_name', 'id_card', 'imageFile', 'verifyCode'], 'required', 'on' => ['create', 'update']],
            [['real_name', 'id_card'], 'filter', 'filter' => 'trim'],
            [['failed_reason'], 'filter', 'filter' => 'trim'],
            ['id_card', 'string', 'min' => 18, 'max' => 18],
            ['id_card', 'yuncms\system\validators\IdCardValidator'],
            [['imageFile'], 'file', 'extensions' => 'gif,jpg,jpeg,png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('user', 'File has to be smaller than 2MB')],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'captchaAction' => '/user/authentication/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('user', 'User Id'),
            'real_name' => Yii::t('user', 'Real Name'),
            'id_card' => Yii::t('user', 'Id Card'),
            'imageFile' => Yii::t('user', 'Id Card Image'),
            'status' => Yii::t('user', 'Status'),
            'failed_reason' => Yii::t('user', 'Failed Reason'),
            'verifyCode' => Yii::t('user', 'Verify Code'),
            'idCardUrl' => Yii::t('user', 'Id Card Image'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }

    public function getIdCardUrl()
    {
        return $this->getModule()->getIdCardUrl($this->user_id);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $idCardPath = $this->getModule()->getIdCardPath(Yii::$app->user->id);
            if ($this->imageFile) {
                $this->imageFile->saveAs($idCardPath);
            }
            return true;
        } else {
            return false;
        }
    }
}
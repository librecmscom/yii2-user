<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use phpDocumentor\Reflection\Types\Self_;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yuncms\user\ModuleTrait;

/**
 * This is the model class for table "user_authentications".
 *
 * @property integer $user_id 用户ID
 * @property string $real_name 真实姓名
 * @property string $id_card 证件号
 * @property string $id_type 证件类型
 * @property string $passport_cover
 * @property string $passport_person_page
 * @property string $passport_self_holding
 * @property int $status 审核状态
 * @property string $failed_reason 拒绝原因
 * @property integer $created_at 创建时间
 * @property integer $updated_at 更新时间
 *
 * @property User $user
 */
class Authentication extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @var \yii\web\UploadedFile 身份证上传字段
     */
    public $id_file;

    /**
     * @var \yii\web\UploadedFile 身份证上传字段
     */
    public $id_file1;

    /**
     * @var \yii\web\UploadedFile 身份证上传字段
     */
    public $id_file2;

    /**
     * @var string 验证码
     */
    public $verifyCode;

    /**
     * @var bool 是否同意注册协议
     */
    public $registrationPolicy;

    const TYPE_ID = 'id';
    const TYPE_PASSPORT = 'passport';
    const TYPE_ARMYID = 'armyid';
    const TYPE_TAIWANID = 'taiwan';
    const TYPE_HKMCID = 'hkmcid';

    const STATUS_PENDING = 0;
    const STATUS_REJECTED = 1;
    const STATUS_AUTHENTICATED = 2;

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
            'create' => ['real_name', 'id_type', 'id_card', 'id_file', 'id_file1', 'id_file2'],
            'update' => ['real_name', 'id_type', 'id_card', 'id_file', 'id_file1', 'id_file2'],
            'verify' => ['real_name', 'id_card', 'status', 'failed_reason'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['real_name', 'id_card', 'id_file', 'id_file1', 'id_file2', 'verifyCode'], 'required', 'on' => ['create', 'update']],
            [['real_name', 'id_card',], 'filter', 'filter' => 'trim'],

            ['id_type', 'in', 'range' => [self::TYPE_ID, self::TYPE_PASSPORT, self::TYPE_ARMYID, self::TYPE_TAIWANID, self::TYPE_HKMCID], 'on' => ['create', 'update']],

            [['failed_reason'], 'filter', 'filter' => 'trim'],

            [['id_card'], 'string', 'when' => function ($model) {//中国大陆18位身份证号码
                return $model->id_type == static::TYPE_ID;
            }, 'whenClient' => "function (attribute, value) {return jQuery(\"#authentication-id_type\").val() == '" . Authentication::TYPE_ID . "';}",
                'length' => 18, 'on' => ['create', 'update']],

            ['id_card', 'yuncms\system\validators\IdCardValidator', 'when' => function ($model) {//中国大陆18位身份证号码校验
                return $model->id_type == static::TYPE_ID;
            }, 'on' => ['create', 'update']],

            [['id_file'], 'file', 'extensions' => 'gif,jpg,jpeg,png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('user', 'File has to be smaller than 2MB'), 'on' => ['create', 'update']],
            [['id_file1'], 'file', 'extensions' => 'gif,jpg,jpeg,png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('user', 'File has to be smaller than 2MB'), 'on' => ['create', 'update']],
            [['id_file2'], 'file', 'extensions' => 'gif,jpg,jpeg,png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('user', 'File has to be smaller than 2MB'), 'on' => ['create', 'update']],

            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha', 'captchaAction' => '/user/authentication/captcha'],

            'registrationPolicyRequired' => ['registrationPolicy', 'required', 'skipOnEmpty' => false, 'requiredValue' => true,
                'message' => Yii::t('user', '{attribute} must be selected.')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('user', 'User Id'),
            'real_name' => Yii::t('user', 'Full Name'),
            'id_type' => Yii::t('user', 'Id Type'),
            'type' => Yii::t('user', 'Id Type'),
            'id_card' => Yii::t('user', 'Id Card'),
            'id_file' => Yii::t('user', 'Passport cover'),
            'id_file1' => Yii::t('user', 'Passport person page'),
            'id_file2' => Yii::t('user', 'Passport self holding'),
            'passport_cover' => Yii::t('user', 'Passport cover'),
            'passport_person_page' => Yii::t('user', 'Passport person page'),
            'passport_self_holding' => Yii::t('user', 'Passport self holding'),
            'status' => Yii::t('user', 'Status'),
            'failed_reason' => Yii::t('user', 'Failed Reason'),
            'verifyCode' => Yii::t('user', 'Verify Code'),
            'idCardUrl' => Yii::t('user', 'Id Card Image'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
            'registrationPolicy' => Yii::t('user', 'Agree and accept Service Agreement and Privacy Policy'),
        ];
    }

    public function getType()
    {
        switch ($this->id_type) {
            case self::TYPE_ID:
                $genderName = Yii::t('user', 'ID Card');
                break;
            case self::TYPE_PASSPORT:
                $genderName = Yii::t('user', 'Passport ID');
                break;
            case self::TYPE_ARMYID:
                $genderName = Yii::t('user', 'Army ID');
                break;
            case self::TYPE_TAIWANID:
                $genderName = Yii::t('user', 'Taiwan ID');
                break;
            case self::TYPE_HKMCID:
                $genderName = Yii::t('user', 'HKMC ID');
                break;
            default:
                throw new \RuntimeException('Not set!');
        }
        return $genderName;
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
        if ($this->user_id) {
            return $this->getModule()->getIdCardUrl($this->user_id);
        }
        return $this->getModule()->getIdCardUrl(Yii::$app->user->id);
    }

    public function getPassportCover64()
    {
        $idCardPath = $this->getIdCardPath();
        if (file_exists($idCardPath . '_passport_cover_image.jpg')) {
            $file = file_get_contents($idCardPath . '_passport_cover_image.jpg');
            return 'data:image/jpg;base64,' . base64_encode($file);
        }
    }

    public function getPassportPersonPage64()
    {
        $idCardPath = $this->getIdCardPath();
        if (file_exists($idCardPath . '_passport_person_page_image.jpg')) {
            $file = file_get_contents($idCardPath . '_passport_person_page_image.jpg');
            return 'data:image/jpg;base64,' . base64_encode($file);
        }
    }

    public function getPassportSelfHolding64()
    {
        $idCardPath = $this->getIdCardPath();
        if (file_exists($idCardPath . '_passport_self_holding_image.jpg')) {
            $file = file_get_contents($idCardPath . '_passport_self_holding_image.jpg');
            return 'data:image/jpg;base64,' . base64_encode($file);
        }
    }

    public function getIdCardPath()
    {
        if ($this->user_id) {
            return $this->getModule()->getIdCardPath($this->user_id);
        }
        return $this->getModule()->getIdCardPath(Yii::$app->user->id);
    }

    public function isAuthentication()
    {
        return $this->status == static::STATUS_AUTHENTICATED;
    }

    /**
     * 删除前先删除附件
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $idCardPath = $this->getIdCardPath();
            if (file_exists($idCardPath . '_passport_cover_image.jpg')) {
                @unlink($idCardPath . '_passport_cover_image.jpg');
            }
            if (file_exists($idCardPath . '_passport_person_page_image.jpg')) {
                @unlink($idCardPath . '_passport_person_page_image.jpg');
            }
            if (file_exists($idCardPath . '_passport_self_holding_image.jpg')) {
                @unlink($idCardPath . '_passport_self_holding_image.jpg');
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $idCardPath = $this->getIdCardPath();
            if ($this->id_file && $this->id_file->saveAs($idCardPath . '_passport_cover_image.jpg')) {
                $this->passport_cover = $this->getIdCardUrl() . '_passport_cover_image.jpg';
            }
            if ($this->id_file1 && $this->id_file1->saveAs($idCardPath . '_passport_person_page_image.jpg')) {
                $this->passport_person_page = $this->getIdCardUrl() . '_passport_person_page_image.jpg';
            }
            if ($this->id_file2 && $this->id_file2->saveAs($idCardPath . '_passport_self_holding_image.jpg')) {
                $this->passport_self_holding = $this->getIdCardUrl() . '_passport_self_holding_image.jpg';
            }
            return true;
        } else {
            return false;
        }
    }
}
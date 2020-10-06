<?php
namespace common\modules\user\forms;

use common\modules\user\Module;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use common\modules\user\models\User;

/**
 * Password reset request form
 *
 * @property-read User|null $user
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    private $_user = false;
    private $_timeout;

    /**
     * PasswordResetRequestForm constructor.
     * @param integer $timeout
     * @param array $config
     */
    public function __construct($timeout, $config = [])
    {
        $this->_timeout = $timeout;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\modules\user\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Module::t('module', 'THERE_IS_NO_USER_WITH_THIS_EMAIL_ADDRESS'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => Module::t('module','EMAIL'),
        ];
    }

    /**
     * @param string $attribute
     * @param array $params
     */
    public function validateIsSent($attribute, $params)
    {
        if (!$this->hasErrors() && $user = $this->getUser()) {
            if (User::isPasswordResetTokenValid($user->$attribute, $this->_timeout)) {
                $this->addError($attribute, Module::t('module', 'ERROR_TOKEN_IS_SENT'));
            }
        }
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     * @throws Exception
     */
    public function sendEmail()
    {

        if ($user = $this->getUser()) {
            $user->generatePasswordResetToken();
            if ($user->save()) {
                return Yii::$app->mailer->compose(['text' => '@app/modules/user/mails/passwordReset'], ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . Yii::$app->name)
                    ->send();
            }
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne([
                'email' => $this->email,
                'status' => User::STATUS_ACTIVE,
            ]);
        }

        return $this->_user;
    }
}

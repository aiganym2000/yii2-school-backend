<?php


namespace api\versions\v1\controllers;


use api\controllers\AuthBaseController;
use api\models\forms\ActivationForm;
use api\models\forms\ChangePasswordForm;
use api\models\forms\InvitationForm;
use api\models\forms\LoginForm;
use api\models\forms\LoginPhoneForm;
use api\models\forms\RefreshTokenForm;
use api\models\forms\RegistrationForm;
use api\models\forms\ResetPasswordEmailForm;
use api\models\forms\ResetPasswordRequestForm;
use api\models\helper\RequestHelper;
use common\models\entity\User;
use common\models\entity\UserRefreshToken;
use common\models\services\UserService;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\web\ConflictHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class AuthController extends AuthBaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'except' => [
                'sign-in',
                'sign-in-phone',
                'registration',
                'activation',
                'refresh-token',
                'reset-password-request',
                'reset-password-email',
                'options',
                'invitation',
            ],
        ];

        return $behaviors;
    }

    /**
     * Авторизация
     * @return array
     * @throws ConflictHttpException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionSignIn()
    {
        $data = $this->getDecodedBodyData();
        $model = new LoginForm();
        if ($model->load($data, '') && $model->validate()) {
            $user = $model->_user;

            $token = $this->generateJwt($user);

            $refresh_token = $this->generateRefreshToken($user);

            if ($model->ftoken) {
                $user->f_token = $model->ftoken;
                $user->save();
            }

            return [
                'status' => 200,
                'user' => $user->getData(),
                'token' => (string)$token,
                'refresh_token' => $refresh_token->urf_token,
            ];
        } else {
            return $model->getFirstErrors();
        }
    }

    private function generateJwt(User $user)
    {
        $jwt = Yii::$app->jwt;
        $signer = $jwt->getSigner('HS256');
        $key = $jwt->getKey();
        $time = time();

        $jwtParams = Yii::$app->params['jwt'];

        return $jwt->getBuilder()
            ->issuedBy($jwtParams['issuer'])
            ->permittedFor($jwtParams['audience'])
            ->identifiedBy($jwtParams['id'], true)
            ->issuedAt($time)
            ->expiresAt($time + $jwtParams['expire'])
            ->withClaim('uid', $user->id)
            ->getToken($signer, $key);
    }

    /**
     * @param User $user
     * @param User|null $impersonator
     * @return UserRefreshToken
     * @throws Exception
     * @throws ServerErrorHttpException
     */
    private function generateRefreshToken(User $user, User $impersonator = null)
    {
        $oldTokens = UserRefreshToken::findAll(['urf_userID' => $user->id]);
        foreach ($oldTokens as $oldToken) {
            $oldToken->delete();
        }

        $refreshToken = Yii::$app->security->generateRandomString(200);
        $userRefreshToken = new UserRefreshToken([
            'urf_userID' => $user->id,
            'urf_token' => $refreshToken,
            'urf_ip' => Yii::$app->request->userIP,
            'urf_user_agent' => Yii::$app->request->userAgent,
            'urf_created' => gmdate('Y-m-d H:i:s'),
        ]);
        if (!$userRefreshToken->save()) {
            throw new ServerErrorHttpException('Не сохранился рефреш токен: ' . $userRefreshToken->getErrorSummary(true));
        }

        return $userRefreshToken;
    }

    /**
     * Авторизация
     * @return array
     * @throws ConflictHttpException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionSignInPhone()
    {
        $data = $this->getDecodedBodyData();
        $model = new LoginPhoneForm();
        if ($model->load($data, '') && $model->validate()) {
            $user = $model->_user;

            $token = $this->generateJwt($user);

            $refresh_token = $this->generateRefreshToken($user);

            if ($model->ftoken) {
                $user->f_token = $model->ftoken;
                $user->save();
            }

            return [
                'status' => 200,
                'user' => $user->getData(),
                'token' => (string)$token,
                'refresh_token' => $refresh_token->urf_token,
            ];
        } else {
            return $model->getFirstErrors();
        }
    }

    /**
     * Регистрация
     * @return array
     * @throws ConflictHttpException
     * @throws Exception
     */
    public function actionRegistration()
    {
        $model = new RegistrationForm();

        if (!$model->load(Yii::$app->request->post(), '') || !($user = $model->save()))
            RequestHelper::exceptionModel($model);

        return RequestHelper::success();
    }

    /**
     * Подтверждение телефона
     * @return array
     * @throws ConflictHttpException
     */
    public function actionActivation()
    {
        $model = new ActivationForm();

        if (!$model->load(Yii::$app->request->post(), '') || !$model->validate())
            RequestHelper::exceptionModel($model);

        return RequestHelper::success();
    }

    public function actionRefreshToken()
    {
        $model = new RefreshTokenForm();

        if (!$model->load(Yii::$app->request->post(), '') || !$model->validate())
            RequestHelper::exceptionModel($model);

        $refreshToken = $model->refreshToken;
        $userRefreshToken = UserRefreshToken::find()->where(['urf_token' => $refreshToken])->one();

        //print_r($userRefreshToken);
        if (Yii::$app->request->getMethod() == 'POST') {
            // Getting new JWT after it has expired
            if (!$userRefreshToken) {
                return new UnauthorizedHttpException('Рефреш токен не существует.');
            }

            $user = UserService::getActiveUser($userRefreshToken->urf_userID);
            if (!$user) {
                $userRefreshToken->delete();
                return new UnauthorizedHttpException('Пользователь неактивен.');
            }

            $token = $this->generateJwt($user);
            $refreshToken = $this->generateRefreshToken($user);

            return [
                'status' => 200,
                'token' => (string)$token,
                'refresh_token' => $refreshToken->urf_token,
            ];

        } elseif (Yii::$app->request->getMethod() == 'DELETE') {
            // Logging out
            if ($userRefreshToken && !$userRefreshToken->delete()) {
                return new ServerErrorHttpException('Рефреш токен не удален.');
            }

            return RequestHelper::success();
        } else {
            return new UnauthorizedHttpException('Пользователь не активен.');
        }
    }

    /**
     * Запрос на сброс пароля
     * @return array
     * @throws ConflictHttpException
     * @throws Exception
     */
    public function actionResetPasswordRequest()
    {
        $model = new ResetPasswordRequestForm();

        if (!$model->load(Yii::$app->request->post(), '') || !$model->save())
            RequestHelper::exceptionModel($model);

        return RequestHelper::success();
    }

    /**
     * Сброс пароля через эмайл
     * @return array
     * @throws ConflictHttpException
     * @throws Exception
     */
    public function actionResetPasswordEmail()
    {
        $model = new ResetPasswordEmailForm();

        if (!$model->load(Yii::$app->request->post(), '') || !$model->resetPassword())
            RequestHelper::exceptionModel($model);

        return RequestHelper::success();
    }

    /**
     * Смена пароля
     * @return array
     * @throws ConflictHttpException
     */
    public function actionChangePassword()
    {
        $model = new ChangePasswordForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || !$model->save())
            RequestHelper::exceptionModel($model);

        return RequestHelper::success();
    }

    public function actionInvitation()
    {
        $model = new InvitationForm(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post(), '') || !$model->save())
            RequestHelper::exceptionModel($model);

        return RequestHelper::success();
    }
}
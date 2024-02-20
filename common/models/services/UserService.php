<?php

namespace common\models\services;

use api\models\helper\ErrorMsgHelper;
use api\models\helper\RequestHelper;
use common\models\entity\Course;
use common\models\entity\Lesson;
use common\models\entity\Promocode;
use common\models\entity\PurchasedCourse;
use common\models\entity\PurchasedWebinar;
use common\models\entity\Setting;
use common\models\entity\Transaction;
use common\models\entity\User;
use common\models\entity\Webinar;
use MessageFormatter;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\ConflictHttpException;

class UserService extends User
{
    public static function addCart($id, $type, $userId)
    {
        $user = self::findOne($userId);

        if (!$user)
            RequestHelper::exception('Пользователь не найден');

        if ($type == 0) {
            $model = Course::findOne($id);
            if ($user->full_access == User::ACCESS_ON || PurchasedCourse::findOne(['user_id' => $userId, 'course_id' => $id]))
                RequestHelper::exception('Уже куплено');
        } else {
            $model = Webinar::findOne($id);
            if (PurchasedWebinar::findOne(['user_id' => $userId, 'webinar_id' => $id]))
                RequestHelper::exception('Уже куплено');

        }

        if (!$model)
            RequestHelper::exception('Корзина/Вебинар не найден');

        $cart = json_decode($user->cart, true);
        if ($cart && in_array($id . '-' . $type, $cart))
            return true;
        $cart[] = $id . '-' . $type;
        $user->cart = json_encode($cart);
        if ($user->save())
            return true;
        else
            return RequestHelper::exceptionModel($user);
    }

    public static function fullAccess($userId)
    {
        $user = self::findOne($userId);
        if (!$user)
            return RequestHelper::exception('Пользователь не найден');

        if ($user->full_access)
            return RequestHelper::exception('Полный доступ уже имеется');

        $price = Course::find()->sum('price');
        $percent = Setting::findOne(['key' => 'full_access_percent']);
        if ($percent && (int)$percent->value <= 100)
            $percent = (int)$percent->value;
        else
            $percent = 10;

        $price *= (100 - $percent) / 100;

        return ['price' => $price];
    }

    public static function deleteCart($id, $type, $userId)
    {
        $user = self::findOne($userId);

        if (!$user)
            RequestHelper::exception('Пользователь не найден');

        $cart = json_decode($user->cart, true);
        if (($key = array_search($id . '-' . $type, $cart)) !== false)
            unset($cart[$key]);

        $user->cart = json_encode($cart);
        if ($user->save())
            return true;
        else
            return RequestHelper::exceptionModel($user);
    }

    public static function deleteCartAll($userId)
    {
        $user = self::findOne($userId);

        if (!$user)
            RequestHelper::exception('Пользователь не найден');

        $user->cart = null;
        if ($user->save())
            return true;
        else
            return RequestHelper::exceptionModel($user);
    }

    public static function deletePromo($userId)
    {
        $user = self::findOne($userId);

        if (!$user)
            RequestHelper::exception('Пользователь не найден');

        $user->promocode_id = null;
        if ($user->save())
            return true;
        else
            return RequestHelper::exceptionModel($user);
    }

    public static function addPromo($userId, $promocode)
    {
        $user = self::findOne($userId);
        if (!$user)
            RequestHelper::exception('Пользователь не найден');

        $promo = Promocode::findOne(['promo' => $promocode, 'status' => Promocode::STATUS_ACTIVE]);
        if (!$promo)
            RequestHelper::exception('Промокод не найден');

        $user->promocode_id = $promo->id;
        if (!$user->save())
            RequestHelper::exceptionModel($user);

        return true;
    }

    public static function checkPromo($promocode)
    {
        $promo = Promocode::findOne(['promo' => $promocode, 'status' => Promocode::STATUS_ACTIVE]);
        if (!$promo)
            RequestHelper::exception('Промокод не найден');

        return ['percent' => $promo->percent];
    }

    public static function listCart($userId)
    {
        $user = self::findOne($userId);

        if (!$user)
            RequestHelper::exception('Пользователь не найден');

        $promocode = Promocode::findOne(['id' => $user->promocode_id, 'status' => Promocode::STATUS_ACTIVE]);

        $percent = 1;
        if ($promocode)
            $percent = (100 - $promocode->percent) / 100;

        $cart = json_decode($user->cart, true);

        $result = [];
        $sum = 0;
        $oldSum = 0;
        if ($cart) {
            foreach ($cart as $item) {
                $lessonArray = [];
                $item = explode('-', $item);
                if ($item[1] == 0) {
                    $model = Course::findOne($item[0]);
                    $count = Lesson::find()->where(['course_id' => $item[0]])->count();
                    $description = MessageFormatter::formatMessage("be",
                        '{n, plural, =0{Нет лекций} =1{1 лекция} one{# лекция} few{# лекции} many{# лекций} other{# лекций}}',
                        ['n' => $count]
                    );
                    $lessons = Lesson::find()->where(['course_id' => $model->id])->orderBy('position')->all();
                    foreach ($lessons as $lesson) {
                        $lessonArray[] = [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'time' => floor($lesson->time / 3600) . gmdate(":i:s", $lesson->time % 3600),
                            'description' => $lesson->description,
                        ];
                    }
                } else {
                    $model = Webinar::findOne($item[0]);
                    $description = 'Вебинар';
                }

                if ($model) {
                    $result[] = [
                        'id' => $model->id,
                        'type' => $item[1],
                        'title' => $model->title,
                        'price' => ceil($model->price),
                        'description' => $description,
                        'photo' => $model->getImgUrl(),
                        'img' => $model->getImgUrl(),
                        'apple_id' => $model->apple_id,
                        'lessons' => $lessonArray,
                    ];
                    $sum += (double)ceil($model->price * $percent);
                    $oldSum += (double)ceil($model->price);
                }
            }
        }

        return ['cart' => $result, 'promocode' => $promocode ? $promocode->promo : null, 'oldSum' => $oldSum, 'sum' => $sum];
    }

    public static function checkout($userId)//todo delete
    {
        $user = self::findOne($userId);

        if (!$user)
            RequestHelper::exception('Пользователь не найден');

        $promocode = Promocode::findOne(['id' => $user->promocode_id, 'status' => Promocode::STATUS_ACTIVE]);
        $cart = json_decode($user->cart, true);

        $sum = 0;
        if ($cart) {
            foreach ($cart as $item) {
                $item = explode('-', $item);
                if ($item[1] == 0) {
                    $model = Course::findOne($item[0]);
                    PurchasedCourseService::addCourse($item[0], $userId);
                } else {
                    $model = Webinar::findOne($item[0]);
                    PurchasedWebinarService::addWebinar($item[0], $userId);
                }

                if ($model)
                    $sum += (double)$model->price;
            }
        }//todo transaction

        $user->promocode_id = null;
        $user->cart = null;
        $user->save();

        if ($promocode) {
            $promocode->status = Promocode::STATUS_INACTIVE;
            $promocode->save();
        }

        return true;
    }

    public static function payApple($userId, $payId)
    {
        $user = self::findOne($userId);
        if (!$user)
            RequestHelper::exception('Пользователь не найден');

        $promocode = Promocode::findOne(['id' => $user->promocode_id, 'status' => Promocode::STATUS_ACTIVE]);
        $cart = json_decode($user->cart, true);

        $sum = 0;
        if ($cart) {
            foreach ($cart as $item) {
                $item = explode('-', $item);
                if ($item[1] == 0) {
                    $model = Course::findOne($item[0]);
                    PurchasedCourseService::addCourse($item[0], $userId);
                } else {
                    $model = Webinar::findOne($item[0]);
                    PurchasedWebinarService::addWebinar($item[0], $userId);
                }

                if ($model)
                    $sum += (double)$model->price;
            }
        }

        $result = TransactionService::getSumWithPromo($user, Transaction::TYPE_APPLE_PAY);
        $transaction = $result['transaction'];
        $transaction->status = Transaction::STATUS_PAID;
        $transaction->pay_id = $payId;
        if (!$transaction->save())
            return RequestHelper::exceptionModel($transaction);

        $user->promocode_id = null;
        $user->cart = null;
        $user->save();

        if ($promocode) {
            $promocode->count = 0;
            $promocode->status = Promocode::STATUS_INACTIVE;
            $promocode->save();
        }

        return true;
    }

    public static function checkoutFullAccess($userId)
    {
        $user = self::findOne($userId);
        if (!$user)
            RequestHelper::exception('Пользователь не найден');

        $user->full_access = User::ACCESS_ON;
        $user->save();
        return true;
    }

    public static function countCart($userId)
    {
        $user = self::findOne($userId);

        if (!$user)
            RequestHelper::exception('Пользователь не найден');

        $cart = json_decode($user->cart, true);

        return ['count' => count($cart)];}

    /**
     * @param $userId
     * @return User|null
     */
    public static function getActiveUser($userId)
    {
        $user = self::findOne(['id' => $userId]);
        if (!$user)
            throw new ConflictHttpException('Пользователь не найден');

        if ($user->status != User::STATUS_ACTIVE)
            throw new ConflictHttpException('Пользователь не активен');
        return $user;
    }

    /**
     * @param $email
     * @return UserService|null
     */
    public static function getActiveUserByEmail($email)
    {
        $user = self::findOne(['email' => $email]);
        if (!$user)
            throw new ConflictHttpException('Пользователь не найден');

        if ($user->status != User::STATUS_ACTIVE)
            throw new ConflictHttpException('Пользователь не активен');
        return $user;
    }

    /**
     * @param $email
     * @return UserService|null
     */
    public static function getUserByEmail($email)
    {
        $user = self::findOne(['email' => $email]);
        if (!$user)
            throw new ConflictHttpException('Пользователь не найден');

        return $user;
    }

    public static function deleteUser($userId)
    {
        $user = self::findOne($userId);
        if (!$user)
            throw new ConflictHttpException('Пользователь не найден');

        if ($user->id == 1 || $user->role == User::ROLE_ADMIN)
            throw new ConflictHttpException('Нельзя удалить админа');

        $user->delete();
        return true;
    }

    /**
     * @param $fullname
     * @param $email
     * @param $phone
     * @param $password
     * @return UserService
     * @throws ConflictHttpException
     * @throws Exception
     */
    public static function createUser($fullname, $email, $phone, $password)
    {
        $user = new self();
        $user->fullname = $fullname;
        $user->email = $email;
        $user->phone = $phone;
        $user->role = self::ROLE_USER;
        $user->setPassword($password);
        $user->status = self::STATUS_REGISTRATION;
        $user->generateAuthKey();
        $user->ref_string = Yii::$app->security->generateRandomString();

        if (!$user->save())
            throw new ConflictHttpException(ErrorMsgHelper::getErrorMsg($user));

        return $user;
    }

    /**
     * @param $userId
     * @param $fullname
     * @param $ava
     * @param $email
     * @param $oldPassword
     * @param $newPassword
     * @return UserService|null
     * @throws ConflictHttpException
     * @throws Exception
     */
    public static function updateUser($userId, $fullname = null, $ava = null, $email = null, $phone = null, $oldPassword = null, $newPassword = null)
    {
        $user = self::findOne($userId);

        if (!$user)
            RequestHelper::exception();

        if ($fullname)
            $user->fullname = $fullname;

        if ($ava)
            $user->ava = $ava;

        if ($email)
            $user->email = $email;

        if ($phone)
            $user->phone = $phone;

        if ($oldPassword && $newPassword) {
            if (!password_verify($oldPassword, $user->password_hash))
                throw new ConflictHttpException('Неверный пароль');

            $user->setPassword($newPassword);
        }

        if (!$user->save())
            throw new ConflictHttpException(ErrorMsgHelper::getErrorMsg($user));

        return $user;
    }

    /**
     * @param $email
     * @param $phone
     * @return array|ActiveRecord|null
     */
    public static function getUniqueUser($email, $phone = null)
    {
        $user = self::find();
        if ($phone)
            $user = $user->where([['email' => $email], ['phone' => $phone]]);
        else
            $user = $user->where(['email' => $email]);

        $user = $user->andWhere(['!=', 'status', self::STATUS_DELETED])
            ->one();
        if ($user)
            throw new ConflictHttpException('Телефон или почта уже используются');
        return $user;
    }

    /**
     * @param $email
     * @param $userId
     * @return array|ActiveRecord|null
     */
    public static function getUniqueUserByEmail($email, $userId)
    {
        $user = self::find()
            ->where(['email' => $email])
            ->andWhere(['!=', 'status', self::STATUS_DELETED])
            ->andWhere(['!=', 'id', $userId])
            ->one();
        if ($user)
            throw new ConflictHttpException('Почта уже используется');
        return $user;
    }

    /**
     * @param $ref
     * @return array|ActiveRecord|null
     */
    public static function getRefUser($ref)
    {
        $user = self::find()
            ->where(['ref_string' => trim($ref)])
            ->andWhere(['<', 'ref_count', 10])
            ->one();
        if (!$user)
            throw new ConflictHttpException('Реферальный пользователь не найден');
        return $user;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
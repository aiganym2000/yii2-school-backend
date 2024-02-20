<?php


namespace api\models\forms;

use common\models\entity\Achievement;
use common\models\entity\AchievementUser;
use common\models\entity\Course;
use common\models\entity\Notification;
use common\models\entity\Setting;
use common\models\entity\User;
use common\models\entity\UserLessons;
use common\models\helpers\FCMPushHelper;
use common\models\services\AchievementService;
use common\models\services\NotificationService;
use common\models\services\QuestionService;
use yii\base\Model;

class QuestionFinishForm extends Model
{
    public $courseId;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function rules()
    {
        return [
            [['courseId'], 'required'],
            [['courseId'], 'integer'],
        ];
    }

    public function save()
    {
        if (!$this->validate())
            return false;
        $user = User::findOne($this->userId);
        $course = Course::findOne($this->courseId);

        $result = QuestionService::finishLesson($this->courseId, $this->userId);

        QuestionService::RedoLesson($this->courseId, $this->userId);
        if ($result['passed']) {
            $answered_count = UserLessons::find()
                ->where(['course_id' => $this->courseId])
                ->andWhere(['user_id' => $this->userId])
                ->andWhere('score=question_count')
                ->count('DISTINCT(course_id)');

            if ($answered_count) {
                $letter = AchievementUser::find()
                    ->where(['user_id' => $this->userId])
                    ->orderBy(['achievement_id' => SORT_DESC])
                    ->one();
                if ($letter)
                    $achievementId = $letter->achievement_id + 1;
                else
                    $achievementId = 1;

                if (Achievement::findOne($achievementId)) {
                    $achievement = AchievementService::createAchievement($achievementId, $this->userId, strip_tags($course->title), AchievementUser::TYPE_COURSE);
                    $text = 'Вы сдали тест на 100%! Мы гордимся вашими успехами! Продолжайте учиться.';
                    $notification = NotificationService::createNotification('Новое достижение!', $text, $this->userId, Notification::STATUS_SEND);
                    if ($user->f_token)
                        $response = FCMPushHelper::sendNotification($notification->title, $notification->description, $user->f_token);

                    $limit = Setting::findOne(['key' => 'percent_limit']);
                    if ($limit && (int)$limit->value <= 100)
                        $limit = (int)$limit->value * 0.01;
                    else
                        $limit = 0.5;

                    $userLesson = UserLessons::find()
                        ->where(['user_id' => $this->userId])
                        ->andWhere('score>=' . $limit . '*question_count')
                        ->count('DISTINCT(course_id)');
                    if ($userLesson == 9) {
                        $achievement = AchievementService::createAchievement($achievementId + 1, $this->userId, '', AchievementUser::TYPE_ALL);
                        $text = 'Вы прошли все курсы. Поздравляем! Движение “Возрождение” радо принять вас в свои ряды. Не забывайте приходить на наши мероприятия. Это только первый шаг!';
                        $notification = NotificationService::createNotification('Новое достижение!', $text, $this->userId, Notification::STATUS_SEND);
                        if ($user->f_token)
                            $response = FCMPushHelper::sendNotification($notification->title, $notification->description, $user->f_token);

                        $webinarA = AchievementUser::findOne(['user_id' => $this->userId, 'type' => AchievementUser::TYPE_WEBINAR]);
                        if ($webinarA) {
                            $text = 'Вы собрали слово. Теперь Возрождение с вами навсегда! Скоро вы получите бонус.';
                            $notification = NotificationService::createNotification('Все достижения собраны!', $text, $this->userId, Notification::STATUS_SEND);
                            if ($user->f_token)
                                $response = FCMPushHelper::sendNotification($notification->title, $notification->description, $user->f_token);
                        }
                    }
                }
            }
        }
        return ['percent' => $result['percent'], 'passed' => $result['passed']];
    }
}
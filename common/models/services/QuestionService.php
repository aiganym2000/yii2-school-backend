<?php

namespace common\models\services;

use api\models\helper\RequestHelper;
use common\models\entity\Course;
use common\models\entity\PurchasedCourse;
use common\models\entity\Question;
use common\models\entity\Setting;
use common\models\entity\UserAnswer;
use common\models\entity\UserLessons;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

class QuestionService extends Question
{
    /**
     * @param $courseId
     * @return QuestionService[]
     */
    public static function getQuestions($courseId, $userId)
    {
        $model = Course::findOne($courseId);
        if (!$model)
            RequestHelper::exception();

        $pCourse = PurchasedCourse::findOne(['course_id' => $courseId, 'user_id' => $userId]);
        if (!$pCourse)
            throw new ForbiddenHttpException('Курс не куплен');

        return self::find()
            ->where(['course_id' => $courseId])
            ->orderBy('position')
            ->all();
    }

    public static function finishLesson($courseId, $userId)
    {
        $questions = Question::findAll(['course_id' => $courseId]);
        $productArray = ArrayHelper::map($questions, 'id', 'id');
        $attributeIds = implode(',', $productArray);
        if ($attributeIds && Yii::$app->user->id) {
            $answered_count = UserAnswer::find()
                ->where('question_id IN (' . $attributeIds . ')')
                ->andWhere(['user_id' => $userId])
                ->andWhere(['right' => 1])
                ->count('DISTINCT(question_id)');
        } else {
            $answered_count = 0;
        }

        $limit = Setting::findOne(['key' => 'percent_limit']);
        if ($limit && (int)$limit->value <= 100)
            $limit = (int)$limit->value;
        else
            $limit = 50;

        $percent = 0;
        if ($questions)
            $percent = round(($answered_count / count($questions)) * 100);
        $passed = $percent >= $limit ? 1 : 0;

        $uLesson = new UserLessons();
        $uLesson->user_id = $userId;
        $uLesson->course_id = $courseId;
        $uLesson->score = $answered_count;
        $uLesson->passed = $passed;
        $uLesson->question_count = count($questions);

        if (!$uLesson->save())
            RequestHelper::exceptionModel($uLesson);

        $questions = Question::findAll(['course_id' => $courseId]);
        $productArray = ArrayHelper::map($questions, 'id', 'id');
        $attributeIds = implode(',', $productArray);
        if ($attributeIds && Yii::$app->user->id) {
            $answers = UserAnswer::find()
                ->where('question_id IN (' . $attributeIds . ')')
                ->andWhere(['user_id' => $userId])
                ->all();
            foreach ($answers as $answer) {
                $answer->delete();
            }
        }

        return ['question' => $uLesson, 'percent' => $percent, 'passed' => $passed];
    }

    public static function RedoLesson($courseId, $userId)
    {
        $questions = Question::findAll(['course_id' => $courseId]);
        $productArray = ArrayHelper::map($questions, 'id', 'id');
        $attributeIds = implode(',', $productArray);
        if ($attributeIds && Yii::$app->user->id) {
            $answers = UserAnswer::find()
                ->where('question_id IN (' . $attributeIds . ')')
                ->andWhere(['user_id' => $userId])
                ->all();
            foreach ($answers as $answer) {
                $answer->delete();
            }
        }

        return true;
    }

    /**
     * @param $type
     * @param $answer
     * @return false|string
     */
    public static function getAnswer($type, $answer)
    {
        if (!$answer) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'NO_ANSWER'));
            return false;
        }

        if ($type == Question::TYPE_MATCH) {
            $first = [];
            $second = [];

            $id = 0;
            foreach ($answer as $a) {
                if ($a['first'] || $a['second']) {
                    $first[] = ['text' => $a['first'], 'id' => $id];
                    $second[] = ['text' => $a['second'], 'first_id' => $id];
                    $id++;
                }
            }

            $answer = ['first' => $first, 'second' => $second];
        } elseif ($type == Question::TYPE_PLACEMENT) {
            $array = [];

            $id = 1;
            foreach ($answer as $a) {
                if ($a['text']) {
                    $array[] = ['text' => $a['text'], 'position' => $id];
                    $id++;
                }
            }

            $answer = $array;
        } elseif ($type == Question::TYPE_ONE_ANSWER) {
            $array = [];
            $r = Yii::$app->request->post('r');
            if ($r === null) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'NO_RADIO'));
                return false;
            }

            $id = 0;
            foreach ($answer as $a) {
                if ($a['text']) {
                    $right = ($r == $id) ? 1 : 0;
                    $array[] = ['text' => $a['text'], 'right' => $right];
                    $id++;
                }
            }

            $answer = $array;
        } else {
            $array = [];
            $c = Yii::$app->request->post('checkbox');
            if ($c === null) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'NO_CHECKBOX'));
                return false;
            }

            $id = 0;
            foreach ($answer as $a) {
                if ($a['text']) {
                    $right = in_array($id, $c) ? 1 : 0;
                    $array[] = ['text' => $a['text'], 'right' => $right];
                    $id++;
                }
            }

            $answer = $array;
        }

        return json_encode($answer);
    }

    /**
     * @param $type
     * @return false|string
     */
    public static function getView($type)
    {
        if ($type == Question::TYPE_MATCH) {
            $view = '_form1';
        } elseif ($type == Question::TYPE_PLACEMENT) {
            $view = '_form2';
        } elseif ($type == Question::TYPE_ONE_ANSWER) {
            $view = '_form3';
        } elseif ($type == Question::TYPE_SEVERAL_ANSWERS) {
            $view = '_form4';
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'NO_TYPE'));
            return false;
        }

        return $view;
    }
}
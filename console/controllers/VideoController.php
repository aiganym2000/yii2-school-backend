<?php

namespace console\controllers;

use common\models\entity\Course;
use common\models\entity\Lesson;
use common\models\services\AuthorService;
use common\models\services\CourseService;
use common\models\services\LessonService;
use getID3;
use Yii;
use yii\console\Controller;

class VideoController extends Controller
{
    public function actionRecalculate()
    {
        $courses = Course::find()->all();
        foreach ($courses as $course) {
            $lessons = Lesson::findAll(['course_id' => $course->id]);
            $sum = 0;
            foreach ($lessons as $lesson) {
                $getID3 = new getID3();
                $getID3 = $getID3->analyze(Yii::getAlias('@static') . '/web/video/' . $lesson->video);
                $lesson->time = $getID3['playtime_seconds'];
                $lesson->save();
                $sum += $getID3['playtime_seconds'];
            }
            $course->time = $sum;
            $course->save();
        }
        $this->stdout('Done!' . PHP_EOL);
    }

    public function actionRename($oldName, $newName)
    {
        $lesson = LessonService::find()
            ->filterWhere(['like', 'video', $oldName])
            ->one();
        if ($lesson) {
            $lesson->video = str_replace($oldName, $newName, $lesson->video);
            $lesson->save();
        }

        $course = CourseService::find()
            ->filterWhere(['like', 'trailer', $oldName])
            ->one();
        if ($course) {
            $course->trailer = str_replace($oldName, $newName, $course->trailer);
            $course->short_description = 'sadfsdf';
            $course->save();
        }

        $author = AuthorService::find()
            ->filterWhere(['like', 'video', $oldName])
            ->one();
        if ($author) {
            $author->video = str_replace($oldName, $newName, $author->video);
            $author->save();
        }

        $this->stdout('Done!' . PHP_EOL);
    }
}
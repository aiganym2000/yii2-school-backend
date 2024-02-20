<?php

namespace api\models\forms;

use api\models\helper\RequestHelper;
use common\models\entity\Question;
use common\models\entity\UserAnswer;
use yii\base\Model;

class QuestionCreateForm extends Model
{
    public $answer;
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function save()
    {
        foreach ($this->answer as $an) {
            $status = 0;

            $question = Question::findOne($an['question_id']);
            if (!$question)
                RequestHelper::exception('Вопрос не найден');

            $rAnswer = json_decode($question->answer, true);
            if ($question->type == Question::TYPE_ONE_ANSWER) {
                $right = array_search(1, array_column($rAnswer, 'right'));
                if (isset($rAnswer[$right]) && in_array($rAnswer[$right]['text'], $an['answer']))
                    $status = 1;
            } else if ($question->type == Question::TYPE_SEVERAL_ANSWERS) {
                $rights = array_keys(array_column($rAnswer, 'right'), 1);
                $rights = array_intersect_key($rAnswer, array_flip($rights));
                $rights = array_column($rights, 'text');
                if (!array_diff($rights, $an['answer']) && !array_diff($an['answer'], $rights))
                    $status = 1;
            } else if ($question->type == Question::TYPE_MATCH) {
                $first = array_column($rAnswer['first'], 'text');
                $second = array_column($rAnswer['second'], 'text');
                $rights = [];
                for ($i = 0; $i < count($first); $i++) {
                    $rights[] = ['left' => $first[$i], 'right' => $second[$i]];
                }
                if ($rights == $an['answer'])
                    $status = 1;
            } else if ($question->type == Question::TYPE_PLACEMENT) {
                $rights = array_column($rAnswer, 'text');
                if ($rights === $an['answer'])
                    $status = 1;
            }

            $table = new UserAnswer();
            $table->user_id = $this->userId;
            $table->question_id = $an['question_id'];
            $table->answer = json_encode($an['answer'], true);
            $table->right = $status;
            $table->save();
        }

        return true;
    }
}
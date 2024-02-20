<?php

namespace console\controllers;


use common\models\entity\Course;
use common\models\entity\RandomSeed;
use yii\console\Controller;

class RandomSeedController extends Controller
{
    public function actionGenerate()
    {
        $product_count = Course::find()->count();
        $random_count = RandomSeed::find()->count();
        $php_random_max = 500;

        if ($random_count < $product_count + $php_random_max) {
            $count = $product_count + $php_random_max - $random_count;
            for ($i = 0; $i < $count; $i++) {
                $random = new RandomSeed();
                $random->random_seed = rand(0, 500);
                $random->save();
            }
        }

        $this->stdout('Done!' . PHP_EOL);
    }
}
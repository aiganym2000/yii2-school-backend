<?php

namespace common\models\integration;

use Yii;

class BitrixApi
{
    /**
     * Создать задачу
     *
     * @param $title
     * @param $description
     * @param $responsible
     * @param $token
     * @return false|mixed|\stdClass|string
     */
    public static function createTask($title, $description,$responsible,$token)
    {
        $json = self::query('tasks.task.add.json?',['fields[TITLE]' => $title, 'fields[RESPONSIBLE_ID]' => $responsible, 'fields[DESCRIPTION]' => $description],$token);
        return $json;
    }

    /**
     * Получение Лида
     *
     * @param $id
     * @param string $token
     * @return false|mixed|\stdClass|string
     */
    public static function getDealData($id,$token = "th720u2vk2h6gijo")
    {
        $json = self::query('crm.deal.get.json?',['id' => $id],$token);
        return $json;
    }

    /**
     * Получение Товаров лида
     *
     * @param $id
     * @param string $token
     * @return false|mixed|\stdClass|string
     */
    public static function getProductData($id,$token = "th720u2vk2h6gijo")
    {
        $json = self::query('crm.deal.productrows.get.json?',['id' => $id],$token);
        return $json;
    }

    /**
     * Получение всех сотрудников
     *
     * @return false|mixed|\stdClass|string
     */
    public static function getAllUsers()
    {
        $json = self::query('user.search',[],User::findOne(['id'=>Yii::$app->user->id])->bitrix_token);
        return $json;
    }

    /**
     * Генератор запросов
     *
     * @param $type
     * @param $arr
     * @param $token
     * @return false|mixed|\stdClass|string
     */
    public static function query($type,$arr,$token)
    {
        $query = '';
        foreach ($arr as $key => $value) {
            if ($value != null) {
                $query = $query . '&' . $key . '=' . $value;
            }
        }
        $json = Yii::$app->params['bitrix_default_url'] . '/102/' . $token. '/'.$type. '/'.$query;
//        throw new ConflictHttpException(print_r($json, true));
        if ($json = @file_get_contents($json)){
            $json = json_decode($json);
            $json->status = 200;
            return $json;
        }else{
//            $json = json_decode($json);
            $json = new \stdClass();
            $json->status = 401;
            $json->message = 'Token error';
            return $json;
        }
    }


}
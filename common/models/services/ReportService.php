<?php

namespace common\models\services;

use api\models\helper\ErrorMsgHelper;
use api\models\helper\RequestHelper;
use common\models\entity\Report;
use yii\db\ActiveRecord;
use yii\web\ConflictHttpException;

class ReportService extends Report
{
    /**
     * @param $email
     * @return array|ActiveRecord|null
     * @throws ConflictHttpException
     */
    public static function getActiveRequest($email)
    {
        return ($model = self::find()
            ->where(['email' => $email])
            ->andWhere(['status' => self::STATUS_SEND])
            ->orderBy(['id' => SORT_DESC])
            ->one()) ? $model : RequestHelper::exception();
    }

    /**
     * @param $email
     * @param $code
     * @return ReportService
     * @throws ConflictHttpException
     */
    public static function createReport($email, $code)
    {
        $report = new self();
        $report->email = $email;
        $report->code = password_hash($code, PASSWORD_BCRYPT);
        $report->status = self::STATUS_SEND;
        if (!$report->save())
            throw new ConflictHttpException(ErrorMsgHelper::getErrorMsg($report));

        return $report;
    }

    public static function unactivateExReports($email)
    {
        return Report::updateAll(['status' => self::STATUS_CONFIRMED], ['email' => $email, 'status' => self::STATUS_SEND]);
    }
}
<?php


namespace backend\modules\admin\models;

use common\models\entity\Course;
use common\models\entity\Promocode;
use common\models\entity\Transaction;
use common\models\services\TransactionService;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Writer_Excel2007;

class PromocodeDataModel
{
    public function getit($start, $end)
    {
        $objPHPExcel = new PHPExcel();
        $result = Promocode::find()
            ->where(['status' => Promocode::STATUS_INACTIVE]);
        if ($start)
            $result = $result->andWhere(['>=', 'created_at', date('Y-m-d 01:00:00', strtotime($start))]);
        if ($end)
            $result = $result->andWhere(['<=', 'created_at', date('Y-m-d 01:00:00', strtotime($end))]);
        $result = $result->orderBy(['id' => SORT_DESC])
            ->all();

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Промокод');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Курс');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'ID транзакции');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Почта пользователя');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Дата покупки');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'ID промокода');

        $objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);

        $rowCount = 2;
        foreach ($result as $row) {
            $transaction = Transaction::findOne(['promocode_id' => $row->id, 'status' => Transaction::STATUS_PAID]);
            if ($transaction) {
                $cart = json_decode($transaction->cart, true);
                if ($cart) {
                    foreach ($cart as $item) {
                        $item = explode('-', $item);
                        if ($item[1] == TransactionService::CART_TYPE_COURSE) {
                            $model = Course::findOne($item[0]);

                            if ($model) {
                                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->promo);
                                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, strip_tags($model->title));
                                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $transaction->id);
                                if ($transaction->user)
                                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $transaction->user->email);
                                else
                                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, '');
                                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $transaction->created_at);
                                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->id);

                                $rowCount++;
                            }
                        }//todo webinar and full access
                    }
                }
            }
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Promocodes.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
<?php


namespace backend\modules\admin\models;

use common\models\entity\User;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Writer_Excel2007;

class UserDataModel
{
    public function getit($start, $end)
    {
        $objPHPExcel = new PHPExcel();
        $result = User::find();
        if ($start)
            $result = $result->andWhere(['>=', 'created_at', strtotime($start . ' 00:00:00')]);
        if ($end)
            $result = $result->andWhere(['<=', 'created_at', strtotime($end . ' 23:59:59')]);
        $result = $result->orderBy(['id' => SORT_DESC])
            ->all();

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Имя');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Почта');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Дата регистрации');

        $objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);

        $rowCount = 2;
        foreach ($result as $row) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->id);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->fullname);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->email);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, date('Y-m-d H:i:s', $row->created_at));

            $rowCount++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Users.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
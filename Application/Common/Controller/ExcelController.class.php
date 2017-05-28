<?php
namespace Common\Controller;
use Think\Controller;
/**
 * 后台公共控制器
 */
class ExcelController extends Controller {
	public function reception_statistics($excelData, $startcol, $endcol, $title, $file){
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \PHPExcel();
		//设置当前的sheet
		$objPHPExcel->setActiveSheetIndex(0);

		$row = 1;
		$marge = $startcol . $row . ':' . $endcol . $row;
		$objPHPExcel->getActiveSheet()->mergeCells($marge);
		$objPHPExcel->getActiveSheet()->setCellValue($startcol . $row, $title);
		$checkrow = $row;
		if($excelData['intr']){
			$row++;
			$marge = $startcol . $row . ':' . $endcol . $row;
			$objPHPExcel->getActiveSheet()->mergeCells($marge);
			$objPHPExcel->getActiveSheet()->setCellValue($startcol . $row, " " . $excelData['intr']);
			$checkrow = $row;
		}

		if($excelData['count']){
			self::setReceptionCount($objPHPExcel, $excelData['count'], $row, $checkrow);
		}
		if($excelData['list']){
			self::setReceptionList($objPHPExcel, $excelData['list'], $row, $checkrow, $excelData['biz']);
		}

		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		ob_end_clean();//去除中文乱码
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$file.'-'.date('Ymd').'.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	}
	public function setReceptionCount(&$objPHPExcel, $excelData, &$row, $checkrow = 1){
		$colChar = array('A', 'B', 'C', 'D', 'E', 'F');
		$start_row = $row;
		//标题
		$row++;
		$rowTitle = array('待客', '应收', '退款', '会员折扣', '券折扣', '实收');
		foreach($rowTitle as $key => $v){
			$objPHPExcel->getActiveSheet()->setCellValue($colChar[$key] . $row, $v);
		}

		foreach($excelData as $v){
			$row++;
			if(is_array($v)){
				foreach($colChar as $key => $vo){
					$objPHPExcel->getActiveSheet()->setCellValue($vo . $row, $v[$key]);
				}
			}else{
				$objPHPExcel->getActiveSheet()->mergeCells($colChar[0] . $row . ':' . end($colChar) . $row);
				$objPHPExcel->getActiveSheet()->setCellValue($colChar[0] . $row, $v);
			}
		}
		if($start_row == $checkrow){
			foreach($colChar as $key => $vo){
				if($vo == 'F'){
					$objPHPExcel->getActiveSheet()->getColumnDimension($vo)->setWidth(50);
				}else{
					$objPHPExcel->getActiveSheet()->getColumnDimension($vo)->setWidth(15);
				}
			}
		}
		return;
	}
	public function setReceptionList(&$objPHPExcel, $excelData, &$row, $checkrow = 1, $is_biz){
		if($is_biz == 1){
			$colChar = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');
			$rowTitle = array('订单号', '门店', '开房时间', '包厢号', '房型', '消费形式', '定房方式', '支付形式', '支付金额', '退款', '优惠券', '会员折扣', '实际支付');
		}else{
			$colChar = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L');
			$rowTitle = array('订单号', '开房时间', '包厢号', '房型', '消费形式', '定房方式', '支付形式', '支付金额', '退款', '优惠券', '会员折扣', '实际支付');
		}
		$start_row = $row;

		//标题
		$row++;
		foreach($rowTitle as $key => $v){
			$objPHPExcel->getActiveSheet()->setCellValue($colChar[$key] . $row, $v);
		}

		foreach($excelData as $v){
			$row++;
			foreach($colChar as $key => $vo){
				$objPHPExcel->getActiveSheet()->setCellValue($vo . $row, " " . $v[$key]);
			}

		}
		if($start_row == $checkrow){
			foreach($colChar as $key => $vo){
				$objPHPExcel->getActiveSheet()->getColumnDimension($vo)->setWidth(22);
			}
		}
		return;
	}

	/**
	 * 分类汇总表excel
	 */
	public function food_category_excel($excelData, $file){
		vendor('PHPExcel.Classes.PHPExcel');
		$objPHPExcel = new \PHPExcel();
		//设置当前的sheet
		$objPHPExcel->setActiveSheetIndex(0);

		$row = 0;
		if($excelData['title']){
			$row++;
			$marge = 'A' . $row . ':' . 'B' . $row;
			$objPHPExcel->getActiveSheet()->mergeCells($marge);
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $excelData['title']);
		}

		if($excelData['intr']){
			$row++;
			$marge = 'A' . $row . ':' . 'B' . $row;
			$objPHPExcel->getActiveSheet()->mergeCells($marge);
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, " " . $excelData['intr']);
		}

		$row++;

		$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, "分类");
		$objPHPExcel->getActiveSheet()->setCellValue('B' . $row, "应收");
		$objPHPExcel->getActiveSheet()->setCellValue('C' . $row, "会员优惠");
		$objPHPExcel->getActiveSheet()->setCellValue('D' . $row, "券折");
		$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, "实收");

		foreach($excelData['data'] as $v){
			$row++;
			$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $v[0]);
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $v[1]);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $v[2]);
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $v[3]);
			$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $v[4]);
		}

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);

		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		ob_end_clean();//去除中文乱码
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$file.'-'.date('Ymd').'.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	}

	public function importData($filepath,$titleReplace=array()){
		vendor('PHPExcel.Classes.PHPExcel.IOFactory');

		$file = explode('/',str_replace('\\','/',$filepath));
		$file = end($file);

		$type = strtolower( pathinfo($file, PATHINFO_EXTENSION) );

		$path = $filepath;

		if (!file_exists($path)) {
			die('no file!');
		}

		//根据不同类型分别操作
		if( $type=='xlsx'||$type=='xls' ){
			$objPHPExcel = \PHPExcel_IOFactory::load($path);
		}else if( $type=='csv' ){
			$objReader = \PHPExcel_IOFactory::createReader('CSV')
				->setDelimiter(',')
				->setInputEncoding('GBK') //不设置将导致中文列内容返回boolean(false)或乱码
				->setEnclosure('"')
				->setLineEnding("\r\n")
				->setSheetIndex(0);
			$objPHPExcel = $objReader->load($path);

		}else{
			//die('Not supported file types!');
			return false;
		}

		//选择标签页

		$sheet = $objPHPExcel->getSheet(0);

		//获取行数与列数,注意列数需要转换
		$highestRowNum = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		$highestColumnNum = \PHPExcel_Cell::columnIndexFromString($highestColumn);

		//取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
		$filed = array();
		for($i=0; $i<$highestColumnNum;$i++){
			$cellName = \PHPExcel_Cell::stringFromColumnIndex($i).'1';
			$cellVal = $sheet->getCell($cellName)->getValue();//取得列内容
			$filed []= $cellVal;
		}
		if(!empty($titleReplace)){
			foreach ($filed as $key => $value) {
				$filed_replaced[$key] = $titleReplace[$value];
			}
			$filed = $filed_replaced;
		}

		//开始取出数据并存入数组
		$data = array();
		for($i=2;$i<=$highestRowNum;$i++){//ignore row 1
			$row = array();
			for($j=0; $j<$highestColumnNum;$j++){
				$cellName = \PHPExcel_Cell::stringFromColumnIndex($j).$i;
				$cellVal = $sheet->getCell($cellName)->getValue();
				$row[ $filed[$j] ] = $cellVal;
			}
			$nullNum = array();
			foreach($row as &$v){
				if(is_null($v)){
					$nullNum[] = true;
					$v = '';
				}
			}unset($v);
			if(count($row)>count($nullNum)){
				$data []= $row;
			}
		}

		if(empty($data)){
			return false;
		}

		//完成，可以存入数据库了
		return $data;
		//var_dump($data);
	}
}
<?php

class FinanceController extends AdminController{
	public function actionDaily(){
		if(isset($_GET['nowtime']))
			$nowtime = $_GET['nowtime'];
		else
			$nowtime = date('Y-m-d');
		
		$model = new Order;
		$model->unsetAttributes();
		$model->nowtime = $nowtime;

		$this->render('daily',array(
			'nowtime' => $nowtime,
			'model' => $model,
		));
	}

	public function actionMonth(){
		if(isset($_GET['nowtime']))
			$nowtime = $_GET['nowtime'];
		else
			$nowtime = date('Y-m');
		
		$model = new Order;
		$model->unsetAttributes();
		$model->nowtime = $nowtime;

		$this->render('month',array(
			'nowtime' => $nowtime,
			'model' => $model,
		));
	}

	public function actionYear(){
		if(isset($_GET['nowtime']))
			$nowtime = $_GET['nowtime'];
		else
			$nowtime = date('Y');
		
		$model = new Order;
		$model->unsetAttributes();
		$model->nowtime = $nowtime;

		$this->render('year',array(
			'nowtime' => $nowtime,
			'model' => $model,
		));
	}

	public function actionCustom(){
		$model = new Order;
		$model->unsetAttributes();

		$this->render('custom',array(
			'model' => $model,
		));
	}

	public function actionMenu(){
		if(isset($_GET['nowtime']))
			$nowtime = $_GET['nowtime'];
		else
			$nowtime = date('Y-m');

		$category = MenuCategory::model()->findAll('1 order by porder');
		
		//['2016-09-22'=>[1=>[11,22],2=>[22,33]]]
		$arr = array();

		foreach($category as $v){
			$sql = "select mc.id as cid,SUM(quantity) as allcount,o.req_date,SUM(case o.pay_status when 1 then quantity else 0 end) as paycount";
			$sql .= " from order_detail od";
			$sql .= " left join `order` o on o.id=od.order_id";
			$sql .= " left join menu m on od.gid=m.id";
			$sql .= " left join menu_category mc on m.cid=mc.id";
			$sql .= " where o.pay_status=1 and (o.order_status=1 or o.order_status=4) and mc.id=".$v->id." and DATE_FORMAT(o.req_date,'%Y-%m')='".$nowtime."'";
			$sql .= " group by o.req_date";
			$sql .= " order by o.req_date desc";

			$tmpData = Yii::app()->db->createCommand($sql)->queryAll();
			foreach($tmpData as $vv){
				if(empty($arr[$vv['req_date']]))
					$arr[$vv['req_date']] = array();

				$arr[$vv['req_date']][$v->id] = array($vv['allcount'],$vv['paycount']);
			}
		}

		$staticArr = array();
		foreach($arr as $k=>$v){
			foreach($v as $kk=>$vv){
				if(empty($staticArr[$kk])){
					$staticArr[$kk] = array(0);
				}
				
				$staticArr[$kk][0] += $vv[1];
			}
		}
		
		$this->render('menu',array(
			'nowtime'=>$nowtime,
			'category'=>$category,
			'arr'=>$arr,
			'staticArr'=>$staticArr,
		));
	}

	public function actionExport(){
		$this->layout = false;
		$type = isset($_GET['type'])?$_GET['type']:'';
		$nowtime = isset($_GET['nowtime'])?$_GET['nowtime']:'';
		$stime = isset($_GET['starttime'])?$_GET['starttime']:'';
		$etime = isset($_GET['endtime'])?$_GET['endtime']:'';

		if($type==''){
			throw new CHttpException(404);
		}

		switch($type){
			case 'day':
				$arr = Yii::app()->cache->get('dailyStatic');
				if(!$arr){
					$orders = Order::model()->findAll('req_date="'.$nowtime.'" and pay_status=1 and (order_status=1 or order_status=4) order by pay_status desc');

					$arr = array();

					foreach($orders as $k=>&$v){
						$tmpArr = array();
						$tmpArr = $v->attributes;
						$tmpArr['order_time'] = isset($v->order_time)?date("Y-m-d H:i:s",$v->order_time):"";
						$tmpArr['buyer'] = isset($v->buyer)?$v->buyer->username:"用户已注销";
						$tmpArr['addr_mobile'] = isset($v->addr)?$v->addr->mobile:"地址已注销";
						$tmpArr['addr'] = isset($v->addr)?$v->addr->address:"地址已注销";

						$tmpSum = 0;
						$tmpDes = '';

						foreach($v->details as $dv){
							$tmpSum += $dv->quantity;
							$tmpDes .= $dv->menu->name." : ".$dv->quantity."份; \r\n";
						}

						$tmpArr['staticSum'] = $tmpSum;
						$tmpArr['staticDetail'] = $tmpDes;

						$arr[] = $tmpArr;
					}
				}

				if(!count($arr)){
					Yii::app()->user->setFlash('error','导出失败，'.$nowtime.' 没有数据');
					$this->redirect('/admin/finance/daily');
				}else{
					header("Content-type:text/html;charset=utf-8");
					error_reporting(E_ALL);
					date_default_timezone_set('Europe/London');
					$objPHPExcel = new PHPExcel();
					
					$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								->setLastModifiedBy("Maarten Balliauw")
								->setTitle("Office 2007 XLSX Test Document")
								->setSubject("Office 2007 XLSX Test Document")
								->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								->setKeywords("office 2007 openxml php")
								->setCategory("Test result file");
				  
					// set width    
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);  
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
				  
					// 设置行高度    
					$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
				  
					$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
				  
					// 字体和样式  
					$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
					$objPHPExcel->getActiveSheet()->getStyle('A2:L2')->getFont()->setBold(true);  
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
				  
					$objPHPExcel->getActiveSheet()->getStyle('A2:L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('A2:L2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
				  
					// 设置水平居中    
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
					$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				  
					//  合并  
					$objPHPExcel->getActiveSheet()->mergeCells('A1:L1');  
					// Add some data
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', '外卖侠 '.$nowtime.'日 订单报表')
							->setCellValue('A2',  '订单号')
							->setCellValue('B2',  '客户')
							->setCellValue('C2',  '联系方式')
							->setCellValue('D2',  '数量')
							->setCellValue('E2',  '明细')
							->setCellValue('F2',  '售价')
							->setCellValue('G2',  '实收金额')
							->setCellValue('H2',  '订单状态')
							->setCellValue('I2',  '配送地址')
							->setCellValue('J2',  '发票抬头')
							->setCellValue('K2',  '下单时间')
							->setCellValue('L2',  '发货时间');

					// 内容
					foreach($arr as $i=>$v) {
						$objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+3), $v['order_sn']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+3), $v['buyer']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('C'.($i+3), $v['addr_mobile']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('D'.($i+3), $v['staticSum']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('E'.($i+3), $v['staticDetail']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('F'.($i+3), ' '.$v['amount']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('G'.($i+3), ' '.$v['realpay']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('H'.($i+3), Yii::app()->params["order_status"][$v["order_status"]]);
						$objPHPExcel->getActiveSheet(0)->setCellValue('I'.($i+3), $v['addr']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('J'.($i+3), $v['bak']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('K'.($i+3), $v['order_time']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('L'.($i+3), $v['deliver_time']);

						$objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':L'.($i+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
						$objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':L'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
						$objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(16);  
					}

					// Rename sheet    
					$objPHPExcel->getActiveSheet()->setTitle('外卖侠 '.$nowtime.'日 订单报表');  
				  
					// Set active sheet index to the first sheet, so Excel opens this as the first sheet    
					$objPHPExcel->setActiveSheetIndex(0);  
					
					ob_end_clean();//清除缓冲区,避免乱码
					// 输出  
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="'.'外卖侠 '.$nowtime.'日 订单报表'.'.xls"');  
					header('Cache-Control: max-age=0');  
				  
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
					$objWriter->save('php://output');
					exit;
				}
				break;
			case 'month':
				$arr = Yii::app()->cache->get('monthStatic');
				if(!$arr){
					$sql = 'select DATE_FORMAT(req_date,"%Y-%m-%d") as id,sum(amount) as amount,sum(realpay) as realpay,count(1) as all_count from `order` where pay_status=1 and (order_status=1 or order_status=4) and DATE_FORMAT(req_date,"%Y-%m")="'.$this->nowtime.'" group by 
					DATE_FORMAT(req_date,"%Y-%m-%d") order by req_date desc';
		
					$arr = Yii::app()->db->createCommand($sql)->queryAll();

					$staticRow = array();
					$staticRow['id'] = '合计';
					$staticRow['amount'] = 0;
					$staticRow['realpay'] = 0;
					$staticRow['all_count'] = 0;

					foreach($arr as $v){
						$staticRow['amount'] += $v['amount'];
						$staticRow['realpay'] += $v['realpay'];
						$staticRow['all_count'] += $v['all_count'];
					}
					
					$staticRow['amount'] = sprintf("%.2f",$staticRow['amount']);
					$staticRow['realpay'] = sprintf("%.2f",$staticRow['realpay']);
					$arr[] = $staticRow;
				}

				if(!count($arr)){
					Yii::app()->user->setFlash('error','导出失败，'.$nowtime.' 没有数据');
					$this->redirect('/admin/finance/daily');
				}else{
					header("Content-type:text/html;charset=utf-8");
					error_reporting(E_ALL);
					date_default_timezone_set('Europe/London');
					$objPHPExcel = new PHPExcel();
					
					$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								->setLastModifiedBy("Maarten Balliauw")
								->setTitle("Office 2007 XLSX Test Document")
								->setSubject("Office 2007 XLSX Test Document")
								->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								->setKeywords("office 2007 openxml php")
								->setCategory("Test result file");
				  
					// set width    
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);  
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
				  
					// 设置行高度    
					$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
				  
					$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
				  
					// 字体和样式  
					$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
					$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);  
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
				  
					$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
				  
					// 设置水平居中    
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				  
					//  合并  
					$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');  
					// Add some data
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', '外卖侠 '.$nowtime.'月度 订单报表')
							->setCellValue('A2',  '日期')
							->setCellValue('B2',  '订单总金额')
							->setCellValue('C2',  '实际订单总金额')
							->setCellValue('D2',  '订单量');

					// 内容
					foreach($arr as $i=>$v) { 
						$objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+3), $v['id']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+3), ' '.$v['amount']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('C'.($i+3), ' '.$v['realpay']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('D'.($i+3), ' '.$v['all_count']);
					}

					// Rename sheet    
					$objPHPExcel->getActiveSheet()->setTitle('外卖侠 '.$nowtime.'月度 订单报表');  
				  
					// Set active sheet index to the first sheet, so Excel opens this as the first sheet    
					$objPHPExcel->setActiveSheetIndex(0);  
					
					ob_end_clean();//清除缓冲区,避免乱码
					// 输出  
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="'.'外卖侠 '.$nowtime.'月度 订单报表'.'.xls"');  
					header('Cache-Control: max-age=0');  
				  
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
					$objWriter->save('php://output');
					exit;
				}
				break;
			case 'year':
				$arr = Yii::app()->cache->get('yearStatic');
				if(!$arr){
					$sql = 'select DATE_FORMAT(req_date,"%Y-%m") as id,sum(amount) as amount,sum(realpay) as realpay,count(1) as all_count,count(case pay_status when 1 then 1 end) as pay_count from `order` where pay_status=1 and (order_status=1 or order_status=4) and DATE_FORMAT(req_date,"%Y")="'.$this->nowtime.'" group by DATE_FORMAT(req_date,"%Y-%m") order by order_time desc';
		
					$arr = Yii::app()->db->createCommand($sql)->queryAll();

					$staticRow = array();
					$staticRow['id'] = '合计';
					$staticRow['amount'] = 0;
					$staticRow['realpay'] = 0;
					$staticRow['all_count'] = 0;

					foreach($arr as $v){
						$staticRow['amount'] += $v['amount'];
						$staticRow['realpay'] += $v['realpay'];
						$staticRow['all_count'] += $v['all_count'];
					}
					
					$staticRow['amount'] = sprintf("%.2f",$staticRow['amount']);
					$staticRow['realpay'] = sprintf("%.2f",$staticRow['realpay']);
					$arr[] = $staticRow;
				}

				if(!count($arr)){
					Yii::app()->user->setFlash('error','导出失败，'.$nowtime.' 没有数据');
					$this->redirect('/admin/finance/daily');
				}else{
					header("Content-type:text/html;charset=utf-8");
					error_reporting(E_ALL);
					date_default_timezone_set('Europe/London');
					$objPHPExcel = new PHPExcel();
					
					$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								->setLastModifiedBy("Maarten Balliauw")
								->setTitle("Office 2007 XLSX Test Document")
								->setSubject("Office 2007 XLSX Test Document")
								->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								->setKeywords("office 2007 openxml php")
								->setCategory("Test result file");
				  
					// set width    
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);  
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); 
				  
					// 设置行高度    
					$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
				  
					$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
				  
					// 字体和样式  
					$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
					$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);  
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
				  
					$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
				  
					// 设置水平居中    
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				  
					//  合并  
					$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');  
					// Add some data
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', '外卖侠 '.$nowtime.'年度 订单报表')
							->setCellValue('A2',  '月份')
							->setCellValue('B2',  '订单总金额')
							->setCellValue('C2',  '实际订单总金额')
							->setCellValue('D2',  '订单量');

					// 内容
					foreach($arr as $i=>$v) { 
						$objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+3), $v['id']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+3), ' '.$v['amount']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('C'.($i+3), ' '.$v['realpay']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('D'.($i+3), ' '.$v['all_count']);
					}

					// Rename sheet    
					$objPHPExcel->getActiveSheet()->setTitle('外卖侠 '.$nowtime.'年度 订单报表');  
				  
					// Set active sheet index to the first sheet, so Excel opens this as the first sheet    
					$objPHPExcel->setActiveSheetIndex(0);  
					
					ob_end_clean();//清除缓冲区,避免乱码
					// 输出  
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="'.'外卖侠 '.$nowtime.'年度 订单报表'.'.xls"');  
					header('Cache-Control: max-age=0');  
				  
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
					$objWriter->save('php://output');
					exit;
				}
				break;
			case 'custom':
				$arr = Yii::app()->cache->get('customStatic');
				if(!$arr){
					$crit = new CDbCriteria;

					if(!empty($starttime)){
						$sdate = $starttime;
						$edate = $endtime;
						if(!$edate) {
							$edate = date('Y-m-d');
						} else {
							$edate = date('Y-m-d', strtotime($edate));
						}
						$etime = $edate . ' 23:59:59';
						$etime_int = strtotime($etime);

						if(!$sdate) {
							$sdate = date('Y-m-d', strtotime('-365 days', $etime_int));

						} else {
							$sdate = date('Y-m-d', strtotime($sdate));

						}
						$stime = $sdate . ' 00:00:00';
						$stime_int = strtotime($stime);

						$days = floor(($etime_int - $stime_int) / (3600 * 24));
						if($days > 365) {
							$sdate = date('Y-m-d', strtotime('-365 days', $etime_int));
							$stime = $sdate . ' 00:00:00';
						}
						
						$crit->addBetweenCondition('req_date', $sdate, $edate);
					}
					
					$crit->addCondition('pay_status=1 and (order_status=1 or order_status=4)');
					$crit->order = 'req_date desc,pay_status desc';

					$orders = Order::model()->findAll($crit);

					$arr = array();

					foreach($orders as $k=>&$v){
						$tmpArr = array();
						$tmpArr = $v->attributes;
						$tmpArr['order_time'] = isset($v->order_time)?date("Y-m-d H:i:s",$v->order_time):"";
						$tmpArr['buyer'] = isset($v->buyer)?$v->buyer->username:"用户已注销";
						$tmpArr['addr_mobile'] = isset($v->addr)?$v->addr->mobile:"地址已注销";
						$tmpArr['addr'] = isset($v->addr)?$v->addr->address:"地址已注销";

						$tmpSum = 0;
						$tmpDes = '';

						foreach($v->details as $dv){
							$tmpSum += $dv->quantity;
							$tmpDes .= $dv->menu->name." : ".$dv->quantity."份; \r\n";
						}

						$tmpArr['staticSum'] = $tmpSum;
						$tmpArr['staticDetail'] = $tmpDes;

						$arr[] = $tmpArr;
					}
				}

				if(!count($arr)){
					Yii::app()->user->setFlash('error','导出失败，'.$stime.'-'.$etime.' 没有数据');
					$this->redirect('/admin/finance/daily');
				}else{
					header("Content-type:text/html;charset=utf-8");
					error_reporting(E_ALL);
					date_default_timezone_set('Europe/London');
					$objPHPExcel = new PHPExcel();
					
					$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								->setLastModifiedBy("Maarten Balliauw")
								->setTitle("Office 2007 XLSX Test Document")
								->setSubject("Office 2007 XLSX Test Document")
								->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								->setKeywords("office 2007 openxml php")
								->setCategory("Test result file");
				  
					// set width    
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);  
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20); 
					$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
				  
					// 设置行高度    
					$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
				  
					$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
				  
					// 字体和样式  
					$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
					$objPHPExcel->getActiveSheet()->getStyle('A2:L2')->getFont()->setBold(true);  
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
				  
					$objPHPExcel->getActiveSheet()->getStyle('A2:L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('A2:L2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
				  
					// 设置水平居中    
					$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
					$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
					$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				  
					//  合并  
					$objPHPExcel->getActiveSheet()->mergeCells('A1:L1');  
					// Add some data
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', '外卖侠 '.$stime.'-'.$etime.'日 订单报表')
							->setCellValue('A2',  '订单号')
							->setCellValue('B2',  '客户')
							->setCellValue('C2',  '联系方式')
							->setCellValue('D2',  '数量')
							->setCellValue('E2',  '明细')
							->setCellValue('F2',  '售价')
							->setCellValue('G2',  '实收金额')
							->setCellValue('H2',  '订单状态')
							->setCellValue('I2',  '配送地址')
							->setCellValue('J2',  '发票抬头')
							->setCellValue('K2',  '下单时间')
							->setCellValue('L2',  '发货世间');

					// 内容
					foreach($arr as $i=>$v) { 
						$objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+3), $v['order_sn']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+3), $v['buyer']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('C'.($i+3), $v['addr_mobile']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('D'.($i+3), $v['staticSum']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('E'.($i+3), $v['staticDetail']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('F'.($i+3), ' '.$v['amount']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('G'.($i+3), ' '.$v['realpay']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('H'.($i+3), Yii::app()->params["order_status"][$v["order_status"]]);
						$objPHPExcel->getActiveSheet(0)->setCellValue('I'.($i+3), $v['addr']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('J'.($i+3), $v['bak']);
						$objPHPExcel->getActiveSheet(0)->setCellValue('K'.($i+3), $v['order_time']);  
						$objPHPExcel->getActiveSheet(0)->setCellValue('L'.($i+3), $v['deliver_time']);    

						$objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':L'.($i+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
						$objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':L'.($i+3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
						$objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(16);  
					}

					// Rename sheet    
					$objPHPExcel->getActiveSheet()->setTitle('外卖侠 '.$stime.'-'.$etime.'日 订单报表');  
				  
					// Set active sheet index to the first sheet, so Excel opens this as the first sheet    
					$objPHPExcel->setActiveSheetIndex(0);  
					
					ob_end_clean();//清除缓冲区,避免乱码
					// 输出  
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="'.'外卖侠 '.$stime.'-'.$etime.'日 订单报表'.'.xls"');  
					header('Cache-Control: max-age=0');  
				  
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
					$objWriter->save('php://output');
					exit;
				}
				break;
		}
	}

	public function actionMenudetail(){
		if(isset($_GET['nowtime']))
			$nowtime = $_GET['nowtime'];
		else
			$nowtime = date('Y-m-d');
		
		$dateArr = array();
		
		//当前时间往前一周内的时间
		for($i=6;$i>=0;$i--){
			$dateArr[] = date('Y-m-d',strtotime("$nowtime -$i days"));	
		}

		$menus = Menu::model()->findAll('1 order by status desc,updtime desc');
		
		$arr = array();
		foreach($dateArr as $v){
			if(empty($arr[$v]))
				$arr[$v] = array();

			foreach($menus as $vv){
				$sql = "select sum(od.quantity) as ct,gid,m.name from order_detail od left join `order` o on od.order_id=o.id left join `menu` m on od.gid=m.id where od.gid=".$vv->id." and o.pay_status=1 and (o.order_status=1 or o.order_status=4) and o.req_date='".$v."'";

				$tmpArr = Yii::app()->db->createCommand($sql)->queryRow();

				if(!empty($tmpArr['gid'])){
					$arr[$v][$tmpArr['gid']]['name'] = $tmpArr['name'];
					$arr[$v][$tmpArr['gid']]['count'] = $tmpArr['ct'];
				}
			}
		}

		$this->render('menudetail',array(
			'nowtime' => $nowtime,
			'arr' => $arr,
			'menus' => $menus,
			'dateArr' => $dateArr,
		));
	}
	
	//菜单明细统计导出
	public function actionEe(){
		$ct = Yii::app()->request->getPost('toee');
		$staticDate = Yii::app()->request->getPost('staticDate');
		ob_end_clean();//清除缓冲区,避免乱码
		header("Content-type:text/html;charset=utf-8");
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:filename=".$staticDate."_menu_type_static.xls");

		//require_once $_SERVER['DOCUMENT_ROOT']."/css/table_form.css";
		echo "<style>.items{width:90%;}</style><div class='grid-view'>".$ct."</div>";
	}
}
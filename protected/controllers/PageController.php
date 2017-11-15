<?php

class PageController extends CController
{
	public function actionPagelist()
    {
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$criteria=new CDbCriteria();
			
			//$criteria->condition = 'p_id =:pid';
			
			//$criteria->params = array(':pid' => 0);
			if(isset($_GET['q'])){
				$q = $_GET['q'];
				$criteria->compare('page_title', $q, true);
			}
			$criteria->order = 'id ASC';
			$count=Pages::model()->count($criteria);
			$pages=new CPagination($count);
			// results per page
			$pages->pageSize=5;
			$pages->applyLimit($criteria);
			$allpages=Pages::model()->findAll($criteria);
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('pagelist', array(
			'allpages' => $allpages,
			 'pages' => $pages
			));	
			$this->renderPartial('//layouts/admin/footer');			
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionManagefields()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$criteria=new CDbCriteria();			
			if(isset($_GET['q'])){
				$q = $_GET['q'];
				$criteria->compare('field_label', $q, true);
			}
			$criteria->order = 'id ASC';
			$count=Fields::model()->count($criteria);
			$pages=new CPagination($count);
			// results per page
			$pages->pageSize=10;
			$pages->applyLimit($criteria);
			$fields=Fields::model()->findAll($criteria);
			
			//$fields = Fields::model()->findAll();
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('managefields', array('fields' => $fields,'pages' => $pages));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionAddpage()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(isset($_POST['submit']))
			{
				if($_POST['id'] != ''){
					$model=new Pages;
					$updatearr['p_id']=$_POST['oldpage'];
					$updatearr['id']=$_POST['id'];		
					$updatearr['page_name']=$_POST['page_name'];
					$updatearr['page_location']=$_POST['page_loc'];
					$updatearr['page_title']=$_POST['title_name'];
					$updatearr['page_meta']=$_POST['meta_tag'];
					$updatearr['page_keyword']=$_POST['key_word'];
					$updatearr['page_body']=$_POST['elm1'];
					
					//echo $updatearr['page_location']; die;
					
					$update = Yii::app()->db->createCommand()
							->update('cads_pages', 
								$updatearr,
								'id=:id',array(':id'=>$_POST['id'])
							);
					Yii::app()->user->setFlash('success', "Page details Updated");
					$this->redirect(array('Page/pagelist'));
				}
				
			$model=new Pages;
			$model->p_id=$_POST['oldpage'];
			$model->page_name=$_POST['page_name'];
			$model->page_location=$_POST['page_loc'];
			$model->page_title=$_POST['title_name'];
			$model->page_meta=$_POST['meta_tag'];
			$model->page_keyword=$_POST['key_word'];
			$model->page_body=$_POST['elm1'];
			if($model->save())
			{
				Yii::app()->user->setFlash('success', "Page details Saved");
				$this->redirect(array('Page/pagelist'));
			}
			else
			{
				Yii::app()->user->setFlash('error', "Page details not saved due to Some error!");
				$this->redirect(array('Page/addpage'));	
			}
			}
			
			$allpages = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_pages')
						->queryAll();
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addpage',array('allpages'=>$allpages));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
	public function actionUpdatepage($id = Null)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if($id!=''){
				$pages = Yii::app()->db->createCommand()
							->select('*')
							->from('cads_pages')
							->where(array('like', 'id', "$id"))
							->queryRow();
							
				$allpages = Yii::app()->db->createCommand()
							->select('*')
							->from('cads_pages')
							->queryAll();
							
				$this->renderPartial('//layouts/admin/header');
				$this->renderPartial('//layouts/admin/sidebar');
				$this->render('addpage',array('allpages'=>$allpages,'pages'=>$pages));
				$this->renderPartial('//layouts/admin/footer');
			} else {
				if(isset($_POST['submit'])){
					
				}
				$this->redirect(array('Page/pagelist'));
			}			
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
	public function actionDeletepage($id)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if($id!='')
			{
				$model=new Pages;
				$page_delete=Yii::app()->db->createCommand()
				->delete('cads_pages', 'id = '.$id);
				Yii::app()->user->setFlash('success', "Page details Deleted");				
				$this->redirect(array('Page/pagelist'));
			}
			else
			{
				$this->redirect(array('Page/pagelist'));
			}
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
	public function actionFeedback()
    {
		$PageList = Yii::app()->commonFnc->PageList();
		$feedbackpage = Yii::app()->db->createCommand()
				->select('*')
				->from('cads_pages')
				->where('page_name=:name',array(':name'=>'feedback'))
				->queryRow();
		$this->renderPartial('//layouts/header');
		$this->render('feedback',array('PageList'=>$PageList,'feedbackpage'=>$feedbackpage));
		$this->renderPartial('//layouts/footer');
	}
	public function actionSendFeedback(){
		$AdminUserdetails = Yii::app()->db->createCommand()
			->select('u.login_email,um.firstname,um.lastname')
			->from('cads_Users u')
			->leftJoin('cads_UsersMeta um', 'u.id=um.user_id')
			->where('u.id=:aid',array(':aid'=>1))
			->queryRow();
			
		$to  = 'support@tripken.com';
		//$to  = 'pankaj.netqom@gmail.com';
		$subject = 'Feedback Form On Tripken ClassifiedAds';
		$message = '<html>
					<head>
					  <title>Feedback Form On Tripken ClassifiedAds</title>
					</head>
					<body>
					  <p>Dear Admin,<br/><br/> A user has Submit a Feedback Form On Tripken ClassifiedAds. Please Check the details given below..</p>
					  <p> Your Subject                 :  '.$_REQUEST['subject'].'</p>
					  <p> Your Email                   :  '.$_REQUEST['eid'].'</p>	
					  <p> Your Message                 :  '.$_REQUEST['msg'].'</p>		
					</body>
					</html>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'To: '.$AdminUserdetails['firstname'].' <'.$_REQUEST['eid'].'>' . "\r\n";
		$headers .= 'From: '.$_REQUEST['eid'].' <'.$_REQUEST['eid'].'>' . "\r\n";
		if(mail($to, $subject, $message, $headers)){
			echo "We will review your message, and respond if applicable, as soon as possible.<br/>Thank you for using ads.tripken.com. ";
		} else {
			echo "There seems some error.";
		}
	}
	
}

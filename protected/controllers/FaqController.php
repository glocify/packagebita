<?php

class FaqController extends CController
{
	public function actionFaqlist()
    {
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$criteria=new CDbCriteria();			
			if(isset($_GET['q'])){
				$q = $_GET['q'];
				$criteria->compare('faq_question', $q, true);
			}
			$criteria->order = 'id ASC';
			$count=Faqs::model()->count($criteria);
			$pages=new CPagination($count);
			// results per page
			$pages->pageSize=10;
			$pages->applyLimit($criteria);
			$allfaqs=Faqs::model()->findAll($criteria);
			
			/* $allfaqs = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_faq')
						->queryAll(); */ 
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('faqlist',array('allfaqs'=>$allfaqs,'pages' => $pages));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('User/login'));
		}
		
    }
    
    public function actionAddfaq()
    {
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$ad_faqtype = array('Users' , 'Owners', 'Business Listing - Menus', 'Classified/Ads');
			if(isset($_POST['submit']))
			{
				
				if($_POST['id'] != '')
				{
					$model=new Faqs;
					$updatearr['id']=$_POST['id'];		
					$updatearr['faq_question']=$_POST['question'];
					$updatearr['faq_type']=$_POST['type'];
					$updatearr['faq_keyword']=$_POST['key_word'];
					$updatearr['faq_meta']=$_POST['meta_tag'];
					$updatearr['faq_answer']=$_POST['elm1'];
					$updatearr['faq_status']=$_POST['oldpage'];
					$update = Yii::app()->db->createCommand()
								->update('cads_faq', 
									$updatearr,
									'id=:id',
									array(':id'=>$_POST['id'])
								);
					$this->redirect(array('Faq/faqlist'));
				}
			$model=new Faqs;
			$model->faq_question=$_POST['question'];
			$model->faq_type=$_POST['type'];
			$model->faq_keyword=$_POST['key_word'];
			$model->faq_meta=$_POST['meta_tag'];
			$model->faq_answer=$_POST['elm1'];
			$model->faq_status=$_POST['oldpage'];
			if($model->save())
			{
				$this->redirect(array('Faq/faqlist'));
			}
			else
			{
				$this->redirect(array('Faq/addfaq'));	
			}
			
			}
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addfaq', array('ad_faqtype' => $ad_faqtype));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
	public function actionUpdatefaq($id)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$ad_faqtype = array('Users' , 'Owners', 'Business Listing - Menus', 'Classified/Ads');
			if($id!='')
			{
				$faqs = Yii::app()->db->createCommand()
							->select('*')
							->from('cads_faq')
							->where(array('like', 'id', "$id"))
							->queryRow();	
				$this->renderPartial('//layouts/admin/header');
				$this->renderPartial('//layouts/admin/sidebar');
				$this->render('addfaq',array('faqs'=>$faqs,'ad_faqtype' => $ad_faqtype));
				$this->renderPartial('//layouts/admin/footer');
			}
			else
			{
				$this->redirect(array('Faq/faqlist'));
			}
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
	public function actionDeletefaq($id)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if($id!='')
			{
				$model=new Faqs;
				$faq_delete=Yii::app()->db->createCommand()
					->delete('cads_faq', 'id = '.$id);
					
				$this->redirect(array('Faq/faqlist'));
			}
			else
			{
				$this->redirect(array('Faq/faqlist'));
			}
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
}

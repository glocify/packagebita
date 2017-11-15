<?php
class SiteController extends CController
{
	/**
	 * This is the default action that displays the phonebook Flex client.
	 */
	public function actions()
	{
		return array(
						'oauth' => array(
											'class'=>'ext.hoauth.HOAuthAction',
											'model' => 'Users',
											 'attributes' => array(
																		'login_email' => 'email',
																		'firstname' => 'firstName',
																		'lastlname' => 'lastName',
																	),
									),
					);
	}	
	
	public function actionIndex()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "user_status =:status AND role =:role";
		$criteria->params = array(':status' => 1, ':role' => 'user');
		
		if(isset($_GET['Rsorting']) && $_GET['Rsorting'] != 0){
			if($_GET['Rsorting'] == 1){
				$criteria->order = 'UserMeta.Hour_rate ASC';	
			}else{
				$criteria->order = 'UserMeta.Hour_rate DESC';	
			}		
		}else{
			$criteria->order = 't.id DESC';		
		}	
		
		$count = Users::model()->with('UserMeta')->count($criteria);
		$pages=new CPagination($count);
		$pages->pageSize=12;
		if(isset($_GET['Rsorting']) && $_GET['Rsorting'] != 0){
			$pages->params = array('Rsorting'=> $_GET['Rsorting']);
		}	
		$pages->applyLimit($criteria);		
		
		$Userdata = Users::model()->with('UserMeta')->findAll($criteria);

		$criteria2 = new CDbCriteria();
		$criteria2->condition = "user_status =:status AND role =:role";
		$criteria2->params = array(':status' => 1, ':role' => 'user');
		$UserForProfiledata = Users::model()->with('UserMeta')->findAll($criteria2);
		
	
		$this->renderPartial('//layouts/header');
		$this->render('index',array('Userdata'=>$Userdata, 'pages'=>$pages, 'UserForProfiledata'=>$UserForProfiledata));
		$this->renderPartial('//layouts/footer');
	}
	
	public function actionListing()
	{		
		$criteria=new CDbCriteria();	
		$criteria->condition = "user_status =:status AND role =:role";
		$criteria->params = array(':status' => 1, ':role' => 'user');
		
		if(isset($_GET['Rsorting']) && $_GET['Rsorting'] != 0){
			if($_GET['Rsorting'] == 1){
				$criteria->order = 'UserMeta.Hour_rate ASC';	
			}else{
				$criteria->order = 'UserMeta.Hour_rate DESC';	
			}		
		}else{
			$criteria->order = 't.id DESC';		
		}	
		$count=Users::model()->with(array('UserMeta' => array('condition' => 'UserMeta.offer !=""')))->count($criteria);
		$pages=new CPagination($count);
		$pages->pageSize=12;
		$pages->applyLimit($criteria);
		$Userdata=Users::model()->with(array('UserMeta' => array('condition' => 'UserMeta.offer !=""')))->findAll($criteria);	
		
		$criteria2 = new CDbCriteria();
		$criteria2->condition = "user_status =:status AND role =:role AND offer !=:offer";
		$criteria2->params = array(':status' => 1, ':role' => 'user',':offer' => '');
		$UserForProfiledata = Users::model()->with('UserMeta')->findAll($criteria2);
		
		$this->renderPartial('//layouts/header');
		$this->render('listing',array('Userdata'=>$Userdata, 'pages'=>$pages, 'UserForProfiledata' => $UserForProfiledata)); 
		$this->renderPartial('//layouts/footer');
	}
	public function actionJobadd()
	{		
		$base_url = Yii::app()->getBaseUrl(true);
		if(isset($_FILES['pic']['name']) && ($_FILES['pic']['name']!=''))
		{
			    $base_url = Yii::app()->getBaseUrl(true);
				$extensions = array("jpeg","jpg","png","pdf","csv","docx");
				$dir = $base_url."/images/uploads/";
				$tmp_name = $_FILES['pic']['tmp_name'];
				$file_name = $_FILES['pic']['name'];
				$file_size =$_FILES['pic']['size'];
				$file_tmp =$_FILES['pic']['tmp_name'];
				$file_type=$_FILES['pic']['type'];
				$fileext=explode('.',$file_name);
				$count = count($fileext);
				$count1 = $count-1;
				$fileext1=$fileext[$count1];  
				$file_ext=strtolower($fileext1);
				if(in_array($file_ext,$extensions ) === false){
					Yii::app()->user->setFlash('error','selected file extension is not allowed.');
					$this->redirect(array('Site/jobadd'));
				}else{
					$file_name = time().$file_name;
					if(is_dir($dir)==false){
							move_uploaded_file($file_tmp,"images/uploads/".$file_name);
							}
				}
		}
		
		if(isset($_POST['submit'])) {
				
				if( isset($_POST['moreDevelopers']) && !empty($_POST['moreDevelopers'])){
					$getAlldeveloer = $_POST['moreDevelopers'];  
				}else{
					$getAlldeveloer =  array();
				}
				             				
				$getAlldeveloer[] = $_POST['sent_to_user'];
				
				$sendprofilecount = 0;
				$successStatus = '';
				$errorStatus = '';
				$messageNotification = '';
				$redirect_url = $_POST['redirect_url'];
				foreach($getAlldeveloer as $singleDeveloper ){
				
					$user_email = $_POST['email_address'];
					$ipAddress = $_SERVER['REMOTE_ADDR'];

					$pagedta = Yii::app()->db->createCommand()
						->select('email')
						->from('cads_Jobs')
						->where('ip_address =:ip_address',array(':ip_address' =>$ipAddress))
						->queryAll();
						
					if(count($pagedta) < 5){
						$model=new Jobs;					
						$model->send_to_user=$singleDeveloper;
						$model->email=$_POST['email_address'];
						$model->job_name=$_POST['job_name'];
						$model->job_description=$_POST['job_description'];
						$model->project_type=$_POST['project_type'];
						$model->skill_needed=$_POST['skills'];
						$model->start_date=$_POST['start_date'];
						$model->pay_type=$_POST['like_to_pay'];
						$model->experience_level=$_POST['experience_level'];
						$model->project_duration=$_POST['project_duration'];
						$model->time_commitment=$_POST['time_commitment'];
						//$model->ip_address = $_SERVER['REMOTE_ADDR'];
						$model->file_uploaded = $file_name;
						if($model->save()):
							$userId = $singleDeveloper;
						
							$Ipaddress = $_SERVER['REMOTE_ADDR'];		
						
							$Adcountdata = Yii::app()->db->createCommand()
							->select('*')
							->from('cads_click_count')		
							->where('user_id=:user_id',array(':user_id'=>$userId))
							->andwhere('ip_address=:ipad',array(':ipad'=>$Ipaddress))
							->queryRow();
					
							if(isset($Adcountdata) && empty($Adcountdata) && $Adcountdata == '' ){
								$Newadcount				=	new ProfileCounts;
								$Newadcount->ip_address	=	$Ipaddress;
								$Newadcount->profile_count	=	10;
								$Newadcount->user_id		=	$userId;
								$Newadcount->save();
							} else {
								$Newadcount				=	ProfileCounts::model()->findbyPk($Adcountdata['id']);
								$Newadcount->profile_count	=	$Adcountdata['profile_count']+10;
								$Newadcount->update();
							} 
							
							
						
							
							$sent_to_user_date = Yii::app()->db->createCommand()
							->select('*')
							->from('cads_Users')
							->where('id =:id',array(':id' => $singleDeveloper))
							->queryRow();
				
							$to  = $sent_to_user_date['login_email']; // note the comma
							// subject
							$subject = 'Job Notification from Money Media Solutions ';
							$message = '
							<html>
							<head>
							  <title>Money Media Solutions </title>
							</head>
							<body>
								<table width="100%" cellspacing="0" cellpadding="0">
								  <tr>
										<td align="left" valign="top" height="50">
										<table width="100%" cellspacing="2" cellpadding="2">
											<tr>
												<td width="45%" align="left"><img src="'.$base_url.'/images/logo-n.png"></td>
											</tr>
										</table>
										</td>
								  </tr>
								  <tr>
										<td align="left" valign="top" >
											<table width="100%" cellspacing="8" cellpadding="8">
												<tr>
													<td align="left" valign="top">
														<p>Dear '.$sent_to_user_date['name'].',</p>
														<p>Here below given new job details.</p>
														<p> Client Email: '.$_POST['email_address'].'</p>	
														<p> Job Title : '.$_POST['job_name'].'</p>	
														<p> Job Description : '.$_POST['job_description'].'</p>	
														<p> Project Type  : '.$_POST['project_type'].'</p>
														<p> Skills required   : '.$_POST['skills'].'</p>
														<p> Start Date  : '.$_POST['start_date'].'</p>
														<p> Payment  : '.$_POST['like_to_pay'].'</p>
														<p> Desired Experienced Level  : '.$_POST['experience_level'].'</p>
														<p> Job Duration  : '.$_POST['project_duration'].'</p>
														<p> Time Commitments required  : '.$_POST['time_commitment'].'</p>
														<p> Thanks & Regards,<br/>Money Media Solution.</p>
													</td>
												</tr>
											</table>
										</td>
								  </tr>						  
								  </table>
							</body>
							</html>
							';
								
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							// Additional headers
							$headers .= 'To: '.$sent_to_user_date['name']. "\r\n";
							$headers .= 'From: Money Media Solutions <upworksanjeev@gmail.com>' . "\r\n";
							
							// Mail it
							if(mail($to, $subject, $message, $headers)){
								$sendprofilecount += 1;
								$successStatus = 'success';
								$messageNotification = 'Your Job has been posted successfully!';
							}else{
								$successStatus = 'error';
								$messageNotification = 'Something wrong. Please contact with Administration!';
							}
						else:
							$successStatus = 'error';
							$messageNotification = 'Something wrong. Please contact with Administration!';
						endif;
						
					}else{	
						$successStatus = 'error';
						$messageNotification = 'Your already sent job to 4 profiles!';
					} 
				}
				
				if($sendprofilecount > 0 && $messageNotification == 'Your already sent job to 4 profiles!' ){
					
					Yii::app()->user->setFlash($successStatus,'Your job has been sent to '.$sendprofilecount.' profiles. You can\'t send job to more then 4 profiles!');
					$this->redirect($redirect_url);
				}elseif($sendprofilecount > 0 && $messageNotification == 'Your Job has been posted successfully!'){
					Yii::app()->user->setFlash($successStatus, 'You job has been sent to '.$sendprofilecount.' profiles successfully!');
					$this->redirect($redirect_url);
				}else{
					Yii::app()->user->setFlash($successStatus, $messageNotification);
					$this->redirect($redirect_url);
				}

		}else{
			$this->redirect(array('Site/index'));
		}
	}
	
	public function actionSignup()
	{
		$base_url = Yii::app()->getBaseUrl(true);
		if(isset($_POST['submit'])) {		
			
			if($_POST['password'] == $_POST['cpassword']){				
				$user_email = $_POST['email'];
				$pagedta = Yii::app()->db->createCommand()
					->select('login_email')
					->from('cads_Users')
					->where(array('like','login_email',"$user_email"))
					->queryAll();
				
				if(count($pagedta) > 0){
					Yii::app()->user->setFlash('error','User with same email already registered.');
					$this->redirect(array('Site/Signup'));
				}else{
					if(isset($_FILES['pic']['name']) && ($_FILES['pic']['name']!=''))
					{
						$base_url = Yii::app()->getBaseUrl(true);
						$extensions = array("jpeg","jpg","png");
						$dir = $base_url."/images/userimages/";
						$tmp_name = $_FILES['pic']['tmp_name'];
						$file_name = $_FILES['pic']['name'];
						$file_size =$_FILES['pic']['size'];
						$file_tmp =$_FILES['pic']['tmp_name'];
						$file_type=$_FILES['pic']['type'];
						$fileext=explode('.',$file_name);
						$count = count($fileext);
						$count1 = $count-1;
						$fileext1=$fileext[$count1];  
						$file_ext=strtolower($fileext1);
						if(in_array($file_ext,$extensions ) === false){
							Yii::app()->user->setFlash('error','selected file extension is not allowed for profile image.');
							$this->redirect(array('Site/Signup'));
						}else{
							$file_name = time().$file_name;
							$thumbnail_width = 90;
							$thumbnail_height = 70;
							$thumb_beforeword = "thumb";
							$thumb_path = "images/usersthumb/";
							$size = getimagesize($tmp_name);
							$original_width = $size[0];
							$original_height = $size[1];
							$thumb_create = imagecreatetruecolor($thumbnail_width,$thumbnail_height);
									switch($file_ext){
										case 'jpg':
											$source = imagecreatefromjpeg($tmp_name);
											break;
										case 'jpeg':
											$source = imagecreatefromjpeg($tmp_name);
											break;
										case 'png':
											$source = imagecreatefrompng($tmp_name);
											break;
										default:
											$source = imagecreatefromjpeg($tmp_name);
									}
									 imagecopyresized($thumb_create,$source,0,0,0,0,$thumbnail_width,$thumbnail_height,$original_width,$original_height);
									 $thumbnail = $thumb_path."thumb_".$file_name;
									 switch($file_ext){
										case 'jpg' || 'jpeg':
											imagejpeg($thumb_create,$thumbnail,100);
											break;
										case 'png':
											imagepng($thumb_create,$thumbnail,100);
											break;
										default:
											imagejpeg($thumb_create,$thumbnail,100);
									}
							if(is_dir($dir)==false){
								move_uploaded_file($file_tmp,"images/userimages/".$file_name);
							}   
						}
					}else{
						Yii::app()->user->setFlash('error','Select profile image.'); 
							$this->redirect(array('Site/Signup'));
					}
					
					$model=new Users;					
					$model->name=$_POST['firstname'].$_POST['lastname'];
					$model->login_email=$_POST['email'];
					$model->role='user';
					$model->Premium_starting_date=date('Y-m-d h:i:s');
					$model->password=md5($_POST['password']);
					$model->save();
					$user_id = $model->id;
					$Usermeta=new UsersMeta;
					$Usermeta->user_id=$user_id;
					$Usermeta->firstname=$_POST['firstname'];
					$Usermeta->speciality=$_POST['speciality'];
					$Usermeta->position=$_POST['position'];
					$Usermeta->url=$_POST['url'];
					$Usermeta->Hour_rate=$_POST['hour_rate'];
					$Usermeta->user_image=$file_name;
					$Usermeta->lastname=$_POST['lastname'];
					$Usermeta->zip=$_POST['zip'];
					$Usermeta->user_registered=date('Y-m-d h:i:s');
						
					if(isset($_POST['recommendation'])) {
						$Usermeta->recommendation=$_POST['recommendation'];
					}
					if($Usermeta->save()){
											
						$to  = $_POST['email']; // note the comma
						// subject
						$subject = 'Money Media Solutions Account Verfication';

						// message
						$verifyId = $user_id+100;
						$verificationlink = $base_url.'/Site/verify/'.$verifyId;
						$message = '
						<html>
						<head>
						  <title>Money Media Solutions Account Verfication</title>
						</head>
						<body>
							<table width="100%" cellspacing="0" cellpadding="0">
							  <tr>
									<td align="left" valign="top" height="50" style="background-color:#1baea6;">
									<table width="100%" cellspacing="2" cellpadding="2">
										<tr>
											<td width="45%" align="left"><img src="'.$base_url.'/images/logo-n.png"></td>
											<td width="45%" align="right">
												<img src="'.$base_url.'/images/march-adsimg.png"></h1>
											</td>
										</tr>
									</table>
									</td>
							  </tr>
							  <tr>
									<td align="left" valign="top" style="background-color:#2cd9ce;color:#fff;">
										<table width="100%" cellspacing="8" cellpadding="8">
											<tr>
												<td align="left" valign="top">
													<p>Dear '.$_POST['firstname'].',</p>
													<p>Find your account details and verification Link below. Click on verification link to activate you account.</p>
													<p> First Name : '.$_POST['firstname'].'</p>	
													<p> Last Name : '.$_POST['lastname'].'</p>	
													<p> Email : '.$_POST['email'].'</p>	
													<p> Password  : '.$_POST['password'].'</p>
													<p> Verification Link   : <a href="'.$verificationlink.'">Click Here</a></p>
													<p> Thanks & Regards,<br/>Lorem Team.</p>
												</td>
											</tr>
										</table>
									</td>
							  </tr>						  
							  <tr>
									<td align="left" valign="top" height="90" style="background-color:#1baea6;">
										<table width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td align="left" valign="top" style="float: left;text-align:center;width: 100%;padding: 23px 0 0 0;margin: 0px auto;">
													<ul class=" foot-link">
													<li><a href="'.$base_url.'/about-us.html">About</a> </li>
													<li><a href="'.$base_url.'/contact-us.html">Contact Us</a> </li>
													<li><a href="'.$base_url.'/privacy-policy.html">privacy policy </a> </li>
													<li><a href="'.$base_url.'/terms-conditions.html">Terms &amp; Conditions</a> </li>													
													<li><a href="'.$base_url.'/help.html">Help</a> </li>
													</ul>
												</td>
											</tr>
											<tr>
												<td style="float: left;text-align:center;width: 100%;padding: 10px 0 0 0;margin: 0px auto;">
												<p class="copy">Copyright © 2015 Lorem, LLC. All rights reserved.</p>
												</td>
											</tr>
										</table>
									</td>
							  </tr>
							  </table>
						</body>
						</html>
						';
						
						
							
						// To send HTML mail, the Content-type header must be set
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						// Additional headers
						$headers .= 'To: '.$_POST['firstname'].' <'.$_POST['email'].'>' . "\r\n";
						$headers .= 'From: Money Media Solution Team <upworksanjeev@gmail.com>' . "\r\n";
						
						// Mail it
						if(mail($to, $subject, $message, $headers)){
							$redirect_url = $base_url."/thank_you.html";
							$this->redirect($redirect_url);
						}else{
							Yii::app()->user->setFlash('error','Something wrong. Please contact with Administration.');
							$this->redirect(array('Site/signin'));
						}	
								
					}
					else
					{
						Yii::app()->user->setFlash('error','Some error Occured.');
						$this->redirect(array('Site/signup'));
					}
					
				}
			} else	{
				Yii::app()->user->setFlash('error','Please Confirm your password carefully.');
				$this->redirect(array('Site/Signup'));
			}
		}
		$this->renderPartial('//layouts/header');
		$this->render('Signup');
		$this->renderPartial('//layouts/footer');
	}
	
	public function actionThankyou()
	{	
		if(isset(Yii::app()->session['userid'])){
			if(isset($_REQUEST) && !empty($_REQUEST)){
				if(isset($_REQUEST['amt']) && $_REQUEST['amt'] !== ''){
					$amount = $_REQUEST['amt'];
				}else{
					$amount = $_REQUEST['payment_gross'];
				}
				
				$itemname = $_REQUEST['item_name'];
				
				$item_id = $_REQUEST['item_number'];
				
				if(isset($_REQUEST['st']) && $_REQUEST['st'] !== ''){
					$paypal_payment_status = $_REQUEST['st'];
				}else{
					$paypal_payment_status = $_REQUEST['payment_status'];
				}
				
				
				if(isset($_REQUEST['st']) && $_REQUEST['st'] !== ''){
					if(isset($_REQUEST['st']) && $_REQUEST['st'] == 'Completed'){
						$order_status = true;
					}else{
						$order_status = false;
					}
				}else{
					if(isset($_REQUEST['payment_status']) && $_REQUEST['payment_status'] == 'Completed'){
						$order_status = true;
					}else{
						$order_status = false;
					}
				}
				
				if(isset($_REQUEST['tx']) && $_REQUEST['tx'] !== ''){
					$paypal_payment_refrence = $_REQUEST['tx'];
				}else{
					$paypal_payment_refrence = $_REQUEST['txn_id'];
				}
				
				
				$userId = Yii::app()->session['userid'];
				
				$model=new Orders;
				$model->userid= $userId;
				$model->package_id= $item_id;
				$model->payment_refrence_id= $paypal_payment_refrence;
				$model->payment_status= $paypal_payment_status;
				$model->order_status= $order_status;
				$model->order_price= $amount;
				if($model->save())
				{
						Yii::app()->user->setFlash('register','Your Order has been placed Successfully.');
				}
				else
				{
						Yii::app()->user->setFlash('error','Something went wrong. Please contact with administration');
				}
						
				
			}
			$this->renderPartial('//layouts/header');
			$this->render('thankyou');
			$this->renderPartial('//layouts/footer');
		}else{
			$this->redirect(array('Site/Signin'));
		}
		
	}
	
	public function actionSignupthankyou(){
		$this->renderPartial('//layouts/header');
		$this->render('signupthankyou');
		$this->renderPartial('//layouts/footer');
		
	}

  	public function actionError()
    {
        $error = Yii::app()->errorHandler->error;

        if( $error )
        {
        	$this->renderPartial('//layouts/header');
            $this -> render( 'error', array( 'error' => $error ) );
            $this->renderPartial('//layouts/footer');
        }
    }

	public function actionGetLatLong()
	{
		$street = str_replace(array(' ',','),'+',$_POST['street']);
		$city = str_replace(array(' ,',' '),'+',$_POST['cityid']);
		$city = str_replace( array( '%', '#'), '', $city);
		$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$city);
		$output= json_decode($geocode);
		if(!empty($output->results)):
			$lat = $output->results[0]->geometry->location->lat;
			$long = $output->results[0]->geometry->location->lng;
			echo $lat.','.$long;
		else:
			echo "0,0";
		endif;
	}
	
	public function actionAdddetail($id = null)
	{		
		$this->renderPartial('//layouts/header');
		$this->render('adddetail');
		$this->renderPartial('//layouts/footer');
	}
	
	public function actionVerify($id = null){
		$actual_id = $id - 100;
		$pagedta = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_Users')
					->where('id = :id', array(':id' => $actual_id))
					->andWhere('user_status = :userStat',array(':userStat' => 0))
					->queryAll();
				if(count($pagedta) > 0){
					$post=Users::model()->findByPk($actual_id);
					$post->user_status = 1;
					$post->save();  
					Yii::app()->user->setFlash('message','Congratulations ! your account has been successfully activated.');
					$this->renderPartial('//layouts/header');
					$this->render('verification');
					$this->renderPartial('//layouts/footer'); 	
				}else{
					Yii::app()->user->setFlash('error','Something Went Wrong! Please Signup First.');
					$this->redirect(array('Site/signup'));
				} 	
	}
	
	public function actionFaq()
	{				
				$faqData = Yii::app()->db->createCommand()
							->select('*')
							->from('cads_faq')
							->where('faq_status =:status', array(':status' => 'Publish'))
							->queryAll();
				
				$faq_type = array('classified'=>'Classified/Ads');
				
				if(isset($_GET['management']) && ($_GET['management']=='true'))
				{
					$managestr = 'blisting';
				}else{
					$managestr="";
				}
				
				$this->renderPartial('//layouts/header');
				$this->render('faq',array('faq_type' =>$faq_type,'faqData' =>$faqData,'managestr'=>$managestr));
				$this->renderPartial('//layouts/footer',array('managestr'=>$managestr));
	}

	public function actionPHPINFO()
	{
		echo phpinfo();
	}
	
	
	public function actionFbLogin(){
		if(isset($_POST['fbUserID']))
		{	
			$facebookId = $_POST['fbUserID']['id'] ;
			$fbName = $_POST['fbUserID']['name'];
			
			$stringpos = strrpos($fbName," ");
			
			if($stringpos === false){
				$firstnamefb = $fbName;
				$lastnamefb = '';
			}else{
				$nameArraya = explode(" ",$fbName); 
				$firstnamefb = $nameArraya[0];
				$lastnamefb = $nameArraya[1];
			}

			$checkuser = UsersMeta::model()->findByAttributes(array('fbid'=>$facebookId));
			
			if(empty($checkuser))
			{
				$model=new Users;
				$model->name=$fbName;
				$model->user_status=1;
				$model->save();
				
				$user_id = $model->id;
				$modelM=new UsersMeta;
				$modelM->user_id=$user_id;
				$modelM->firstname=$firstnamefb;
				$modelM->lastname=$lastnamefb;
				$modelM->fbid=$facebookId;
				$modelM->save();
				
				//$checkuser = UsersMeta::model()->findByAttributes(array('fbid'=>$facebookId));
				
				Yii::app()->session['userid']=$user_id;
					//print_r($checkuser);
				$userdata = Yii::app()->db->createCommand()
							->select('U.*')
							->from('cads_Users U')
							->leftJoin('cads_UsersMeta UM','U.id=UM.user_id')
							->where('UM.fbid =:id', array(':id' => $facebookId))
							->queryAll();							
					
				if(count($userdata) > 1){
					$count = 1;
					foreach($userdata as $vale){
						if($count > 1){
							$userId = $vale['id'];
							$deleteMessages = Users::model()->findByPk($userId);
							if($deleteMessages->delete() == true):
								echo "Done";
							else:
								echo "Message did not deleted!";
							endif;
						}
						$count++;
					}	
				}	
			}else{
				$user_id = $checkuser['user_id'];
				Yii::app()->session['userid']=$user_id;
			}
		}	
	}
	
	public function actionSignin()
	{
		
		$model=new LoginForm('Front');
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$useremail = $_POST['LoginForm']['login_email'];
			$userPassword = md5($_POST['LoginForm']['password']);
			
			$pagedta = Yii::app()->db->createCommand()
					->select('id')
					->from('cads_Users')
					->where('login_email = :id', array(':id' => $useremail))
					->andWhere('user_status = :userStat',array(':userStat' => 1))
					->queryAll();
				
				if(count($pagedta) < 1 || count($pagedta) == 0){					
					Yii::app()->user->setFlash('error','Please Activate Your Account First. Email has been already send on your registered email address');
				}else{
					
					$model->attributes=$_POST['LoginForm'];
				
					if($model->validate() && $model->login())
					{	
						if(isset($_REQUEST['redirecturl']) && ($_REQUEST['redirecturl']!=''))
						{
							$this->redirect($_REQUEST['redirecturl']);
						}
						else
						{
							
							Yii::app()->user->setFlash('login','Successfully logged in');
							$this->redirect(array('Site/Index'));
						}
					}
					else
					{
						Yii::app()->user->setFlash('error','Invalid username or password');
					}	
				}
		}           
		if(isset($_REQUEST['redirect_to']) && ($_REQUEST['redirect_to']!=''))
		{
			$redirect = $_REQUEST['redirect_to'];
			$this->renderPartial('//layouts/header');
			$this->render('signin',array('model'=>$model,'redirect'=>$redirect));
			$this->renderPartial('//layouts/footer');
		}
		else
		{	
			$this->renderPartial('//layouts/header');
			$this->render('signin',array('model'=>$model));
			$this->renderPartial('//layouts/footer');
		}
		
	}
	
	public function actionSignout() {
		Yii::app()->user->logout();
		$this->redirect(array('Site/signin'));
	}
	
	public function actionPostAds()
	{
		$base_url = Yii::app()->getBaseUrl(true);
		if(isset(Yii::app()->session['userid']))
		{
		$userid = Yii::app()->session['userid'];
		if(isset($_POST['submit']))
		{
			//echo "<pre>";/*  print_r($_POST); */ print_r($_FILES); die;
			$title = $_POST['title'];
			$description = $_POST['description'];
			$url = $_POST['url'];
			$fname = $_POST['fname'];
			$phone = $_POST['phone'];
			//echo $phone; die;
			$postalcode = $_POST['postalcode'];
			$city_id = $_POST['city_id'];
			$multi_cat = $_POST['multi_cat'];
			
		
			
			$model1 = new Ads;
			$model1->post_title  = $title;
			$model1->description = $description;	
			$model1->city_id = $city_id;
			$model1->user_id = $userid;
			$model1->url = $url;
			
			$model1->latitude  = $_POST['r_lattitude'];
			$model1->longitude = $_POST['r_longitude'];
			
			$model1->contact_person = $_POST['fname'];
			$model1->phone = $phone;
			$model1->postal_code = $_POST['postalcode'];
			$model1->email = $_POST['email'];			
			$model1->created_date  = date('Y-m-d h:i:s');
			//echo "<pre>"; print_r($model1); die;
			$model1->save();
			$last_id = Yii::app()->db->getLastInsertID();
			if($last_id !== null && isset($_POST['extraField'])){
				
				foreach($_POST['extraField'] as $keyExtra => $valueextra){
					$FieldsDataModel = new FieldsData;
					$FieldsDataModel->aid  = $last_id;
					

						$FieldsDataModel->field_name = $keyExtra;	
						$FieldsDataModel->field_value = $valueextra;
					
						
					$FieldsDataModel->save();
				}
				
			}
			$Updatead = Ads::model()->findByPk($last_id);
			$Updatead->auid = "#adx".$last_id;
			$Updatead->update();
			
			/* $userMeta['firstname'] = $fname;
			$userMeta['user_contact'] = $phone;
			$userMeta['zip'] = $postalcode;
			$updateRestauratnInformation = Yii::app()->db->createCommand()->update('cads_UsersMeta', $userMeta, 'user_id=:user_id', array(':user_id' => $userid)); */
			
			if(isset($_FILES['file']))
			{
					if (is_uploaded_file($_FILES['file']['tmp_name'][0]) == true)
					{	
						$extensions = array("jpeg",	 "jpg", "png");
						foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name)
						{	
							$file_name = $key . $_FILES['file']['name'][$key];
							$file_name = str_replace(" ",'_',$file_name);
							$file_size = $_FILES['file']['size'][$key];
							$file_tmp  = $_FILES['file']['tmp_name'][$key];
							$file_type = $_FILES['file']['type'][$key];
							$fileext   = explode('.', $file_name);
							$count     = count($fileext);
							$count1    = $count - 1;
							$fileext1  = $fileext[$count1];
							$file_ext  = strtolower($fileext1);
							if ((in_array($file_ext, $extensions) === false) || ($file_size > 2097152)) {
								echo "in if"; die;
								Yii::app()->user->setFlash('error', 'Image cannot be update. Please try with .jpeg,.png image type extensions and Size below 2Mb');
							} else {			
								$modelIm       	= new AdsImages;
								$modelIm->ad_id = $last_id;
								$modelIm->image = $file_name;
								$modelIm->save();
								
								$thumbnail_width  = 90;
								$thumbnail_height = 70;
								$thumb_beforeword = "thumb";
								$thumb_path       = "images/postAds/";
								$size             = getimagesize($tmp_name);
								$original_width   = $size[0];
								$original_height  = $size[1];
								$thumb_create     = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
								switch ($file_ext) {
									case 'jpg':
										$source = imagecreatefromjpeg($tmp_name);
										break;
									case 'jpeg':
										$source = imagecreatefromjpeg($tmp_name);
										break;
									
									case 'png':
										$source = imagecreatefrompng($tmp_name);
										break;
									default:
										$source = imagecreatefromjpeg($tmp_name);
								}
							
								imagecopyresized($thumb_create, $source, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $original_width, $original_height);
								$thumbnail = $thumb_path . "thumb_" . $key . str_replace(" ",'_',$_FILES['file']['name'][$key]);
								switch ($file_ext) {
									case 'jpg' || 'jpeg':
										imagejpeg($thumb_create, $thumbnail, 100);
										break;
									case 'png':
										imagepng($thumb_create, $thumbnail, 100);
										break;
									default:
										imagejpeg($thumb_create, $thumbnail, 100);
								}
								move_uploaded_file($file_tmp, "images/postAds/" . $file_name);
							}
							
						}
					}
				}
			
			$countSet = 1;
			foreach($multi_cat as $cat):				
				$subcatName = 'multi_subcategory_'.$countSet;
				if(isset($_POST["$subcatName"])){
					foreach($_POST["$subcatName"] as $feature):
						$feature_list_name = 'multi_feature_'.$cat.'_'.$feature;			
						 $enterCategory = new AdSelected;
						$enterCategory->ad_id = $last_id;
						$enterCategory->category_id = $cat;	
						$enterCategory->subcategory_id = $feature;	
						$enterCategory->save(); 
					endforeach;
				}			
				$countSet++;	
			endforeach;
				// Multiple recipients
				$to = $_POST['email']; // note the comma
				// Subject
				
				include('MailChimp.php');
				
						$MailChimp = new MailChimp('626cff9628aea3d99107cc017218e4d7-us10');
						$results = $MailChimp->get('lists');
						$list_id = '19de311f2f';
						$results = $MailChimp->post("lists/$list_id/members", [
						'email_address' => $to,
						'status'        => 'subscribed',
						]);
						//echo "<pre>"; print_r($results); die;
						$result = $MailChimp->post("automations/7323b858b3/emails/fa9a49f239/queue", [
						'email_address' => $to,
						]);
						
				$subject = 'Ad Post On lorem Ads';

				$message = '
						<html>
						<head>
						  <title>Ad Post On lorem Ads</title>
						</head>
						<body>
							<table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #1baea6;">
							  <tr>
									<td align="left" valign="top" height="50" style="background-color:#1baea6;">
									<table width="100%" cellspacing="2" cellpadding="2">
										<tr>
											<td width="45%" align="left"><img src="'.$base_url.'/images/logo.png"></td>
											<td width="45%" align="right">
												<img src="'.$base_url.'/images/march-adsimg.png"></h1>
											</td>
										</tr>
									</table>
									</td>
							  </tr>
							  <tr>
									<td align="left" valign="top"  style="padding-left:10px;">
										<p>Dear '.$_POST['fname'].',</p>
										<p>Your Ad has been posted on ads.lorem.com successfully and send for Administration Approval. On approval you will get notification by email. </p>
										<p>Thanks for Connecting with Us. </p>
										<br />
										<p>Thanks & Regards,<br/> lorem Ads Team </p>
									</td>
							  </tr>						  
							  <tr>
									<td align="left" valign="top" height="90" style="background-color:#1baea6;">
										<table width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td align="left" valign="top" style="float: left;text-align:center;width: 100%;padding: 23px 0 0 0;margin: 0px auto;">
													<ul class="foot-link">
													<li style="display:inline-block;"><a style="color:#fff;" target="_blank" href="https://blog.lorem.com">Blog</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/about-us.html">About</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/contact-us.html">Contact Us</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/privacy-policy.html">privacy policy </a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/terms-conditions.html">Terms &amp; Conditions</a> </li>													
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/help.html">Help</a> </li>
													</ul>
												</td>
											</tr>
											<tr>
												<td style="float: left;text-align:center;width: 100%;padding: 10px 0 0 0;margin: 0px auto;">
												<p style="color:#fff;" class="copy">Copyright © 2017 ads.lorem.com, LLC. All rights reserved.</p>
												</td>
											</tr>
										</table>
									</td>
							  </tr>
							  </table>
						</body>
						</html>
						';			

				// To send HTML mail, the Content-type header must be set
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=iso-8859-1';

				// Additional headers
				$headers[] = 'To: '.$_POST['fname'].' <'.$_POST['email'].'>';
				$headers[] = 'From: lorem Team <support@cars.limo>';
				// Mail it
				if(mail($to, $subject, $message, implode("\r\n", $headers))):
					
					$to1 = 'upworksanjeev@gmail.com'; 
					
					$subject1 = 'New Ad posting on Triken Ads';
								
					$message1 = '
						<html>
						<head>
						  <title>For Approval</title> 
						</head>
						<body>
							<table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #1baea6;">
							  <tr>
									<td align="left" valign="top" height="50" style="background-color:#1baea6;">
									<table width="100%" cellspacing="2" cellpadding="2">
										<tr>
											<td width="45%" align="left"><img src="'.$base_url.'/images/logo.png"></td>
											<td width="45%" align="right">
												<img src="'.$base_url.'/images/march-adsimg.png"></h1>
											</td>
										</tr>
									</table>
									</td>
							  </tr>
							  <tr style="">
									<td align="left" valign="top" style="padding-left:10px;">
										<p>Dear Admin,<br/><br/>New Ad has been posted on ads.lorem.com and now pending for approval. </p>
									  <p>View or Update Ad Details by <a target="_blank" href="'.$base_url.'/Admin/editAd/'.$last_id.'">Click Here</a> </p>
									  <p>Approve Post by <a target="_blank" href="'.$base_url.'/Admin/approveAd/'.$last_id.'">Click Here</a> </p>
									  <br />
									   <p>Thanks & Regards,<br/>lorem Team.</p>
									</td>
							  </tr>						  
							  <tr>
									<td align="left" valign="top" height="90" style="background-color:#1baea6;">
										<table width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td align="left" valign="top" style="float: left;text-align:center;width: 100%;padding: 23px 0 0 0;margin: 0px auto;">
													<ul class="foot-link">
													<li style="display:inline-block;"><a style="color:#fff;" target="_blank" href="https://blog.lorem.com">Blog</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/about-us.html">About</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/contact-us.html">Contact Us</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/privacy-policy.html">privacy policy </a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/terms-conditions.html">Terms &amp; Conditions</a> </li>													
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/help.html">Help</a> </li>
													</ul>
												</td>
											</tr>
											<tr>
												<td style="float: left;text-align:center;width: 100%;padding: 10px 0 0 0;margin: 0px auto;">
												<p style="color:#fff;" class="copy">Copyright © 2017 ads.lorem.com, LLC. All rights reserved.</p>
												</td>
											</tr>
										</table>
									</td>
							  </tr>
							  </table>
						</body>
						</html>
						';			
					$headers1[] = 'MIME-Version: 1.0';
					$headers1[] = 'Content-type: text/html; charset=iso-8859-1';
					$headers1[] = 'From: lorem system <support@cars.limo>';
					if(mail($to1, $subject1, $message1, implode("\r\n", $headers1))):
						Yii::app()->user->setFlash('success', "Your Ad Has Been Saved Successfully");
					else:
						Yii::app()->user->setFlash('success', "Something Went Wrong! Please contact with Administration");
					endif;
				else:
					Yii::app()->user->setFlash('success', "Something Went Wrong! Please contact with Administration");
				endif;
			
				$this->redirect(array('Site/PostAds'));
		}
		else
		{	
			$criteria=new CDbCriteria();
			$criteria->select = '*';
			$criteria->condition = 'category_status =:status';
			$criteria->params = array(':status' => 1);
			$Categories = Categories::model()->findAll($criteria);
			$users = Users::model()->with('UserMeta')->findByPk($userid);
			//echo "<pre>"; print_r($users); die;
			
			$this->renderPartial('//layouts/header');
			$this->render('postAds',array('categories' => $Categories,'user'=>$users));
			$this->renderPartial('//layouts/footer');
		}
		}
		else{
			$this->redirect(array('Site/Signin'));
		}
	}
	public function actionGetFeatureSelction(){
		$category_id = $_REQUEST["searchinput"];
		$setCount = $_REQUEST["setCount"];		
		
		$criteria=new CDbCriteria();
		$criteria->condition = 'category_id=:cat_id and subcategory_status=:status';
		$criteria->params = array(':cat_id'=>$category_id,':status'=>1);
		$criteria->order = 'subcategory_name ASC';
		$allcategory=Subcategories::model()->with('Categories')->findAll($criteria);	
	
		$stringOutput = '';
		if(count($allcategory) > 0): 
				$stringOutput = '<div class="control-group"><label class="control-label">'.$allcategory[0]['Categories'][0]['category_name'].' Subcategories </label><select onchange="selectedsubcate(this);" id="subcategory_selection_box_'.$setCount.'" class="form-control chzn-select"  name="multi_subcategory_'.$setCount.'[]" multiple="multiple">';				 
					foreach($allcategory as $datavalue):
						$stringOutput .= "<option value=".$datavalue['id'].">".$datavalue['subcategory_name']."</option>";
					endforeach;		
                $stringOutput .= '</select></div>';		
		else:	
			$stringOutput = '';	
		endif;
		echo $stringOutput;		
	}
	public function actionGetFeatureSelctionFields(){
			$subID = $_REQUEST['featureID'];	
			$fieldID = Yii::app()->db->createCommand()
					->select('fields_id')
					->from('cads_Catsubcat_relation')
					->where('subcategory_id = :id', array(':id' => $subID))
					->queryRow();
		
			if(isset($fieldID) && $fieldID !== null){
				$fieldString = $fieldID["fields_id"];
				$fieldAarry = explode(',',$fieldString);
				
				
				$htmlNewField = '';
				
				foreach($fieldAarry as $fieldSingelId){
					$fieldData = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_fields')
						->where('id = :sid', array(':sid' => $fieldSingelId))
						->queryRow();
					
					$fieldType = $fieldData['fieldtype'];
					$fieldUnserialValue = unserialize($fieldData['field_value']);
					
					
					$fieldLabel = $fieldData['field_label'];
					//$fieldName = strtolower(str_replace(" ","_",$fieldLabel));
					$fieldName = $fieldLabel;
					
					
					
					switch ($fieldType) {
											case "SelectDropdown":
													if(isset($_REQUEST['adid'])){
															$selectedFields = Yii::app()->db->createCommand()
															->select('*')
															->from('cads_Ads_fields')
															->where('aid = :id', array(':id' => $_REQUEST['adid']))
															->andwhere('field_name = :fname', array(':fname' =>$fieldName))
															->queryRow();
															if(!empty($fieldUnserialValue)){
																$selectionHtml = '<div class="form-group">
																		<label for="'.$fieldLabel .'">'.$fieldLabel .'</label><select class="form-control"  name="extraField['.$fieldName.']" required><option value="">-Select here '.$fieldLabel.'-</option>';
																		foreach($fieldUnserialValue as $fieldUnserialValueSingle){
																			$selectedLabel = '';
																			if($selectedFields['field_value'] == $fieldUnserialValueSingle){
																				$selectedLabel = 'selected';
																			}
																			
																			$selectionHtml .= '<option '.$selectedLabel.' value="'.$fieldUnserialValueSingle.'">'.$fieldUnserialValueSingle.'</option>';
																		}
																$selectionHtml .='</select></div>';		
															}else{
																$selectionHtml ='';
															}
															
														
													}else{
														if(!empty($fieldUnserialValue)){
															
															$selectionHtml = '<div class="form-group">
																			<label for="'.$fieldLabel .'">'.$fieldLabel .'</label><select class="form-control"  name="extraField['.$fieldName.']" required><option value="">-Select here '.$fieldLabel.'-</option>';
																			foreach($fieldUnserialValue as $fieldUnserialValueSingle){
																				$selectionHtml .= '<option value="'.$fieldUnserialValueSingle.'">'.$fieldUnserialValueSingle.'</option>';
																			}
															$selectionHtml .='</select></div>';	
														}else{
															$selectionHtml ='';
														}	
													}
													$htmlNewField .= $selectionHtml;
												break;
												
											case "RadioButton":
													if(isset($_REQUEST['adid'])){
														if(!empty($fieldUnserialValue)){
															$selectedFields = Yii::app()->db->createCommand()
															->select('*')
															->from('cads_Ads_fields')
															->where('aid = :id', array(':id' => $_REQUEST['adid']))
															->andwhere('field_name = :fname', array(':fname' =>$fieldName))
															->queryRow();
															$radioHtml = '<div class="form-group">
																		<label for="'.$fieldLabel .'">'.$fieldLabel .'</label></br>';
																		foreach($fieldUnserialValue as $fieldUnserialValueSingle){
																			$selectedLabel = '';
																			if($selectedFields['field_value'] == $fieldUnserialValueSingle){
																				$selectedLabel = 'checked="checked"';
																			}
																			
																			$radioHtml .= ' <input type="radio" name="extraField['.$fieldName.']" '.$selectedLabel.' value="'.$fieldUnserialValueSingle.'"> '.$fieldUnserialValueSingle.'</br>';
																		}
															$radioHtml .='</div>';
															$htmlNewField .= $radioHtml;
														}else{
															$selectionHtml ='';
														}		
													}else{
														if(!empty($fieldUnserialValue)){
															$radioHtml = '<div class="form-group">
																			<label for="'.$fieldLabel .'">'.$fieldLabel .'</label></br>';
																			foreach($fieldUnserialValue as $fieldUnserialValueSingle){
																				$radioHtml .= ' <input type="radio" name="extraField['.$fieldName.']" value="'.$fieldUnserialValueSingle.'"> '.$fieldUnserialValueSingle.'</br>';
																			}
															$radioHtml .='</div>';
															$htmlNewField .= $radioHtml;	
														}else{
															$selectionHtml ='';
														}	
													}		
													
												break;
												
											case "Checkboxes":
													if(isset($_REQUEST['adid'])){
															$selectedFields = Yii::app()->db->createCommand()
															->select('*')
															->from('cads_Ads_fields')
															->where('aid = :id', array(':id' => $_REQUEST['adid']))
															->andwhere('field_name = :fname', array(':fname' =>$fieldName))
															->queryRow();
													}else{
														if(!empty($fieldUnserialValue)){
															$checkHtml = '<div class="form-group">
																			<label for="'.$fieldLabel .'">'.$fieldLabel .'</label></br>';
																			foreach($fieldUnserialValue as $fieldUnserialValueSingle){
																				$selectedLabel = '';
																				if($selectedFields['field_value'] == $fieldUnserialValueSingle){
																					$selectedLabel = 'checked';
																				}
																				
																				$checkHtml .= ' <input  '.$selectedLabel.' type="checkbox" name="extraField['.$fieldName.']" value="'.$fieldUnserialValueSingle.'"> '.$fieldUnserialValueSingle.'</br>';
																			}
															$checkHtml .='</div>';
															$htmlNewField .= $checkHtml;
														}else{
															$selectionHtml ='';
														}				
													}		
													
												break;
												
											case "text":
													if(isset($_REQUEST['adid'])){
															$selectedFields = Yii::app()->db->createCommand()
															->select('*')
															->from('cads_Ads_fields')
															->where('aid = :id', array(':id' => $_REQUEST['adid']))
															->andwhere('field_name = :fname', array(':fname' =>$fieldName))
															->queryRow();
															
															$textHtml = '<div class="form-group">
																		<label for="'.$fieldLabel .'">'.$fieldLabel .'</label><input type="text" name="extraField['.$fieldName.']" class="form-control" id="'.$fieldName.'" value="'.$selectedFields['field_value'].'" placeholder="" required>';
																		
															$textHtml .='</div>';
															$htmlNewField .= $textHtml;
													}else{
														$textHtml = '<div class="form-group">
																		<label for="'.$fieldLabel .'">'.$fieldLabel .'</label><input type="text" name="extraField['.$fieldName.']" class="form-control" id="'.$fieldName.'" placeholder="" required>';
																		
														$textHtml .='</div>';
														$htmlNewField .= $textHtml;
													}
													
												break;
												
											case "textArea":
													if(isset($_POST['adid'])){
															$selectedFields = Yii::app()->db->createCommand()
															->select('*')
															->from('cads_Ads_fields')
															->where('aid = :id', array(':id' => $_REQUEST['adid']))
															->andwhere('field_name = :fname', array(':fname' =>$fieldName))
															->queryRow();
															$textareaHtml = '<div class="form-group">
																				<label for="'.$fieldLabel .'">'.$fieldLabel .'</label><textarea class="form-control textdescrip" required name="extraField['.$fieldName.']" rows="3" placeholder="">'.$selectedFields['field_value'] .'</textarea>';
																				
															$textareaHtml .='</div>';
															$htmlNewField .= $textareaHtml;
													}else{
														$textareaHtml = '<div class="form-group">
																		<label for="'.$fieldLabel .'">'.$fieldLabel .'</label><textarea class="form-control textdescrip" required name="extraField['.$fieldName.']" rows="3" placeholder=""></textarea>';
																		
														$textareaHtml .='</div>';
														$htmlNewField .= $textareaHtml;
													}		
													
												break;
												
											default:
												echo "";
										} 
					
				} 
				echo $htmlNewField;	
				
			}else{
				echo '';
			}
			
		
			
	}
	public function actionGetFeatureSelctionforUpdate(){
		$category_id = $_REQUEST["searchinput"];
		$business_id = $_REQUEST["business_id"];
		$setCount = $_REQUEST["setCount"];
		
		$criteria=new CDbCriteria();
		$criteria->condition = 'category_id=:cat_id and subcategory_status=:status';
		$criteria->params = array(':cat_id'=>$category_id,':status'=>1);
		$featcher=Subcategories::model()->with('Categories')->findAll($criteria);	

							
		$subcategorySelected = Yii::app()->db->createCommand()
								->select('FeaturesSelected.*')
								->from('cads_ads_selectedCategories FeaturesSelected')
								->where('FeaturesSelected.ad_id =:business_id', array(":business_id" => $business_id))
								->queryAll();
										
		$selected_features_id = array();
		
		foreach($subcategorySelected as $featcherListSelect):
				$selected_features_id[] = $featcherListSelect['subcategory_id'];	
		endforeach;
	
		$stringOutput = '';
		if(count($featcher) > 0): 
				$stringOutput = '<div class="control-group"><label class="control-label">'.$featcher[0]['Categories'][0]['category_name'] .' Categories</label><div class="controls"><select id="subcategory_selection_box_'.$setCount.'" class="m-wrap span12 chzn-select" data-adid="'.$business_id.'" data-category="'.$featcher[0]['Categories'][0]['id'] .'" name="multi_subcategory_'.$setCount.'[]" multiple="multiple" onchange="selectedsubcateUpdate(this);" required">';				 
					foreach($featcher as $datavalue):
						if(in_array($datavalue['id'], $selected_features_id)){
							$stringOutput .= "<option selected value=".$datavalue['id'].">".$datavalue['subcategory_name']."</option>";
						}else{
							$stringOutput .= "<option value=".$datavalue['id'].">".$datavalue['subcategory_name']."</option>";
						}
						
					endforeach;		
                $stringOutput .= '</select></div></div>';		  
		else:	
			$stringOutput = '';	
		endif;
		echo $stringOutput;	
	}
	
	
	
	
public function actionGetChosenCity()
	{
		$base_url = Yii::app()->getBaseUrl(true);
		$searchinput = $_REQUEST['searchinput'];
		$citydata = Yii::app()->db->createCommand()
					->select('city.*,city.id  cityid,state.*,country.*')
					->from('cads_City city')
					->leftJoin('cads_CityState cityState', 'cityState.cityid=city.id')
					->leftJoin('cads_State state', 'state.id=cityState.stateid')
					->leftJoin('cads_CountryState stateCountry', 'state.id=stateCountry.stateid')
					->leftJoin('cads_Country country', 'country.id=stateCountry.countryid')
					->where(array('like', 'city.cityname', "%$searchinput%"))
					->limit(25)
					->queryAll();
		if(count($citydata) > 0):			
				$output = '<ul>';
				foreach($citydata as $val){
					$city = $val['cityname'];
					$state = $val['statename'];
					$country = $val['countryname'];
					$cityid = $val['cityid'];
					$statenamearray =explode(" ",$state);
					$statenamestring = implode("_",$statenamearray);
					$countrynamearray =explode(" ",$country);
					$countrynamestring = implode("_",$countrynamearray);
					$output.= '<li id="'.$cityid.'" onclick="selectcity(this);">'.$city.', '.$state.', '.$country.'</li>
					<input type="hidden" id="ct'.$cityid.'" value="'.$cityid.'" /><input type="hidden" id="ci'.$cityid.'" value="'.str_replace(" ","_",trim($city)).'_'.str_replace(" ","_",trim($state)).'" />';
				} 
				$output .= '</ul>';
		else:
				$output = '<div>No Result Found!</div>';
		endif;
		echo $output;
	}
	
	
public function actionGetChosenUser()
	{
		$base_url = Yii::app()->getBaseUrl(true);
		$searchinput = $_REQUEST['searchinput'];
		$userData = Yii::app()->db->createCommand()
					->select('user.*')
					->from('cads_Users user')
					->leftJoin('cads_UsersMeta userMeta', 'userMeta.user_id=user.id')
					->where(array('like', 'user.name', "%$searchinput%"))
					->limit(25)
					->queryAll();
		if(count($userData) > 0):			
				$output = '<ul>';
				foreach($userData as $val){
		
					$output.= '<li id="'.$val['id'].'" onclick="selectuser(this);">'.$val['name'].'</li>
					<input type="hidden" id="ct'.$val['id'].'" value="'.$val['id'].'" /><input type="hidden" id="ci'.$val['id'].'" value="'.str_replace(" ","_",trim($val['name'])).'" />';
				} 
				$output .= '</ul>';
		else:
				$output = '<div>No Result Found!</div>';
		endif;
		echo $output;
	}	
	
	
	public function actiongetSubcategory(){
	if(!empty($_REQUEST['categoryname']) && !empty($_REQUEST['categoryid'])){
		$catid =  $_REQUEST['categoryid'];
	//$Subcategories=Subcategories::model()->with('Catsubcat')->findAll();	
	$Subcategories = Yii::app()->db->createCommand()
	->select('*')
	->from('cads_Subcategories sc')
	->leftJoin('cads_Catsubcat_relation csr','sc.id=csr.subcategory_id')
	->where('csr.category_id=:catid',array(':catid'=>$catid))
	->andwhere('sc.subcategory_status=:status',array(':status'=>'1'))
	->queryAll();?>
	
	<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
		All <?php echo $_REQUEST['categoryname'];?>
		<span class="caret aa"></span>
	</button>
	<ul id="subcategories" class="dropdown-menu" aria-labelledby="dropdownMenu2">
		<li onclick="return subcategoryname('','All <?php echo $_REQUEST['categoryname'];?>');"><a tabindex="3">All <?php echo $_REQUEST['categoryname'];?></a></li>
		<?php if(!empty($Subcategories)){
			foreach($Subcategories as $Subcategory){?>
			<li id="<?php echo $Subcategory['id'];?>" onclick="return subcategoryname('<?php echo $Subcategory['id'];?>','<?php echo $Subcategory['subcategory_name'];?>');"><a tabindex="3"><?php echo $Subcategory['subcategory_name'];?></a></li>
			<?php } 
		} else {?>
			<li>No Subcategories</li>
		<?php } ?>
	</ul> 
<?php }
	}
	public function actionGetChosenzipcode()
	{
		$base_url = Yii::app()->getBaseUrl(true);
		$searchinput = $_REQUEST['searchinput'];
		$citydata = Yii::app()->db->createCommand()
					->select('city.*,city.id cityid,city.city_zipcode zipcode,state.*,country.*')
					->from('cads_City city')
					->leftJoin('cads_CityState cityState', 'cityState.cityid=city.id')
					->leftJoin('cads_State state', 'state.id=cityState.stateid')
					->leftJoin('cads_CountryState stateCountry', 'state.id=stateCountry.stateid')
					->leftJoin('cads_Country country', 'country.id=stateCountry.countryid')
					->where(array('like', 'city.cityname', "%$searchinput%"))
					->orwhere(array('like', 'city.city_zipcode', "%$searchinput%"))
					->limit(25)
					->queryAll();
		if(count($citydata) > 0):			
				$output = '<ul>';
				foreach($citydata as $val){
					$city = $val['cityname'];
					$zipcode = $val['city_zipcode'];
					$state = $val['statename'];
					$country = $val['countryname'];
					$cityid = $val['cityid'];
					$statenamearray =explode(" ",$state);
					$statenamestring = implode("_",$statenamearray);
					$countrynamearray =explode(" ",$country);
					$countrynamestring = implode("_",$countrynamearray);
					$output.= '<li id="cts'.$cityid.'" onclick="selectzipcity('.$cityid.');">'.$zipcode.' '.$city.', '.$state.', '.$country.'</li>
					<input type="hidden" id="ct'.$cityid.'" value="'.$cityid.'" /><input type="hidden" id="ci'.$cityid.'" value="'.str_replace(" ","_",trim($city)).'_'.str_replace(" ","_",trim($state)).'" />';
				} 
				$output .= '</ul>';
		else:
				$output = '<div>No Result Found!</div>';
		endif;
		echo $output;
	}
	public function actionSearch(){		
		
		//echo "<pre>"; Print_r($_REQUEST); echo "</pre>"; die;
		if(!isset($_REQUEST['searchcat']))
		{
			$this->redirect(array('/index.php'));			
		}

		$currentcategory = urldecode($_REQUEST['searchcat']);
		$currentcategory = str_replace("slash", "/", $currentcategory);
		/*	$currentcategory = @str_replace("_"," ",$_REQUEST['searchcat']);
			$currentcategory = @str_replace("and","&",$currentcategory);
			$currentcategory = @str_replace("slash","/",$currentcategory);
		
		if($currentcategory=='Musicians & B&s'){
			$currentcategory='Musicians & Bands';
		}*/
		//echo $currentcategory; die;
		$Categorytype	= @$_REQUEST['category_type'];
		//echo $Categoryid		= @$_REQUEST['cat_id']; die;
		$Cityid			= @$_REQUEST['cityid'];
		$Searchcategoryname	= @$_REQUEST['searchcat'];
		$Cityname	= @$_REQUEST['cityname'];
		
		$citynam = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_City city')
					->leftJoin('cads_CityState cityState', 'city.id=cityState.cityid')
					->leftJoin('cads_State state', 'state.id=cityState.stateid')
					->leftJoin('cads_CountryState stateCountry', 'cityState.stateid=stateCountry.stateid')
					->leftJoin('cads_Country country', 'country.id=stateCountry.countryid')
					->where(array('like', 'city.id', $Cityid))
					->queryRow();
					if($citynam['statename']!='' || $citynam['statename']!='N/A')
					{
						$citystr = $citynam['cityname']."_".$citynam['statename'];
					}
					else
					{
						$citystr = $citynam['cityname']."_".$citynam['countryname'];
					}
		/* echo '<pre>';
		print_r($citynam);
		die;  */
					$commonstr['city_id'] = $citynam['cityid'];
					$commonstr['state_id'] = $citynam['stateid'];
					$commonstr['country_id'] = $citynam['Countryid'];
					$commonstr['town'] = $citystr;
					$commonstr['cityname'] =  $citynam['cityname'];
					$commonstr['statename'] = @$citynam['statename'];
					$commonstr['countryname'] = @$citynam['countryname'];
			
			
			$ct = str_replace(array(' ',','),'+',$citynam['cityname']);
			$stat= str_replace(' ','+',$citynam['statename']);
			$coun= str_replace(' ','+',$citynam['countryname']);
			$city = $ct."+".$stat."+";
			$city = str_replace( array( '%', '#'), '', $city);
			$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$city.'&sensor=false');
			$output= json_decode($geocode);
			
			
		if(!empty($output->results)):
			$lat = $output->results[0]->geometry->location->lat;
			$long = $output->results[0]->geometry->location->lng;
		else:
			$lat = '';
			$long = '';
		endif;
		

		$minlatt 		= $lat-50;
		$maxlatt 		= $lat+50;		
		$minlong 		= $long-50;
		$maxlong 		= $long+50;
		


		
		$CtystatCountry	= @$_REQUEST['state_ctycountry'];
		
		if($Categorytype == 'category'){
			$currentcategoryid	= Categories::model()->find('category_name=:postID', array(':postID'=>$currentcategory));
			$Categoryid = $currentcategoryid->id;
			$categoryname	= Categories::model()->find('id=:postID', array(':postID'=>$Categoryid));
			$relatedsubcategories = Yii::app()->db->createCommand()
				->select('S.*,CSR.category_id')
				->from('cads_Subcategories S')
				->leftJoin('cads_Catsubcat_relation CSR', 'S.id = CSR.subcategory_id')
				->where('CSR.category_id=:CatID',array(':CatID'=>$Categoryid))
				->andwhere('S.subcategory_status=:Status',array(':Status'=>1))
				->limit(10, 0)
				->queryAll();			
		} else{
			$currentcategoryid	= Subcategories::model()->find('subcategory_name=:subcatid', array(':subcatid'=>$currentcategory));
			$Categoryid = $currentcategoryid->id;
			$categoryname	= Subcategories::model()->find('id=:subcatid', array(':subcatid'=>$Categoryid));
			$parentcat	= Yii::app()->db->createCommand()
						->select('category_id')
						->from('cads_Catsubcat_relation')
						->where('subcategory_id=:SCatID',array(':SCatID'=>$Categoryid))
						->queryrow();
			$parentcatid = $parentcat['category_id'];
			$relatedsubcategories = Yii::app()->db->createCommand()
				->select('S.*,CSR.category_id')
				->from('cads_Subcategories S')
				->leftJoin('cads_Catsubcat_relation CSR', 'S.id = CSR.subcategory_id')
				->where('CSR.category_id=:CatID',array(':CatID'=>$parentcatid))
				->andwhere('S.subcategory_status=:Status',array(':Status'=>1))
				->limit(10, 0)
				->queryAll();
		}
		//echo "<pre>"; print_r($currentcategoryid); die;
		if($Categoryid<>''){		
		
			$command = Yii::app()->db->createCommand()
			->select('A.*,A.id as adid')
			->from('cads_Ads A')
			->leftJoin('cads_City city', 'A.city_id=city.id')
			->leftJoin('cads_ads_selectedCategories adsc', 'A.id=adsc.ad_id');	
			
			if (!empty($Cityid)) {
				$command->where('A.city_id=:Cityid',array(':Cityid'=>$Cityid));
			}
			
			if($Categorytype == 'category'){
				$command->andwhere('adsc.category_id=:Catid',array(':Catid'=>$Categoryid));
			} else{
				$command->andwhere('adsc.subcategory_id=:Scatid',array(':Scatid'=>$Categoryid));
			}
			
			$command->andwhere('A.ad_flaged=:adflag',array(':adflag'=>0));
			$command->andwhere('A.ad_status=:status',array(':status'=>1));
			$command->group('A.post_title');		
			$Adssql = $command->queryAll();	
		
			/* echo '<pre>';
			print_r( $Adssql );
			die; */
		
		
			if(count($Adssql) < 5){
				if($Categorytype == 'category'){
					$cond = " adsc.category_id = $Categoryid ";
				} else{
					$cond = " adsc.subcategory_id=$Categoryid ";
				}
				$cond .= " AND A.longitude > $minlong AND A.longitude < $maxlong AND A.latitude > $minlatt AND A.latitude < $maxlatt ";
				$cond .= " AND A.ad_flaged=0 AND A.ad_status=1 ";
				$sql = "SELECT A.*,A.id adid 
						FROM cads_Ads A
						LEFT JOIN cads_ads_selectedCategories adsc ON A.id = adsc.ad_id
						WHERE $cond";
				$Adssql = Yii::app()->db->createCommand($sql)->queryAll();
			}
		}
		
		$this->renderPartial('//layouts/header-inner');
		$this->render('Search',array('category_name' => $categoryname,'Adsdata' => $Adssql,'Searchcategoryname' =>$Searchcategoryname,'Cityname'=>$Cityname,'CtystatCountry'=>$CtystatCountry,'Categorytype'=>$Categorytype,'relatedsubcategories'=>$relatedsubcategories));
		$this->renderPartial('//layouts/footer');		
	}
	
	public function actionProfileCountAdd(){
		if(isset($_REQUEST['userId'])){
			$userId = $_REQUEST['userId'];
			$Ipaddress = $_SERVER['REMOTE_ADDR'];		
			$Adcountdata = Yii::app()->db->createCommand()
			->select('*')
			->from('cads_click_count')		
			->where('user_id=:user_id',array(':user_id'=>$userId))
			->andwhere('ip_address=:ipad',array(':ipad'=>$Ipaddress))
			->queryRow();
			if(isset($Adcountdata) && empty($Adcountdata)){
				$Newadcount				=	new ProfileCounts;
				$Newadcount->ip_address	=	$Ipaddress;
				$Newadcount->profile_count	=	1;
				$Newadcount->user_id		=	$userId;
				$Newadcount->save();
			} else {
				$Newadcount				=	ProfileCounts::model()->findbyPk($Adcountdata['id']);
				$Newadcount->profile_count	=	$Adcountdata['profile_count']+1;
				$Newadcount->update();
			} 
			echo 'Successfull';
		}else{
			echo 'Something Went Wrong';
		}
		
		
	}
	public function actionAdsdetail($id = null){
		//die('<h1>This Page under development </h1>');
		$Ipaddress = $_SERVER['REMOTE_ADDR'];		
		$Adcountdata = Yii::app()->db->createCommand()
		->select('*')
		->from('cads_adcount')		
		->where('status=:countstatus',array(':countstatus'=>1))
		->andwhere('ad_id=:adid',array(':adid'=>$id))
		->andwhere('ip_address=:ipad',array(':ipad'=>$Ipaddress))
		->queryRow();
		if(isset($Adcountdata) && empty($Adcountdata)){
			$Newadcount				=	new AdsCount;
			$Newadcount->ip_address	=	$Ipaddress;
			$Newadcount->ad_count	=	1;
			$Newadcount->ad_id		=	$id;
			$Newadcount->save();
		} else {
			$Newadcount				=	AdsCount::model()->findbyPk($Adcountdata['id']);
			$Newadcount->ad_count	=	$Adcountdata['ad_count']+1;
			$Newadcount->update();
		}
		
		$Adsdata = Yii::app()->db->createCommand()
		->select('A.*,A.id as adid,city.cityname,city_zipcode as cityzip,UM.firstname,UM.lastname,UM.user_contact,U.login_email')
		->from('cads_Ads A')
		->leftJoin('cads_City city', 'A.city_id=city.id')
		->leftJoin('cads_Users U', 'A.user_id=U.id')
        ->leftJoin('cads_UsersMeta UM', 'A.user_id=UM.user_id')
		/* ->where('A.ad_status=:status',array(':status'=>1))
		->andwhere('A.ad_flaged=:flag',array(':flag'=>0)) */
		->andwhere('A.id=:Adid',array(':Adid'=>$id))		
		->queryRow();
		
		$AdsFieldData = Yii::app()->db->createCommand()
		->select('*')
		->from('cads_Ads_fields')
		->where('cads_Ads_fields.aid=:status',array(':status'=>$id))		
		->queryAll();
		
		//echo "<pre>"; print_r($AdsFieldData); echo "</pre>";
		$cityid = $Adsdata['city_id'];
		$citydata = Yii::app()->db->createCommand()
			->select('city.*,city.id cityid,city.city_zipcode zipcode,state.*,country.*')
			->from('cads_City city')
			->leftJoin('cads_CityState cityState', 'cityState.cityid=city.id')
			->leftJoin('cads_State state', 'state.id=cityState.stateid')
			->leftJoin('cads_CountryState stateCountry', 'state.id=stateCountry.stateid')
			->leftJoin('cads_Country country', 'country.id=stateCountry.countryid')
			->where('city.id=:CID',array(':CID'=>$cityid))
			->limit(25)
			->queryRow();
		$location ='';
		if(isset($citydata['zipcode'])){
			$location= $citydata['zipcode'];
		}
		if(isset($citydata['cityname'])){
			$location.= " ".$citydata['cityname'].",";
		}
		if(isset($citydata['statename'])){
			$location.= " ".$citydata['statename'].",";
		}
		if(isset($citydata['countrycode'])){
			$location.= " ".$citydata['countrycode'];
		}
		
		
		/* Getting Categories Name for current Ad on Ads detail Page By Pankaj */
		$Selectedcatdetails_cmd = Yii::app()->db->createCommand()
		->select('scat.category_id,scat.subcategory_id')
		->from('cads_ads_selectedCategories scat')
		->where('scat.ad_id=:ad_id',array(':ad_id'=>$id))		
		->queryAll();
		//print_r($Selectedcatdetails_cmd); die;
		$adscategory = array();
		$adsubcategory = array();
		foreach($Selectedcatdetails_cmd as $Selectedcatdetailscmd){
			array_push($adscategory,$Selectedcatdetailscmd['category_id']);
			array_push($adsubcategory,$Selectedcatdetailscmd['subcategory_id']);
		}
		$adscategory_details = implode(",",$adscategory);
		
		$adsubcategory_details = implode(",",$adsubcategory);
		$AdsCategories_sql = "SELECT category_name FROM  cads_Categories WHERE id IN($adscategory_details) ";	
		$AdsCategories = Yii::app()->db->createCommand($AdsCategories_sql)->queryAll();
		
		$AdsubCategories_sql = "SELECT subcategory_name FROM  cads_Subcategories WHERE id IN($adsubcategory_details) ";	
		$AdsubCategories = Yii::app()->db->createCommand($AdsubCategories_sql)->queryAll();
		
		$criteria=new CDbCriteria();
		$criteria->select = '*';
		$allcategory = Categories::model()->findAll($criteria);
		
		/* End Getting Categories Name for current Ad on Ads detail Page By Pankaj */
		
		
		/* Getting Images Corresponding to the Ad if any*/
		$criteria=new CDbCriteria();
		$criteria->select = '*';
		$criteria->condition = 'ad_id =:adid';
		$criteria->params = array(':adid' => $id);
		$images = AdsImages::model()->findAll($criteria);
		/* //Getting Images Corresponding to the Ad if any*/
		
		
		
		if(isset(Yii::app()->session['userid'])){
			$userid = Yii::app()->session['userid'];
			$CurrentUserdetails = Yii::app()->db->createCommand()
				->select('U.id,U.login_email,CUM.firstname,CUM.lastname')
				->from('cads_Users U')
				->leftJoin('cads_UsersMeta CUM', 'U.id=CUM.user_id')
				->where('CUM.user_id=:Uid',array(':Uid'=>$userid))
				->queryRow();
		} else {
			$CurrentUserdetails=array();
		}	
		$metatitle = $Adsdata['post_title'];
		$metatitle .= " | ".$AdsCategories[0]['category_name'];
	
		if(isset($Adsdata) && !empty($Adsdata)){
			
			
			$this->renderPartial('//layouts/header-detail',array(
																	'Adsdata'=>$Adsdata,
																	'loginuser'=>$CurrentUserdetails,
																	'adscategorynames'=>$AdsCategories,
																	'images'=>@$images,
																	'allcategory'=>$allcategory,
																	'AdsubCategories'=>$AdsubCategories,
																	'location'=>$location,
																	'adscategory'=>$adscategory,
																	'adsubcategory'=>$adsubcategory,
																	'AdsFieldData' => $AdsFieldData,
																	'metatitle' => $metatitle
																)
								);
			$this->render('Adsdetail',array(
												'Adsdata'=>$Adsdata,
												'loginuser'=>$CurrentUserdetails,
												'adscategorynames'=>$AdsCategories,
												'images'=>@$images,
												'allcategory'=>$allcategory,
												'AdsubCategories'=>$AdsubCategories,
												'location'=>$location,
												'adscategory'=>$adscategory,
												'adsubcategory'=>$adsubcategory,
												'Adcountdata'=>$Adcountdata,
												'AdsFieldData' => $AdsFieldData
											)
						);
			$this->renderPartial('//layouts/footer');
		} else {
			$this->redirect(array('Site/Search'));
		}
		
	}
	public function actionReportAds(){
		$base_url = Yii::app()->getBaseUrl(true);
		$problemtype	=	$_POST['problem_type'];
		$email			=	$_POST['email'];
		$sendresponse	=	$_POST['ra_agree'];
			if($sendresponse==1){
				$sendresponse='Yes';
			} else {
				$sendresponse='No';
			}
		$comments		=	strip_tags($_POST['comments'],"<br/><a>");
		$addid			=	$_POST['adsid'];
		$AdId			=   str_replace("#adx",'',$addid);
		$Ad_ownereid	=	$_POST['adowneremail'];
		$Ad_ownername	=	$_POST['adownername'];
		if(isset(Yii::app()->session['userid'])){
			$userid = Yii::app()->session['userid'];
			$CurrentUserdetails = Yii::app()->db->createCommand()
				->select('firstname,lastname')
				->from('cads_UsersMeta CUM')
				->where('CUM.user_id=:Uid',array(':Uid'=>$userid))
				->queryRow();
		}
		
		$AdminUserdetails = Yii::app()->db->createCommand()
		->select('u.login_email,um.firstname,um.lastname')
		->from('cads_Users u')
		->leftJoin('cads_UsersMeta um', 'u.id=um.user_id')
		->where('u.id=:aid',array(':aid'=>1))
		->queryRow();
		
		$to  = 'upworksanjeev@gmail.com'; //$AdminUserdetails['login_email'];

		$cc  = $Ad_ownereid;
		$subject = 'Report This Ads against Ad Id '.$addid.' On Lorem ClassifiedAds';
		
		if(isset($CurrentUserdetails)){
			$message = '
						<html>
						<head>
						  <title>Report This Ads against Ad Id'.$addid.'</title> 
						</head>
						<body>
							<table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #1baea6;">
							  <tr>
									<td align="left" valign="top" height="50" style="background-color:#1baea6;">
									<table width="100%" cellspacing="2" cellpadding="2">
										<tr>
											<td width="45%" align="left"><img src="'.$base_url.'/images/logo.png"></td>
											<td width="45%" align="right">
												<img src="'.$base_url.'/images/march-adsimg.png"></h1>
											</td>
										</tr>
									</table>
									</td>
							  </tr>
							  <tr style="">
									<td align="left" valign="top" style="padding-left:10px;">
										<p>Dear Admin,<br/><br/> A user has Submit a report to this Ad against Ad Id '.$addid.' On lorem ClassifiedAds. Please Check the details given below.</p>
										<p> First Name                 :  '.$CurrentUserdetails['firstname'].'</p>
										  <p> Last Name                  :  '.$CurrentUserdetails['lastname'].'</p>
										  <p> Email Id                   :  '.$email.'</p>	
										  <p> Problem Type               :  '.$problemtype.'</p>	
										  <p> Like a response :  '.$sendresponse.'</p>	
										  <p> Comments                   :  '.$comments.'</p>
									</td>
							  </tr>						  
							  <tr>
									<td align="left" valign="top" height="90" style="background-color:#1baea6;">
										<table width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td align="left" valign="top" style="float: left;text-align:center;width: 100%;padding: 23px 0 0 0;margin: 0px auto;">
													<ul class="foot-link">
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/about-us.html">About</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/contact-us.html">Contact Us</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/privacy-policy.html">privacy policy </a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/terms-conditions.html">Terms &amp; Conditions</a> </li>													
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/help.html">Help</a> </li>
													</ul>
												</td>
											</tr>
											<tr>
												<td style="float: left;text-align:center;width: 100%;padding: 10px 0 0 0;margin: 0px auto;">
												<p style="color:#fff;" class="copy">Copyright © 2017 ads.Lorem.com, LLC. All rights reserved.</p>
												</td>
											</tr>
										</table>
									</td>
							  </tr>
							  </table>
						</body>
						</html>
						';
		} else {
			$message = '
						<html>
						<head>
						  <title>Report This Ads against Ad Id'.$addid.'</title> 
						</head>
						<body>
							<table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #1baea6;">
							  <tr>
									<td align="left" valign="top" height="50" style="background-color:#1baea6;">
									<table width="100%" cellspacing="2" cellpadding="2">
										<tr>
											<td width="45%" align="left"><img src="'.$base_url.'/images/logo.png"></td>
											<td width="45%" align="right">
												<img src="'.$base_url.'/images/march-adsimg.png"></h1>
											</td>
										</tr>
									</table>
									</td>
							  </tr>
							  <tr style="">
									<td align="left" valign="top" style="padding-left:10px;">
										<p>Dear Admin,<br/><br/> A user has Submit a report to this Ad against Ad Id '.$addid.' On Lorem ClassifiedAds. Please Check the details given below.</p>
										  <p> Email Id : '.$email.'</p>	
										  <p> Problem Type : '.$problemtype.'</p>	
										  <p> Like a response : '.$sendresponse.'</p>	
										  <p> Comments  : '.$comments.'</p>
									</td>
							  </tr>						  
							  <tr>
									<td align="left" valign="top" height="90" style="background-color:#1baea6;">
										<table width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td align="left" valign="top" style="float: left;text-align:center;width: 100%;padding: 23px 0 0 0;margin: 0px auto;">
													<ul class="foot-link">
													<li style="display:inline-block;"><a style="color:#fff;" target="_blank" href="https://blog.Lorem.com">Blog</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/about-us.html">About</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/contact-us.html">Contact Us</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/privacy-policy.html">privacy policy </a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/terms-conditions.html">Terms &amp; Conditions</a> </li>													
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/help.html">Help</a> </li>
													</ul>
												</td>
											</tr>
											<tr>
												<td style="float: left;text-align:center;width: 100%;padding: 10px 0 0 0;margin: 0px auto;">
												<p style="color:#fff;" class="copy">Copyright © 2017 ads.lorem.com, LLC. All rights reserved.</p>
												</td>
											</tr>
										</table>
									</td>
							  </tr>
							  </table>
						</body>
						</html>
						';
		}
		$adownermasg = '
						<html>
						<head>
						  <title>Report This Ads against Ad Id'.$addid.'</title> 
						</head>
						<body>
							<table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #1baea6;">
							  <tr>
									<td align="left" valign="top" height="50" style="background-color:#1baea6;">
									<table width="100%" cellspacing="2" cellpadding="2">
										<tr>
											<td width="45%" align="left"><img src="'.$base_url.'/images/logo.png"></td>
											<td width="45%" align="right">
												<img src="'.$base_url.'/images/march-adsimg.png"></h1>
											</td>
										</tr>
									</table>
									</td>
							  </tr>
							  <tr style="">
									<td align="left" valign="top" style="padding-left:10px;">
										 <p>Dear '.$Ad_ownername.',<br/><br/> A user has Submit a report to this Ad against Ad Id '.$addid.' On Lorem ClassifiedAds. Please Check the details given below.</p>
										  <p> Email Id : '.$email.'</p>	
										  <p> Problem Type : '.$problemtype.'</p>	
										  <p> Like a response : '.$sendresponse.'</p>	
										  <p> Comments  : '.$comments.'</p>		
									</td>
							  </tr>						  
							  <tr>
									<td align="left" valign="top" height="90" style="background-color:#1baea6;">
										<table width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td align="left" valign="top" style="float: left;text-align:center;width: 100%;padding: 23px 0 0 0;margin: 0px auto;">
													<ul class="foot-link">
													<li style="display:inline-block;"><a style="color:#fff;" target="_blank" href="https://blog.Lorem.com">Blog</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/about-us.html">About</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/contact-us.html">Contact Us</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/privacy-policy.html">privacy policy </a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/terms-conditions.html">Terms &amp; Conditions</a> </li>													
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/help.html">Help</a> </li>
													</ul>
												</td>
											</tr>
											<tr>
												<td style="float: left;text-align:center;width: 100%;padding: 10px 0 0 0;margin: 0px auto;">
												<p style="color:#fff;" class="copy">Copyright © 2017 ads.lorem.com, LLC. All rights reserved.</p>
												</td>
											</tr>
										</table>
									</td>
							  </tr>
							  </table>
						</body>
						</html>
						';
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		//$headers .= 'To: '.$AdminUserdetails['firstname'].' <'.$_POST['email'].'>' . "\r\n";
		$headers .= 'From: lorem Team <upworksanjeev@gmail.com>' . "\r\n";
		// Mail it
		if(mail($to, $subject, $message, $headers)){
			mail($cc, $subject, $adownermasg, $headers);
			$Ads = Ads::model()->findbyPk($AdId);
			$Ads->ad_flaged	= '1';
			if($Ads->update()):
				echo '<label>Thank you. We will review the ad and take appropriate action. We appreciate your help to improve Lorem Classifieds.</label>';
			else:
				echo 'Ads Not Flaged Try Later!';
			endif;			
		}else{
			echo 'Error Occured during sending mail! Contact to Admin!';
		}
						
	}
	/* Respond Ads Code Start Here */
	public function actionRespondAds(){
		$base_url = Yii::app()->getBaseUrl(true);
		$name	   =	$_POST['name'];
		$email	   =	$_POST['email'];
		$message   =	strip_tags($_POST['message'],"<br/><a>");
		$ownerid   =	$_POST['ownerid'];
		$clginuser =	$_POST['clginuser'];
		$Adsid	   =	$_POST['adsid'];
		$AdsUniqeId=	'#adx'.$_POST['adsid'];
		
		$Newmessage = new Messages;
		$Newmessage->user_name=$name;
		$Newmessage->user_email=$email;
		$Newmessage->user_id=$clginuser;
		$Newmessage->ads_id=$Adsid;
		$Newmessage->owner_id=$ownerid;
		$Newmessage->message_text=$_POST['message'];
		$Newmessage->message_date=new CDbExpression('NOW()');
		$Newmessage->save();

		if(isset(Yii::app()->session['userid'])){
			$userid = Yii::app()->session['userid'];
			$CurrentUserdetails = Yii::app()->db->createCommand()
				->select('firstname,lastname')
				->from('cads_UsersMeta CUM')
				->where('CUM.user_id=:Uid',array(':Uid'=>$userid))
				->queryRow();
		}
		
		$Adsownerdetails = Yii::app()->db->createCommand()
		->select('u.login_email,um.firstname,um.lastname')
		->from('cads_Users u')
		->leftJoin('cads_UsersMeta um', 'u.id=um.user_id')
		->where('u.id=:aid',array(':aid'=>$ownerid))
		->queryRow();
		
		$to  = $Adsownerdetails['login_email'];
		$subject = 'Respond This Ads Form against Ad Id'.$AdsUniqeId.'On lorem ClassifiedAds';		
			
		/*$message = '
			<html>
			<head>
			  <title>Respond This Ads Form against Ad Id'.$AdsUniqeId.'</title>
			</head>
			<body>
			  <p>Dear '.$Adsownerdetails['firstname'].',<br/><br/> A user has Submit Respond to this Ad against Ad-Id '.$AdsUniqeId.' On lorem ClassifiedAds. Please Check the details given below..</p>
			  <p> Name		                 :  '.$name.'</p>
			  <p> Email Id                   :  '.$email.'</p>
			  <p> Comments                   :  '.$message.'</p>
			  <p> Thanks & Regards,<br/>lorem Ads Team.</p>			  
			</body>
			</html>
		';*/
		
		$message = '<html>
						<head>
						  <title>Respond This Ads Form against Ad Id'.$AdsUniqeId.'</title> 
						</head>
						<body>
							<table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #1baea6;">
							  <tr>
									<td align="left" valign="top" height="50" style="background-color:#1baea6;">
									<table width="100%" cellspacing="2" cellpadding="2">
										<tr>
											<td width="45%" align="left"><img src="'.$base_url.'/images/logo.png"></td>
											<td width="45%" align="right">
												<img src="'.$base_url.'/images/march-adsimg.png"></h1>
											</td>
										</tr>
									</table>
									</td>
							  </tr>
							  <tr style="">
									<td align="left" valign="top" style="padding-left:10px;">
										 <p>Dear '.$Adsownerdetails['firstname'].',<br/><br/> A user has Submit Respond to this Ad against Ad-Id '.$AdsUniqeId.' On Lorem ClassifiedAds. Please Check the details given below..</p>
									  <p> Name		                 :  '.$name.'</p>
									  <p> Email Id                   :  '.$email.'</p>
									  <p> Comments                   :  '.$message.'</p>
									  <p> Thanks & Regards,<br/>Lorem Ads Team.</p>	
									</td>
							  </tr>						  
							  <tr>
									<td align="left" valign="top" height="90" style="background-color:#1baea6;">
										<table width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td align="left" valign="top" style="float: left;text-align:center;width: 100%;padding: 23px 0 0 0;margin: 0px auto;">
													<ul class="foot-link">
													<li style="display:inline-block;"><a style="color:#fff;" target="_blank" href="https://blog.Lorem.com">Blog</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/about-us.html">About</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/contact-us.html">Contact Us</a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/privacy-policy.html">privacy policy </a> </li>
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/terms-conditions.html">Terms &amp; Conditions</a> </li>													
													<li style="display:inline-block;"><a style="color:#fff;" href="'.$base_url.'/help.html">Help</a> </li>
													</ul>
												</td>
											</tr>
											<tr>
												<td style="float: left;text-align:center;width: 100%;padding: 10px 0 0 0;margin: 0px auto;">
												<p style="color:#fff;" class="copy">Copyright © 2017 ads.lorem.com, LLC. All rights reserved.</p>
												</td>
											</tr>
										</table>
									</td>
							  </tr>
							  </table>
						</body>
						</html>
						';	
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Additional headers
		$headers .= 'To: '.$Adsownerdetails['firstname'].' <'.$Adsownerdetails['login_email'].'>' . "\r\n";		
		$headers .= 'From: '.$name.' <'.$email.'>' . "\r\n";
		// Mail it
		if(mail($to, $subject, $message, $headers)){
			echo '<label>Your message has been sent. Thank you!</label>';
			
		}else{
			echo 'Error Occured during sending mail! Contact to Admin!';
		}
		/* Respond Ads Code Start Here */				
	}
	
	public function actionContactus()
	{		
			$this->renderPartial('//layouts/header');
			$this->render('contactus');
			$this->renderPartial('//layouts/footer');
	}
	
	
	public function actionPages()
	{
		$segments = Yii::app()->uri->segment_array();
		$page = str_replace(".html","",$segments[0]);
		$pagedta = array();
		$pagedat = array();
		if($page):
			$pagedat = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_pages')
						->where(array('like','page_name',"$page"))
						->queryRow();
						
			$PageList = Yii::app()->commonFnc->PageList();
			
			//$this->pageTitle = $pagedat['page_title'];

			//Yii::app()->clientScript->registerMetaTag('foo, bar', 'keywords');
		
			if(!empty($pagedat)):
			 $pg_id= $pagedat['id'];
			//die;
				$pagedta = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_pages')
					->where(array('like','p_id',"$pg_id"))
					->queryRow();
			endif;
			 
			if(empty($pagedta)):
				$pagedata = $pagedat;
			else:
				$pagedata = array_merge($pagedat,$pagedta);
			endif;
		
			$this->renderPartial('//layouts/header');
			$this->render('pagedata',array('pagedata'=>$pagedata,'PageList'=>$PageList));
			$this->renderPartial('//layouts/footer');
		endif;
	}
	public function actionForgot()
	{		
		
		$this->renderPartial('//layouts/header');
		$this->render('forgot');
		$this->renderPartial('//layouts/footer');
	}
	public function actionGetUserdetails()
	{				
		if(isset($_REQUEST["frgtemail"]) && !empty($_REQUEST["frgtemail"])){
			$emailId = $_REQUEST["frgtemail"];
			$chckemail = Yii::app()->db->createCommand()
				->select('U.id,U.login_email,UM.firstname')
				->from('cads_Users U')
				->leftJoin('cads_UsersMeta UM', 'U.id=UM.user_id')
				->where('login_email=:email',array(':email'=>$emailId))
				->andwhere('login_email=:email',array(':email'=>$emailId))
				->queryRow();
			if(isset($chckemail) && !empty($chckemail)){				
				$length = 8;
				$chars = array_merge(range('a','z'), range(0,9), range('A','Z'));
				shuffle($chars);
				$password = implode(array_slice($chars, 0, $length));
				$uId = $chckemail['id'];
				$UpdatedUser = Users::model()->findByPk($uId);
				$UpdatedUser->password = md5($password);
				//print_r($UpdatedUser); die;
				/* Mail Code start Here */
				$to  = $emailId;
				$subject = 'Recover Password';
				$message = '
				<html>
				<head>
				<title>Money Media Solution</title>
				</head>
				<body>
				<p>Dear '.$chckemail['firstname'].',<br/><br/> We have recieved a request to reset your password. Your Password is given below: </p>
				<p> Email Id                   :  '.$to.'</p>
				<p> Password                   :  '.$password.'</p>
				<p>Thanks & regards,<br/>Money Media Solution Team</p>
				</body>
				</html>
				';

				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				// Additional headers
				$headers .= 'To: '.$chckemail['firstname'].' <'.$chckemail['login_email'].'>' . "\r\n";		
				$headers .= 'From: Money Media Solution <info@moneymedia.com>' . "\r\n";
				// Mail it
				if(mail($to, $subject, $message, $headers)){
					$UpdatedUser->save();
					echo 'Your Password has been sent on your Email address. Please Check your Email';
				}else{
					echo 'Error Occured during sending mail! Contact to Admin!';
				}
				
				/* Mail Code start Here */
			} else {
				echo "Your Email id doesn't exist.";
			}			
		} else {
			echo "You didn't put your email ID!";
		}
			
	}
	public function actionGetNewChosenCategory(){
		$base_url = Yii::app()->getBaseUrl(true);
		$searchinput = $_REQUEST['searchinput'];
		$categorydata = Yii::app()->db->createCommand()
					->select('category.*')
					->from('cads_Categories category')
					->where(array('like', 'category.category_name', "%$searchinput%"))
					->andWhere('category.category_status = :status', array(':status' => 1))
					->queryAll();
		
		$featuredata = Yii::app()->db->createCommand()
					->select('Features.*')
					->from('cads_Subcategories Features')
					->where(array('like', 'Features.subcategory_name', "%$searchinput%"))
					->andWhere('Features.subcategory_status = :status', array(':status' => 1))
					->queryAll();
		$array3 = array_merge($categorydata, $featuredata);			
		/* echo '<pre>';
		print_r($array3); 
		die ; */
		if(count($array3) > 0 ):
			$output = '<ul>';
				if(count($array3) > 0):
					foreach($array3 as $val){
						$cat_id = $val['id'];
						if(isset($val['category_name']) && !empty($val['category_name'])){
							$cat_name = $val['category_name'];
							$output.= '<li id="cat_'.$cat_id.'" data-categorytype="category" onclick="selectnewcategory(this);">'.$cat_name.'</li>';		
						}
						if(isset($val['subcategory_name']) && !empty($val['subcategory_name']) ){
							$cat_name = $val['subcategory_name'];
							$output.= '<li id="cat_'.$cat_id.'" data-categorytype="subcategory" onclick="selectnewcategory(this);">'.$cat_name.'</li>';
						}
						
					} 	
				endif;	
			$output .= '</ul>';
		else:
			$output = '<div>No Result Found!</div>';
		endif;	
		
		echo $output;
	}
	public function actionGetNewChosenCity()
	{
		$base_url = Yii::app()->getBaseUrl(true);
		$searchinput = $_REQUEST['searchinput'];
		$citydata = Yii::app()->db->createCommand()
					->select('city.*,city.id  cityid,state.*,country.*')
					->from('cads_City city')
					->leftJoin('cads_CityState cityState', 'cityState.cityid=city.id')
					->leftJoin('cads_State state', 'state.id=cityState.stateid')
					->leftJoin('cads_CountryState stateCountry', 'state.id=stateCountry.stateid')
					->leftJoin('cads_Country country', 'country.id=stateCountry.countryid')
					->where(array('like', 'city.cityname', "%$searchinput%"))
					->limit(25)
					->queryAll();
		if(count($citydata) > 0):			
				$output = '<ul>';
				foreach($citydata as $val){
					$city = $val['cityname'];
					$state = $val['statename'];
					$country = $val['countryname'];
					$cityid = $val['cityid'];
					$statenamearray =explode(" ",$state);
					$statenamestring = implode("_",$statenamearray);
					$countrynamearray =explode(" ",$country);
					$countrynamestring = implode("_",$countrynamearray);
					$output.= '<li id="'.$cityid.'" onclick="selectNewcity(this);">'.$city.', '.$state.', '.$country.'</li>
					<input type="hidden" id="ct'.$cityid.'" value="'.$cityid.'" />';
				} 
				$output .= '</ul>';
		else:
				$output = '<div>No Result Found!</div>';
		endif;
		echo $output;
	}
	public function actionChangePassword()
	{		
		if(isset(Yii::app()->session['userid']))
		{
			if(isset($_POST['Changepassbut'])){
				$cuserId = Yii::app()->session['userid'];
				$getUsers = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_Users U')
					->leftJoin('cads_UsersMeta UM','U.id=UM.user_id')
					->where(array('like', 'U.id', "%$cuserId%"))
					->queryRow();
				$Uoldpass = md5(@$_POST['oldpassword']);
				$newpassword = @$_POST['newpassword'];
				if(empty($newpassword) || empty($_POST['oldpassword'])){
					Yii::app()->user->setFlash('error','Please Enter Old And New Password Here!');
				}elseif($newpassword == $_POST['oldpassword']) {
					Yii::app()->user->setFlash('error','Old and New Password should not be same!');
				} elseif($Uoldpass <> $getUsers['password']) {
					Yii::app()->user->setFlash('error','You entered wrong Old Password!');
				} else {
					$UpdatedUser = Users::model()->findByPk($cuserId);
					$UpdatedUser->password = md5($newpassword);
					
					/* Send mail after Change Password Code start Here */
					$to  = $getUsers['login_email'];
					$subject = 'You have changed Password On lorem ClassifiedAds';
					$message = '
					<html>
					<head>
					<title>You have changed Password On lorem ClassifiedAds</title>
					</head>
					<body>
					<p>Dear '.$getUsers['firstname'].',<br/><br/> You have changed your Password On lorem ClassifiedAds. Your Credentials is given below: </p>
					<p> Email Id                   :  '.$getUsers["login_email"].'</p>
					<p> Password                   :  '.$newpassword.'</p>
					<p>Thanks & regards,<br/>lorem Classified Ads Team</p>
					</body>
					</html>
					';

					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

					// Additional headers
					$headers .= 'To: '.$getUsers['firstname'].' <'.$getUsers['login_email'].'>' . "\r\n";		
					$headers .= 'From: lorem Classified Ads <info@ads.lorem.com>' . "\r\n";
					// Mail it
					if(mail($to, $subject, $message, $headers)){
						$UpdatedUser->save();
						Yii::app()->user->setFlash('error','Your Password has been changed Please Check your mail and login with new credentials!');
					}else{
						Yii::app()->user->setFlash('error','Your Password has been changed but Problem in sending Mail!');
					}
				}
				
			}
			$this->renderPartial('//layouts/header');
			$this->render('ChnagePassword');
			$this->renderPartial('//layouts/footer');	
		} else {
			$this->redirect(array('Site/Signup'));
		}
	}
}

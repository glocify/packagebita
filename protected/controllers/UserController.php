<?php
class UserController extends CController
{
	public function actionLogin($pageid = null)
	{
		if(!isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] != 1)
		{	
			$model=new LoginForm('Back');
			if(isset($_POST['LoginForm']))
			{
				$model->attributes=$_POST['LoginForm'];

				if($model->validate() && $model->login())
				{
					Yii::app()->user->setFlash('login','Successfully logged in');
					$this->redirect(array('User/dashboard'));
				}
				else
				{
					Yii::app()->user->setFlash('error','Invalid username or password');
				}
			}
			$this->render('login',array('model'=>$model));
		}
		else
		{
			//echo "hello"; die;
			$this->renderPartial('//layouts/admin/header');
		
		$this->render('insufficientPermission');
		$this->renderPartial('//layouts/admin/footer');
		//$this->redirect(array('User/login'));
		}
	}
	
	public function actionUsersearch($searchinput)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$getUsers = Yii::app()->db->createCommand()
							->select('users.id userid, users.login_email user_email, userMeta.firstname firstname, userMeta.lastname, userMeta.zip zipcode')
							->from('cads_Users users')
							->leftJoin('cads_UsersMeta userMeta','users.id=userMeta.user_id')
							->where(array('like', 'users.login_email', "%$searchinput%"))
							->orWhere(array('like', 'userMeta.firstname', "%$searchinput%"))
							->orWhere(array('like', 'userMeta.lastname', "%$searchinput%"))
							->orWhere(array('like', 'userMeta.zip', "%$searchinput%"))
							->queryAll();
			if(count($getUsers) > 0){
				echo '<ul>';
				foreach($getUsers as $getUsers){
					echo '<li id="'.$getUsers['userid'].'" onclick="selectuser(this);">'.$getUsers['firstname'].' '.$getUsers['lastname'].', '.$getUsers['zipcode'].', '.$getUsers['user_email'].'</li>';
					}
				echo '</ul>';
			}else{
				echo 'No Result Found!';
			}
			
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('Site/Signin')); 
	}
	
	
	public function actionSearchuser($search_input)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
				
			$base_url = Yii::app()->getBaseUrl(true);
			$getUsers = Yii::app()->db->createCommand()
							->select('users.id userid, users.login_email user_email, userMeta.firstname firstname, userMeta.lastname, userMeta.zip zipcode')
							->from('cads_Users users')
							->leftJoin('cads_UsersMeta userMeta','users.id=userMeta.user_id')
							->where(array('like', 'users.id', "$search_input"))
							->queryAll();	
			/* print_r($getUsers);	
			die ; */
			echo '<thead>
						  <tr>
							 <th>First Name</th>
							 <th>Last Name</th>
							 <th>Email</th>
							 <th>Zip Code</th>
							 <th>Action</th>
						  </tr>
					   </thead>';
			
			foreach($getUsers as $getUsers) {
				$userid = $getUsers['userid'];
				$confirmmessage = "'Are you sure about this?'";

				echo '<tr class="odd gradeX">
								<td style="display:none;" class="hidden-phone">914</td>
								
								<td class="hidden-phone">'.$getUsers['firstname'].'</td>
								<td class="hidden-phone">'.$getUsers['lastname'].'</td>
								<td class="hidden-phone">'.$getUsers['user_email'].'</td>
								<td class="hidden-phone">'.$getUsers['zipcode'].'</td>
								<td style="width:15%" class="tableActs"> 
								<a href="'.$base_url.'/User/updateuser/'.$userid.'" class="btn mini green-stripe">Update</a> 
								<a onclick="return confirm('.$confirmmessage.');" href="'.$base_url.'/User/deleteuser/'.$userid.'" class="btn mini red-stripe">Delete</a>
								</td>
							  </tr>';	
			}
			echo '</tbody>';
		}
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
	
	public function actionMyAccount()
	{
		if(Yii::app()->session['userid'] !=''): 
			$PageList = Yii::app()->commonFnc->PageList();
			$userinfo = array();
			$user_id = Yii::app()->session['userid'];
		
			$user_id;
			
				$userdata = Yii::app()->db->createCommand()
							->select('UserMain.id id,UserMain.*,UM.firstname,UM.lastname,UM.address,UM.address,UM.speciality,UM.position,UM.url,UM.Hour_rate,UM.offer,UM.zip,UM.user_contact,UM.user_registered,UM.recommendation,UM.user_image,UM.fbid,UM.company')
							->from('cads_Users UserMain')
							->leftJoin('cads_UsersMeta UM','UserMain.id=UM.user_id')
							->where('UserMain.id =:id', array(':id' => $user_id))
							->queryRow();
				/*echo '<pre>';			
				print_r($userdata);*/			
				
				
				
				
				$orders	=	Yii::app()->db->createCommand()
						->select('*')
						->from('cads_Orders')
						->where('userid =:uid', array(':uid' => $user_id))
						->queryAll();		
										
				$criteria = new CDbCriteria();
				$criteria->condition = "status =:status";
				$criteria->params = array(':status' => 1);
				$criteria->order = 'id ASC';
				$packages = Packages::model()->findAll($criteria);	
				
				$this->renderPartial('//layouts/header');
				$this->render('myaccount',array('Packages'=>$packages, 'userdata'=>$userdata,'orders'=>$orders,'PageList'=>$PageList));
				$this->renderPartial('//layouts/footer');
			else:
				$this->redirect(array('/Site/Signin'));
			endif;
	}
	
	public function actionBuyPackage(){
		if(isset($_POST['packageID']) && $_POST['packageID'] != ""){
			$pid = trim($_POST['packageID']);
			$criteria = new CDbCriteria();
			$criteria->condition = "status =:status AND en_key =:en_key";
			$criteria->params = array(':status' => 1,':en_key'=>$pid);			
			$packages = Packages::model()->findAll($criteria);			
			$base_url = Yii::app()->getBaseUrl(true);
			$paypalUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			if(count($packages) > 0){					
				$fields = array(
						'business' => 'onkar.blissit-facilitator@gmail.com',
						'cmd' => '_xclick',
						'rm' => 2,
						'item_name' => $packages[0]->name,
						'amount' => $packages[0]->price,
						'currency_code' => 'USD',
						'item_number' => $packages[0]->id,
						'return' => $base_url.'/site/thankyou',
						'notify_url' => $base_url.'/site/thankyou',
						'cancel_return' => $base_url.'/site/thankyou'						
					);	
				echo "<html>\n";
				echo "<head><title>Processing Payment...</title></head>\n";
				echo "<body onLoad=\"document.forms['paypal_form'].submit();\">\n";
				echo "<div style=\"border:1px solid #ddd;padding:5px;width: 700px;margin:0 auto;border-radius: 5px;\"><center><h2>Please wait, your transaction is being processed and you";
				echo " will be redirected to the paypal website.</h2></center>\n";
				echo "<form method=\"post\" name=\"paypal_form\" action=\"" . $paypalUrl . "\">\n";
				foreach ($fields as $name => $value) {
					echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
				}
				echo "<center><br/><br/>If you are not automatically redirected to ";
				echo "paypal within 5 seconds...<br/><br/>\n";
				//echo "<input type=\"submit\" value=\"Click Here\"></center>\n";			
				echo "</center>\n";			
				echo "</form></div>\n";
				echo "</body></html>\n";
				
			}else{
				$this->actionMyAccount();
			}	
		}else{
			$this->actionMyAccount();
		}		
	}	
	public function actionShowMessage(){
		if(Yii::app()->session['userid'] !=''):
			$currenturl = Yii::app()->getBaseUrl(true);
			
			$showMessages = Yii::app()->db->createCommand()
						->select('m.*')
						->from('cads_Messages m')
						->where('m.id =:msgid', array(':msgid' => $_POST['msgid']))
						->queryRow();
			if(isset($showMessages['user_name'])){
				$username = $showMessages['user_name'];
			}
			$msgdate = new DateTime($showMessages["message_date"]);?>
			<div class="msg-headers">
				From: <?php if(isset($showMessages['user_name'])){
				echo $showMessages['user_name'];
			};?> (<?php echo $showMessages['user_email'];?>)<br>To: You<br>Date: <?php echo $msgdate->format('M j,Y');?><br>Time: <?php echo $msgdate->format('H:i a');?>
			</div>
			<?php echo $showMessages['message_text'];?><br><br>
			<div style="text-align:center;">
				<a class="flatter" href="mailto:<?php echo $showMessages['user_email'];?>?subject=<?php echo $currenturl;?>%20Ad-<?php echo "adx".$showMessages['ads_id'];?>">Reply by email</a>
				<a class="delete" style="cursor:pointer;" onclick="Deletemessage('<?php echo $showMessages['id'];?>');">Delete</a>
				<div class="clearer"></div>
			</div>
<?php   else:
		$this->redirect(array('/Signin'));
		endif;
		
	}
	public function actionDeleteMsg($id = Null){
		
		if(Yii::app()->session['userid'] !=''):
			
			$msgid = $_POST['msgid']; 
			$deleteMessages = Messages::model()->findByPk($msgid);
			
			if($deleteMessages->delete() == true):
				echo "Done";
			else:
				echo "Message did not deleted!";
			endif;
		else:
			$this->redirect(array('/Signin'));
		endif;
	}
	
	/*  start Delete Ads From LoggedIn User's Acoount */
	public function actionDeleteAds($id = Null){
		if(isset(Yii::app()->session['userid'])):
			$user_id = Yii::app()->session['userid'];
			$Adsid = $_POST['dltadid'];
			$delete_adsimg = AdsImages::model()->findAll('ad_id=:adid', array(':adid'=>$id));
			$deleteAd = Ads::model()->findByPk($id);
			//echo "<pre>"; print_r($deleteimg); die;
			if(!empty($delete_adsimg)) {
				foreach($delete_adsimg as $img)
				{
					$imgname = $img['image'];
					$imgurl = dirname($_SERVER['SCRIPT_FILENAME']) . "/images/postAds/".$imgname;
					$imgurl1 = dirname($_SERVER['SCRIPT_FILENAME']) . "/images/postAds/thumb_".$imgname;
					
				if(file_exists($imgurl))
				{
					unlink($imgurl);
				}
				if(file_exists($imgurl1))
				{
					unlink($imgurl1);
				}
				}
			}
			if($deleteAd->delete() == true):
				AdsImages::model()->deleteAll('ad_id=:ad_id', array(':ad_id' => $id));
				AdSelected::model()->deleteAll('ad_id=:ad_id', array(':ad_id' => $id));
				Messages::model()->deleteAll('ads_id=:ad_id', array(':ad_id' => $id));
				//Yii::app()->user->setFlash('success', "Ad has been deleted Successfully.");
				echo "Ad has been deleted Successfully";
			else:
				echo "Sorry Ad cannot be deleted.";
				
			endif;
		else:
			$this->redirect(array('/Signin'));
		endif;
	}

	public function actionUserAjaxResults()
	{
		if($_POST['page'])
		{
			$page = $_POST['page'];
			$cur_page = $page;
			$page -= 1;
			$per_page = 20;
			$previous_btn = true;
			$next_btn = true;
			$first_btn = true;
			$last_btn = true;
			$start = $page * $per_page;
			
			$user_id = Yii::app()->session['userid'];
			$sql = "SELECT * FROM trpRestaurants where user_id=".$user_id." and published=1 LIMIT $start, $per_page";
			$Restaurants = Yii::app()->db->createCommand($sql)->queryAll();
			
			foreach($Restaurants as $res)
			{
				$rid = $res['id'];
				$arr[$rid] = Yii::app()->db->createCommand()
						 ->select('categories.*')
						 ->from('trpCategories categories')
						 ->leftJoin('trpSelectedCategoriesand selectcate','categories.id=selectcate.category_id')
						 ->where('business_id=:bid',array(':bid'=>$rid))
						 ->queryAll();
			}
			//echo "<pre>"; print_r($arr); die;
			$item_count = Yii::app()->db->createCommand()
						  ->select('count(id) cnt')
						  ->from('trpRestaurants')
						  ->where('user_id=:uid',array(':uid'=>$user_id))
						  ->andWhere('published=:published',array(':published'=>1))
						  ->queryRow();
			$msg = $Restaurants;
			$data['Restaurants'] = $msg;
			$rrt = $item_count;
			//print_r($itemcnt); die;
			$hotel['hotel_count']=$tot=$rrt['cnt'];
			$msg = "<div class='data'><ul></ul></div>";  // Content for Data
				$no_of_paginations = ceil($tot / $per_page);
				/* ---------------Calculating the starting and endign values for the loop----------------------------------- */
				if ($cur_page >= 7):
					$start_loop = $cur_page - 3;
					if ($no_of_paginations > $cur_page + 3):
						$end_loop = $cur_page + 3;
					elseif($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6):
						$start_loop = $no_of_paginations - 6;
						$end_loop = $no_of_paginations;
					else:
						$end_loop = $no_of_paginations;
					endif;
				else:
					$start_loop = 1;
					if ($no_of_paginations > 7)
						$end_loop = 7;
					else
						$end_loop = $no_of_paginations;
				endif;
			/* ----------------------------------------------------------------------------------------------------------- */
			if($tot<=20)
			{
				$msg .= "<div class='pagination-row'><ul class='pagination'>";
				$msg = $msg . "</ul></div>";   // Content for pagination
				echo $msg;
			}
			else
			{	
				$msg .= "<div class='pagination-row'><ul class='pagination'>";
			
				 // FOR ENABLING THE FIRST BUTTON
				if ($first_btn && $cur_page > 1):
					$msg .= "<li p='1' class='active'><a href='javascript:void(0)' onclick='userpagination(1);'>First</a></li>";
				elseif($first_btn > 1):
					$msg .= "<li p='1' class='inactive'><a href='javascript:void(0)' onclick='userpagination(1);'>First</a></li>";
				endif;
				
				 // FOR ENABLING THE PREVIOUS BUTTON
				if($previous_btn && $cur_page > 1):
					$pre = $cur_page - 1;
					$msg .= "<li p='$pre' class='active' ><a href='javascript:void(0)' onclick='userpagination(1);'>Previous</a></li>";
				elseif($previous_btn > 1):
					$msg .= "<li class='inactive'>Previous</li>";
				endif;
				
				for ($i = $start_loop; $i <= $end_loop; $i++):
					if ($cur_page == $i)
						$msg .= "<li p='$i' id='set$i'  style='color:#fff;background-color:#006699;' class='active'><a href='javascript:void(0)' onclick='userpagination(1);'>{$i}</a></li>";
					else
						$msg .= "<li p='$i' id='set$i' onclick='RestfilterPagination($i,999);' class='active'><a href='javascript:void(0)' onclick='userpagination(1);'>{$i}</a></li>";
				endfor;
				 // TO ENABLE THE NEXT BUTTON
				if($next_btn && $cur_page < $no_of_paginations): 
					$nex = $cur_page + 1;
					$msg .= "<li p='$nex' class='active'><a href='javascript:void(0)' onclick='userpagination(1);'>Next</a></li>";
				elseif($next_btn  < $no_of_paginations):
					$msg .= "<li class='inactive'>Next</li>";
				endif;
				
				// TO ENABLE THE END BUTTON
				if ($last_btn && $cur_page < $no_of_paginations):
					$msg .= "<li p='$no_of_paginations' class='active'><a href='javascript:void(0)' onclick='userpagination(1);'>Last</a></li>";
				elseif($last_btn < $no_of_paginations): 
					$msg .= "<li p='$no_of_paginations' class='inactive'><a href='javascript:void(0)' onclick='userpagination(1);'>Last</a></li>";
				endif;
					$msg = $msg . "</ul></div>";   // Content for pagination
					echo $msg;
			}
			$this->renderPartial('userAjaxResults',array('data'=>$data,'arr'=>$arr));
		
		}
	}
	
	public function actionDashboard()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$userid = Yii::app()->session['userid'];
			$userData = Yii::app()->db->createCommand()
					->select('users.*')
					->from('trpUsers users')
					->leftJoin('trpUsersMeta UsersMeta', 'users.id=UsersMeta.user_id')
					->where('users.id =:state', array(':state' => $userid))
					->queryRow();
				Yii::app()->session['user_email'] = $userData['login_email'];
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('dashboard', array('userData' => $userData));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}

	public function actionUpdateProfile()
	{
		if(isset(Yii::app()->session['userid'])){
			$loginId = Yii::app()->session['userid'];
			$usermeta = $userdata = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_Users U')
						->leftJoin('cads_UsersMeta UM','U.id=UM.user_id')
						->where('U.id =:id', array(':id' => $loginId))
						->queryRow();
			$data['userdata'] = $userdata;
			if(isset($_FILES['pic']['name']) && ($_FILES['pic']['name']!=''))
			{
				$base_url = Yii::app()->getBaseUrl(true);
				$extensions = array("jpeg","jpg","png");
				$dir = $base_url."/images/userimages/";
				$tmp_name = $_FILES['pic']['tmp_name'];
				$file_name = $_FILES['pic']['name'];
				$file_size = $_FILES['pic']['size'];
				$file_tmp = $_FILES['pic']['tmp_name'];
				$file_type=$_FILES['pic']['type'];
				$fileext = explode('.',$file_name);
				$count = count($fileext);
				$count1 = $count-1;
				$fileext1=$fileext[$count1];  
				$file_ext=strtolower($fileext1);
				if(in_array($file_ext,$extensions ) === false){
					$errors[]="extension not allowed";
				}else
				{
					
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
					 $thumbnail = $thumb_path."thumb_".$_FILES['pic']['name'];
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
						$file_name = $this->cwUpload('pic','images/userimages/','',TRUE,'images/userimages/','175','175');
						//move_uploaded_file($file_tmp,"images/userimages/".$file_name);
						
					}
					$updatemetaa['user_image'] = $file_name;
					$updatedata = Yii::app()->db->createCommand()
							->update('cads_UsersMeta', 
								$updatemetaa,
								'user_id=:user_id',
								array(':user_id'=>$loginId)
							);  
				}				
			}else{
				if(isset($_POST['first_name']))
				{
					$updatemeta['firstname'] = $_POST['first_name'];
				}
				if(isset($_POST['last_name']))
				{
					$updatemeta['lastname'] = $_POST['last_name'];
				}
				if(isset($_POST['email']))
				{
					$updates['login_email'] = $_POST['email'];
				}
				if(isset($_POST['address']))
				{
					$updatemeta['address'] = $_POST['address'];
				}
				if(isset($_POST['phone']))
				{
					$updatemeta['user_contact'] = $_POST['phone'];
				}
				if(isset($_POST['offer']))
				{
					$updatemeta['offer'] = $_POST['offer'];
				}
				if(isset($_POST['url'])){
					$updatemeta['url'] = $_POST['url'];
				}
				if(isset($_POST['Hour_rate'])){
					$updatemeta['Hour_rate'] = $_POST['Hour_rate'];
				}
				if(isset($_POST['speciality'])){
					$updatemeta['speciality'] = $_POST['speciality'];
				}
				if(isset($_POST['position'])){
					$updatemeta['position'] = $_POST['position'];
				}
				if(isset($_POST['company'])){
					$updatemeta['company'] = $_POST['company'];
				}
				if(isset($_POST['company'])){
					$updatemeta['company'] = $_POST['company'];
				}
				
				$updatedata = Yii::app()->db->createCommand()
							->update('cads_UsersMeta', 
								$updatemeta,
								'user_id=:user_id',
								array(':user_id'=>$loginId)
							);
				$update1 = Yii::app()->db->createCommand()
							->update('cads_Users', 
								$updates,
								'id=:id',
								array(':id'=>$loginId)
							);
			}
		}
		$url = Yii::app()->createUrl("/User/MyAccount");
		$this->redirect($url);			 
	}
	
	function cwUpload($field_name = '', $target_folder = '', $file_name = '', $thumb = FALSE, $thumb_folder = '', 		$thumb_width = '', $thumb_height = ''){
		//folder path setup
		$target_path = $target_folder;
		$thumb_path = $thumb_folder;
		
		//file name setup
		$filename_err = explode(".",$_FILES[$field_name]['name']);
		$filename_err_count = count($filename_err);
		$file_ext = $filename_err[$filename_err_count-1];
		if($file_name != ''){
			$fileName = $file_name.'_'.time().'.'.$file_ext;
			$fileThumbName = $file_name.'_'.time().'_thumb.'.$file_ext;
		}else{
			$fileName = $_FILES[$field_name]['name'];
			$fname_arr = explode('.',$fileName);
			$fileName = $fname_arr[0].'_'.time().'_thumb.'.$fname_arr[1];
		}	
		//upload image path
		$upload_image = $target_path.basename($fileName);
		
		//upload image
		if(move_uploaded_file($_FILES[$field_name]['tmp_name'],$upload_image))
		{
			//thumbnail creation
			if($thumb == TRUE)
			{
				$thumbnail = $thumb_path.$fileName;				
				list($width,$height) = getimagesize($upload_image);
				$thumb_create = imagecreatetruecolor($thumb_width,$thumb_height);
				switch($file_ext){
					case 'jpg':
						$source = imagecreatefromjpeg($upload_image);
						break;
					case 'jpeg':
						$source = imagecreatefromjpeg($upload_image);
						break;
					case 'png':
						$source = imagecreatefrompng($upload_image);
						break;
					case 'gif':
						$source = imagecreatefromgif($upload_image);
						break;
					default:
						$source = imagecreatefromjpeg($upload_image);
				}
				imagecopyresized($thumb_create,$source,0,0,0,0,$thumb_width,$thumb_height,$width,$height);
				switch($file_ext){
					case 'jpg' || 'jpeg':
						imagejpeg($thumb_create,$thumbnail,100);
						break;
					case 'png':
						imagepng($thumb_create,$thumbnail,100);
						break;
					case 'gif':
						imagegif($thumb_create,$thumbnail,100);
						break;
					default:
						imagejpeg($thumb_create,$thumbnail,100);
				}
			}
			return $fileName;
		}else{
			return false;
		}
	}
	public function actionBusinessOwner()
	{
		if(isset(Yii::app()->session['userid']) )
		{
			if(isset($_POST['submit'])):
				$status = false;
					$base_url = Yii::app()->getBaseUrl(true);
					$dir = $base_url."/images/restaurants/";
						$user_id = Yii::app()->session['userid'];
						if(isset($_POST['star_rating']))
						{
							$rate = $_POST['star_rating'];
						}
						else{
							$rate=0;
						}
						$model = new Restaurants;
						$model->user_id = $user_id;
						$model->name=$_POST['rest_name'];
						$model->address=$_POST['rest_street'];
						$model->email=$_POST['r_email'];
						$model->city_id=$_POST['city_id'];
						$model->star_rating=$rate;
						$model->postalcode=$_POST['r_pcode'];
						$model->website=$_POST['rest_website'];
						//$model->description=$_POST['r_desc'];
						$model->published=0;
						
						if($model->save()):
							$res_id = $model->id;
							$status = true;
						else:
							$status = false;	
						endif;
						
						$modelD = new RestaurantsDetails;
						
						$modelD->restaurant_id=$res_id;
						$modelD->latitude=$_POST['r_latitude'];
						$modelD->longitude=$_POST['r_longitude'];
						$modelD->phone_number=$_POST['phone'];
							
						if($modelD->save()):
							$status = true;
						else:
							$status = false;
						endif;
						
						
						$cat_array = $_POST["multi_cat"] ;
					
						$countSet = 1;
						foreach($cat_array as $cat):	
						
							$enterCategory = new SelectedCategories;
							$enterCategory->business_id = $res_id;
							$enterCategory->category_id = $cat;	
							$enterCategory->save();
							$selected_id =  Yii::app()->db->getLastInsertID();
							$subcatName = 'multi_subcategory_'.$countSet;
					
							/* if(isset($_POST["$subcatName"])){
								foreach($_POST["$subcatName"] as $feature):
												$enterFeature = new SelectedFeatures;
												$enterFeature->business_id = $res_id;
												$enterFeature->selected_id = $selected_id;
												$enterFeature->feature_id = $feature;
												$enterFeature->feature_list_id = NULL;
												$enterFeature->save();
								endforeach;
							}	 */		
							if(isset($_POST["$subcatName"])){
								foreach($_POST["$subcatName"] as $feature):
									$feature_list_name = 'multi_feature_'.$cat.'_'.$feature;
									if(isset($_POST["$feature_list_name"])):
										foreach($_POST["$feature_list_name"] as $featurelist):
												$enterFeature = new SelectedFeatures;
												$enterFeature->business_id = $res_id;
												$enterFeature->selected_id = $selected_id;
												$enterFeature->feature_id = $feature;
												$enterFeature->feature_list_id = $featurelist;
												$enterFeature->save();
										endforeach;
									else:
												$enterFeature = new SelectedFeatures;
												$enterFeature->business_id = $res_id;
												$enterFeature->selected_id = $selected_id;
												$enterFeature->feature_id = $feature;
												$enterFeature->feature_list_id = NULL;
												$enterFeature->save();
									endif;
								endforeach;
							}
							$countSet++;	
							
							
							/* $featcher  	= Yii::app()->db->createCommand()
										->select('Features.*')
										->from('trpFeatures Features')
										->where('Features.status =:status', array(":status" => 1))
										->queryAll();
							foreach($featcher as $feature):
									$feature_list_name = 'multi_feature_'.$cat.'_'.$feature["id"];
									if(isset($_POST["$feature_list_name"])):
										foreach($_POST["$feature_list_name"] as $featurelist):
												$enterFeature = new SelectedFeatures;
												$enterFeature->business_id = $res_id;
												$enterFeature->selected_id = $selected_id;
												$enterFeature->feature_id = $feature["id"];
												$enterFeature->feature_list_id = $featurelist;
												$enterFeature->save();
										endforeach;
									endif;
							endforeach; */
						endforeach;
						/* $extensions = array("jpeg","jpg","png");
						foreach($_FILES['file']['tmp_name'] as $key => $tmp_name )
						{
							
							$file_name = $key.$_FILES['file']['name'][$key];
							$file_size =$_FILES['file']['size'][$key];
							$file_tmp =$_FILES['file']['tmp_name'][$key];
							$file_type=$_FILES['file']['type'][$key];
							$fileext=explode('.',$file_name)	;
							$count = count($fileext);
							$count1 = $count-1;
							$fileext1=$fileext[$count1];  
							$file_ext=strtolower($fileext1);  
							if(in_array($file_ext,$extensions ) === false){
								$errors[]="extension not allowed";
							}    
							else
							{
									
								$modelIm=new RestaurantsImages;
								$modelIm->restaurant_id=$res_id;
								$modelIm->url=$file_name;
								
								$modelIm->save();
								
								$thumbnail_width = 90;
								$thumbnail_height = 70;
								$thumb_beforeword = "thumb";
								$thumb_path = "images/restaurantsthumb/";
								
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
										 $thumbnail = $thumb_path."thumb_".$key.$_FILES['file']['name'][$key];
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
								move_uploaded_file($file_tmp,"images/restaurants/".$file_name);
							}
						}	 */
					
						/* if(isset($_POST["restauranttype"])):
							$mont = serialize($_POST['montxt']);
							$mons = serialize($_POST['s_mon']);
							$mone = serialize($_POST['e_mon']);
							$mon = $mont."+".$mons."+".$mone;
							$tuet = serialize($_POST['tuetxt']);
							$tues = serialize($_POST['s_tue']);
							$tuee = serialize($_POST['e_tue']);
							$tue = $tuet."+".$tues."+".$tuee;
							$wedt = serialize($_POST['wedtxt']);
							$weds = serialize($_POST['s_wed']);
							$wede = serialize($_POST['e_wed']);
							$wed = $wedt."+".$weds."+".$wede;
							$thut = serialize($_POST['thutxt']);
							$thus = serialize($_POST['s_thu']);
							$thue = serialize($_POST['e_thu']);
							$thu = $thut."+".$thus."+".$thue;
							$frit = serialize($_POST['fritxt']);
							$fris = serialize($_POST['s_fri']);
							$frie = serialize($_POST['e_fri']);
							$fri = $frit."+".$fris."+".$frie;
							$satt = serialize($_POST['sattxt']);
							$sats = serialize($_POST['s_sat']);
							$sate = serialize($_POST['e_sat']);
							$sat = $satt."+".$sats."+".$sate;
							$sunt = serialize($_POST['suntxt']);
							$suns = serialize($_POST['s_sun']);
							$sune = serialize($_POST['e_sun']);
							$sun = $sunt."+".$suns."+".$sune;
							$modelI = new RestaurantsInformations;
							if(isset($_POST['price_len1']))
							{
								$modelI->pricerange1=$_POST['price_len1'];
							}
							if(isset($_POST['price_len2']))
							{
								$modelI->pricerange2=$_POST['price_len2'];
							}
							if(isset($_POST['price_len3']))
							{
								$modelI->pricerange3=$_POST['price_len3'];
							}
							if(isset($_POST['price_len4']))
							{
								$modelI->pricerange4=$_POST['price_len4'];
							}
							if(isset($_POST['price_len5']))
							{
								$modelI->pricerange5=$_POST['price_len5'];
							}
							$modelI->restaurant_id=$res_id;
							$modelI->r_range=$_POST['r_range'];
							$modelI->r_accept_cards=$_POST['r_accept_cards'];
							$modelI->r_payment_policies=$_POST['r_payment'];
							$modelI->r_facilities=$_POST['r_facilities'];
							$modelI->r_cross_street=$_POST['r_cross_street'];
							$modelI->r_dining_style=$_POST['r_dining_style'];
							$modelI->r_parking=$_POST['r_parking'];
							$modelI->time_mon=$mon;
							$modelI->time_tue=$tue;
							$modelI->time_wed=$wed;
							$modelI->time_thu=$thu;
							$modelI->time_fri=$fri;
							$modelI->time_sat=$sat;
							$modelI->time_sun=$sun;
							if($modelI->save()):
								$status = true;
							else:
								$status = false;
							endif;
						endif;
						
						if(isset($_POST["hotelType"])):
							$modelII=new HotelsInformations;
							$modelII->business_id=$res_id;
							$modelII->year_opened=$_POST['year_opened'];
							$modelII->year_renovated=$_POST['year_renovated'];
							$modelII->num_rooms=$_POST['num_rooms'];
							$modelII->num_suites=$_POST['num_suites'];
							$modelII->num_floors=$_POST['num_floors'];
							
							$modelII->brand=$_POST['brand'];
							$modelII->currency_code=$_POST['currency'];
							$modelII->lowest_rate=$_POST['low_rate'];
							$modelII->heighest_rate=$_POST['high_rate'];
							
							if($modelII->save()):
								$status = true;
							else:
								$status = false;
							endif;
						endif; */
						
					if($status == true):
						unset(Yii::app()->session['business_info']);
						unset(Yii::app()->session['business_info_FILES']);
						Yii::app()->user->setFlash('success', 'Your Business Information has been submited successfully.');
						$this->redirect(array('User/BusinessOwner'));	
					else:
						Yii::app()->user->setFlash('success', 'Business information is not saved with us due to some technical issue. Please inform administration. ');	
					endif;	
			else:
				$criteria=new CDbCriteria();
				$criteria->select = '*';
				$criteria->condition = 'status =:status';
				$criteria->params = array(':status' => 1);
				$Categories = Categories::model()->findAll($criteria);
				
				$this->renderPartial('//layouts/header');
				$this->render('BusinessOwner', array('categories' => $Categories));
				$this->renderPartial('//layouts/footer');
			endif;
		}
		else
		{
			$this->redirect(array('Site/signin'));
		}		
	}
	
	public function actionVerifylisting(){
			$this->renderPartial('//layouts/header');
			$this->render('Verifylisting');
			$this->renderPartial('//layouts/footer');
	}
	
	public function actionTermandconditions(){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] != ''):
			if(isset($_POST["submit_business"])):
				if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] != ''):
					
					$_POST = yii::app()->session['business_info'];
					$_FILES =yii::app()->session['business_info_FILES'];
					ECHO '<PRE>';
					print_r($_FILES);
					die;
					
					
				else:
					$this->redirect(array('Site/signin'));	
				endif;
			else:
				$this->renderPartial('//layouts/header');
				$this->render('Termandconditions');
				$this->renderPartial('//layouts/footer');
			endif;	
		else:
			$this->redirect(array('Site/signin'));	
		endif;
	} 
	/****************************************************************/
	
	public function actionUserlist()
	{		
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			
			$criteria=new CDbCriteria();
			
			$criteria->condition = 'user_status =:state';
			
			$criteria->params = array(':state' => 1);
			if(isset($_GET['q'])){
				$q = $_GET['q'];
				$criteria->compare('login_email', $q, true);
			}
			$criteria->order = 'name ASC';
			$count=Users::model()->count($criteria);
			$pages=new CPagination($count);
			// results per page
			$pages->pageSize=10;
			$pages->applyLimit($criteria);
			
			$Userlist=Users::model()->with('UserMeta')->findAll($criteria);
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('userlist',array('userlist'=>$Userlist,'pages' => $pages));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
	
	
	public function actionAdminadduser()
	{		
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			
				if(isset($_POST['submit']))
				{
					
					$model=new Users;
					$model->login_email= $email = $_POST['email'];
					///Check User
					$res = Yii::app()->db->createCommand()->select('id')->from('trpUsers')->queryRow();
					if(empty($row))
					{
					
						$model->password=md5($_POST['password']);
						$model->save();
						
						$user_id = $model->id;
						$modelM=new UsersMeta;
						$modelM->user_id=$user_id;
						$modelM->firstname=$_POST['f_name'];
						$modelM->lastname=$_POST['l_name'];
						$modelM->zip=$_POST['z_code'];
						$modelM->fbid='';

						if($modelM->save())
						{
								Yii::app()->user->setFlash('register','You have registered Successfully.');
								$this->redirect(array('User/userlist'));
						}
						else
						{
								Yii::app()->user->setFlash('error','Some error Occured.');
								$this->redirect(array('User/adminadduser'));
						}
					}
					else
					{
						Yii::app()->user->setFlash('error','User with this email already exist.. Try again wih different email..!!');
						$this->redirect(array('User/adminadduser'));
					}
				
				}
			
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('adminadduser');
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
	
	public function actionDeleteuser($id){
	
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			//echo $id; die;
			
			$restaurants_delete1=Yii::app()->db->createCommand()
			->delete('cads_Messages', 'owner_id = '.$id);
			$restaurants_delete2=Yii::app()->db->createCommand()
			->delete('cads_UsersMeta', 'user_id = '.$id);
			
			/*$restaurants_delete=Yii::app()->db->createCommand()
			->delete('cads_Users', 'id = '.$id);
			 $criteria=new CDbCriteria();	
			$post = Users::model()->findByPk($id); 
			$deleteusermeta = UsersMeta::model()->find('user_id=:uid', array(':uid'=>$id));
			$deleteusermsg = Messages::model()->find('owner_id=:uid', array(':uid'=>$id));
			echo "<pre>";
			print_r($deleteusermsg); //die;
			print_r($deleteusermeta); die;
			$deleteusermeta->delete();
			$deleteusermsg->delete(); */
			if(Yii::app()->db->createCommand()
			->delete('cads_Users', 'id = '.$id)):
					
					Yii::app()->user->setFlash('register','User Deleted Successfully.');
					$this->redirect(array('User/userlist'));
			else:
				Yii::app()->user->setFlash('error','Some error Occured.');
				$this->redirect(array('User/userlist'));
			endif;
		}
		
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
		
	public function actionUpdateuser($id){
		
			if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
			{
			
				if(isset($_POST['update'])){
				$id = $_POST['id'];	
				//echo "<pre>"; print_r($_POST); die;
				$updatedata['login_email']=$_POST['email'];

				$update['firstname']=$_POST['f_name'];
				$update['lastname']=$_POST['l_name'];
				$update['zip']=$_POST['z_code'];
				$update['fbid']='';
				
				$updatedata = Yii::app()->db->createCommand()
						->update('cads_Users', 
							$updatedata,
							'id=:id',
							array(':id'=>$id)
						);
						
				$update = Yii::app()->db->createCommand()
					->update('cads_UsersMeta', 
						$update,
						'user_id=:user_id',
						array(':user_id'=>$id)
					);
					
					
					if($update){
						Yii::app()->user->setFlash('register','User Profile Update Successfully.');
						$this->redirect(array('User/userlist'));
					}
					else{
						Yii::app()->user->setFlash('error','Record could not be updated..!!');
						$this->redirect(array('User/userlist'));
						
					}				
				
				}
				else{
					
				$criteria=new CDbCriteria();
				$count = Users::model()->findbyPk($id);
				
				$criteria=new CDbCriteria();
				$count = Users::model()->findbyPk($id);
				$Userdata = Users::model()->with('UserMeta')->findbyPk($id);
					
				$this->renderPartial('//layouts/admin/header');
				$this->renderPartial('//layouts/admin/sidebar');
				$this->render('updateuser',array('userdata'=>$Userdata));
				$this->renderPartial('//layouts/admin/footer');
				
				}
			}
			else
			{
				$this->redirect(array('Site/Signin'));
			}
		}
		
		public function actionUpdatecredit($id){
		
			if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
			{
			        $row = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_user_clicks_by_admin u')
					
					->where('user_id=:id', array(':id'=>$id))
					->queryRow();
						
					if(!empty($row))
					{
					  $clicks=$_POST['clicks'];	
					  if(!empty($clicks))
					  {
						$update = Yii::app()->db->createCommand()
							->update('cads_user_clicks_by_admin', 
								array(
									'clicks'=>$clicks									
								),
								'user_id=:id',
								array(':id'=>$id)
							);
						Yii::app()->user->setFlash('register','Record updated..!!');
						$this->redirect(array('User/userlist'));
					  }else{
						  $row = Yii::app()->db->createCommand()
								->select('*')
								->from('cads_user_clicks_by_admin u')
								
								->where('user_id=:id', array(':id'=>$id))
								->queryRow();
						$this->renderPartial('//layouts/admin/header');
				      $this->renderPartial('//layouts/admin/sidebar');
				      $this->render('updatecredit',array('userdata'=>$id,'click_no'=>$row));
				      $this->renderPartial('//layouts/admin/footer');
                    	  
					  }	  
					  			  
					}else{
						//echo "<pre>";print_r($_POST['clicks']);die;
						if(!empty($_POST['clicks']))
						{ //echo "fsfsdfsfsf";die;
						  $model=new AdminClicks;
						  $model->clicks=$_POST['clicks'];
						  $model->user_id=$id;
						  $model->save();
                          Yii::app()->user->setFlash('register','Record added..!!');
						$this->redirect(array('User/userlist'));						  
						}
                        else{						
						$row = Yii::app()->db->createCommand()
								->select('*')
								->from('cads_user_clicks_by_admin u')
								
								->where('user_id=:id', array(':id'=>$id))
								->queryRow();
						$this->renderPartial('//layouts/admin/header');
				        $this->renderPartial('//layouts/admin/sidebar');
				        $this->render('updatecredit',array('userdata'=>$id,'click_no'=>$row));
				        $this->renderPartial('//layouts/admin/footer');
						}
					}	
                    					
				   
				
				
			}
			else
			{
				$this->redirect(array('Site/Signin'));
			}
		}
		
		
			
	public function actionMyprofile()
		{
			if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
			{
				$myid = (Yii::app()->session['userid']);
				
				$criteria=new CDbCriteria();
				$count = Users::model()->findbyPk($myid);
				
				$criteria=new CDbCriteria();
				$count = Users::model()->findbyPk($myid);
				$Userdata = Users::model()->with('UserMeta')->findbyPk($myid);
					
				$this->renderPartial('//layouts/admin/header');
				$this->renderPartial('//layouts/admin/sidebar');
				$this->render('myprofile', array('mydata'=>$Userdata));
				$this->renderPartial('//layouts/admin/footer');
			}
			else
			{
				$this->redirect(array('Site/Signin'));
			}
		}
			

	public function actionUpdatemyprofile($id){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(isset($_POST['update'])){
			
			$updatedata['login_email']=$_POST['email'];

				$update['firstname']=$_POST['f_name'];
				$update['lastname']=$_POST['l_name'];
				$update['zip']=$_POST['z_code'];
				$update['fbid']='';
				
				$updatedata = Yii::app()->db->createCommand()
						->update('trpUsers', 
							$updatedata,
							'id=:id',
							array(':id'=>$id)
						);
						
				$update = Yii::app()->db->createCommand()
					->update('trpUsersMeta', 
						$update,
						'user_id=:user_id',
						array(':user_id'=>$id)
					);
					
					if($update){
						Yii::app()->user->setFlash('register','User Profile Update Successfully.');
						$this->redirect(array('User/myprofile'));
					}				
			}
			else{
				$criteria=new CDbCriteria();
				$count = Users::model()->findbyPk($id);
				
				$criteria=new CDbCriteria();
				$count = Users::model()->findbyPk($id);
				$Userdata = Users::model()->with('UserMeta')->findbyPk($id);
				
				$this->renderPartial('//layouts/admin/header');
				$this->renderPartial('//layouts/admin/sidebar');
				$this->render('updatemyprofile',array('mydata'=>$Userdata));
				$this->renderPartial('//layouts/admin/footer');
			}
		}
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
	
	public function actionEditAdd($id=Null)
	{

		if(isset(Yii::app()->session['userid'])) {
				
			if(isset($_POST['submit'])) {
				
				$Ads = Ads::model()->findbyPk($id);
				$Ads->post_title	= $_POST['title'];
				$Ads->description	= $_POST['description'];
				$Ads->url	= $_POST['url'];				
				$Ads->city_id		= $_POST['city_id'];
				$Ads->latitude  = $_POST['r_lattitude'];
				$Ads->longitude = $_POST['r_longitude'];
				
				/* $Ads->contact_person = $_POST['fname'];
				$Ads->phone = $_POST['phone'];
				$Ads->postal_code = $_POST['postalcode']; */
				$Ads->updated_date	= date('Y-m-d h:i:s');
				
				$multi_cat			= $_POST['multi_cat'];
			
				if(isset($_POST['subcategorydrop'])){
					$multi_subcat	    = $_POST['subcategorydrop'];
				}
							
				if(($_FILES['adsimage']['error'][0]==0) && ($_FILES['adsimage']['error'][0] == 0)){
					if (is_uploaded_file($_FILES['adsimage']['tmp_name'][0]) == true){	
						
						$extensions = array("jpeg",	 "jpg", "png");
						foreach ($_FILES['adsimage']['tmp_name'] as $key => $tmp_name)
						{	
						
							$file_name = $key . $_FILES['adsimage']['name'][$key];
							$file_name = str_replace(" ",'_',$file_name);
							$file_size = $_FILES['adsimage']['size'][$key];
							$file_tmp  = $_FILES['adsimage']['tmp_name'][$key];
							$file_type = $_FILES['adsimage']['type'][$key];
							$fileext   = explode('.', $file_name);
							$count     = count($fileext);
							$count1    = $count - 1;
							$fileext1  = $fileext[$count1];
							$file_ext  = strtolower($fileext1);
							if ((in_array($file_ext, $extensions) === false) || ($file_size > 2097152)) {
								//echo "in if"; die;
								Yii::app()->user->setFlash('error', 'Image cannot be update. Please try with .jpeg,.png image type extensions and Size below 2Mb');
							} else {			
								$modelIm		= new AdsImages;
								$modelIm->ad_id = $id;
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
								
								$thumbnail = $thumb_path . "thumb_" . $key . str_replace(" ",'_',$_FILES['adsimage']['name'][$key]);
								//echo $thumbnail; echo "<br>"; echo $thumb_create;
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
						//die;
					}
				}
				
				if(isset($multi_cat) && isset($multi_subcat)){
					//continue;
				} else {
					AdSelected::model()->deleteAll('ad_id=:ad_id', array(':ad_id' => $id));
					$countSet = 1;
					foreach($multi_cat as $cat):				
					$subcatName = 'multi_subcategory_'.$countSet;
					if(isset($_POST["$subcatName"])){
					foreach($_POST["$subcatName"] as $feature):
						$feature_list_name = 'multi_feature_'.$cat.'_'.$feature;			
						 $enterCategory = new AdSelected;
						$enterCategory->ad_id = $id;
						$enterCategory->category_id = $cat;	
						$enterCategory->subcategory_id = $feature;	
						$enterCategory->save(); 
					endforeach;
					}			
					$countSet++;	
					endforeach;
				}
				
				if($Ads->update()):
					FieldsData::model()->deleteAll('aid=:ad_id', array(':ad_id' => $id));
					if($id !== null && isset($_POST['extraField'])){
							
							foreach($_POST['extraField'] as $keyExtra => $valueextra){
								
								$FieldsDataModel = new FieldsData;
								$FieldsDataModel->aid  = $id;								
								
									$FieldsDataModel->field_name = $keyExtra;	
									$FieldsDataModel->field_value = $valueextra;
																	
								$FieldsDataModel->save();
							}
							
						}

					if(isset($multi_cat) && isset($multi_subcat)){	
						//continue;	
					} else {
						AdSelected::model()->deleteAll('ad_id=:ad_id', array(':ad_id' => $id));
						$countSet = 1;
						foreach($multi_cat as $cat):				
						$subcatName = 'multi_subcategory_'.$countSet;
						if(isset($_POST["$subcatName"])){
						foreach($_POST["$subcatName"] as $feature):
							$feature_list_name = 'multi_feature_'.$cat.'_'.$feature;			
							$enterCategory = new AdSelected;
							$enterCategory->ad_id = $id;
							$enterCategory->category_id = $cat;	
							$enterCategory->subcategory_id = $feature;
							
							//echo "Categoriesid-".$enterCategory->category_id."---";
							//echo "SubCategoriesid-".$enterCategory->subcategory_id."<br/>";
							$enterCategory->save(); 
						endforeach;
						}			
						$countSet++;	
						endforeach;
					}
					
					Yii::app()->user->setFlash('success', "Your Ads is Updated");
					$redirecturl = 'User/EditAdd/'.$id;
					$this->redirect(array($redirecturl));
				else:
					Yii::app()->user->setFlash('success', "Sorry Something missing. Please try again..");
					$redirecturl = 'User/EditAdd/'.$id;
					$this->redirect(array($redirecturl));
				endif;
				
				
				
			} elseif($id!='') {
						
						
						$ads = Yii::app()->db->createCommand()
						->select('ad.*,ad.id adid,city.cityname')
						->from('cads_Ads ad')
						->leftJoin('cads_City city','ad.city_id=city.id')
						->where('ad.id=:id',array(':id'=>$id))
						->queryRow();
					
						$cusctyid = $ads['city_id'];
						$citydata = Yii::app()->db->createCommand()
						->select('city.*,city.id cityid,city.city_zipcode zipcode,state.*,country.*')
						->from('cads_City city')
						->leftJoin('cads_CityState cityState', 'cityState.cityid=city.id')
						->leftJoin('cads_State state', 'state.id=cityState.stateid')
						->leftJoin('cads_CountryState stateCountry', 'state.id=stateCountry.stateid')
						->leftJoin('cads_Country country', 'country.id=stateCountry.countryid')
						->where('city.id=:CID',array(':CID'=>$cusctyid))
						->limit(25)
						->queryRow();
						
						/* Check Selected Categories Array */
						$selectedCategories = AdSelected::model()->findAll('ad_id=:adid', array(':adid'=>$id));
						$Adsselectedcat = array();
						$AdsselectedSubcat = array();
						if(!empty($selectedCategories)){
							foreach($selectedCategories as $Slectdcat){
								$Adsselectedcat[]=$Slectdcat['category_id'];
								$AdsselectedSubcat[]=$Slectdcat['subcategory_id'];
							}
						}
						/* Check Selected Categories Array */
						$location = '';	
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
							
						$Categories = Categories::model()->findAll();
						$SubCategories = Subcategories::model()->findAll();
						$imgs = AdsImages::model()->findAll('ad_id=:adid', array(':adid'=>$id));
						if($ads['user_id']==Yii::app()->session['userid']){
											
							$this->renderPartial('//layouts/header');
							
							$this->render('editAd', array(	'ad'=>$ads,
															'imgs'=>$imgs,
															'location'=>$location,
															'categories'=>$Categories,
															'SubCategories'=>$SubCategories,
															'Adsselectedcat'=>$Adsselectedcat,
															'AdsselectedSubcat'=>$AdsselectedSubcat));
							
							$this->renderPartial('//layouts/footer');						
						} else {
							Yii::app()->user->setFlash('adnotur', "It was Not Your Ad.");
							$this->redirect(array('User/Myaccount'));
						}				
			} else{
				$this->redirect(array('User/Myaccount'));
			}		
		} else {
			$this->redirect(array('Site/Signin'));
		}		
	}
	public function actionDeleteAdsimage($id = Null){
		if(isset(Yii::app()->session['userid'])):
			$imgid = $_REQUEST['imageid'];
			$Adsid = $_REQUEST['addsid'];
			$deleteAdimgs = AdsImages::model()->findByPk($imgid);
			if(!empty($deleteAdimgs)) {
				$imgname = $deleteAdimgs->image;
				$imgurl = dirname($_SERVER['SCRIPT_FILENAME']) . "/images/postAds/".$imgname;
				$imgurl1 = dirname($_SERVER['SCRIPT_FILENAME']) . "/images/postAds/thumb_".$imgname;
				if(file_exists($imgurl))
				{
					unlink($imgurl);
				}
				if(file_exists($imgurl1))
				{
					unlink($imgurl1);
				}
				
			}						
			if($deleteAdimgs->delete() == true):
				echo "The Image ".$deleteAdimgs->image." is deleted";
			else:
				echo "Not Deleted Some Error! Please Try Again";
			endif; 
		else:
			$this->redirect(array('Admin/login'));	 
		endif;
	}
	
	
}

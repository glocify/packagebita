<?php 
class AdminController extends CController
{
	/**
	 * This is the default action that displays the phonebook Flex client.
	 */
	
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
					$this->redirect(array('Admin/dashboard'));
				}
				else
				{
					Yii::app()->user->setFlash('error','Invalid username or password');
				
				}
			}
			$this->render('login',array('model'=>$model));
		} elseif(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1){
			$this->redirect(array('Admin/dashboard'));
		} else {			
			$this->renderPartial('//layouts/admin/header');
			$this->render('insufficientPermission');
			$this->renderPartial('//layouts/admin/footer');
		}
	}
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('Admin/login')); 
	}
	
	function actionIndex(){		
		$this->redirect(array('dashboard'));
		//$this->render('dashboard');
	}
	function actionAdsnotification(){		
		$sql = "SELECT COUNT(*)  count FROM cads_Adsnotification";
		$Ads = Yii::app()->db->createCommand($sql);
		$results = $Ads->queryAll();
		echo $results[0]['count'];
		//$this->render('dashboard');
	}
	public function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	function actionAdsnotificationdata(){		
		$base_url = Yii::app()->getBaseUrl(true);
		$sql = "SELECT * FROM cads_Adsnotification";
		$Ads = Yii::app()->db->createCommand($sql);
		$results = $Ads->queryAll();
		if(!empty($results)){?>
			<li><p>You have <?php echo count($results);?> new messages</p></li>
			<?php foreach($results as $notification){?>			
			<li>
				<a href="<?php echo $base_url;?>/Admin/editAd/<?php echo $notification['ad_id'];?>">
					<span class="subject">
						<span class="from"><?php echo $notification['username'];?></span>
						<span class="time"><?php echo $this->time_elapsed_string($notification['created_date']); ?></span>
					</span>
					<span class="message">
					<?php echo $notification['post_title'];?>
					</span>  
				</a>
			</li>
			<?php } ?>
			<li class="external"><a href="<?php echo $base_url;?>/Admin/Unapproved_ads">See all<i class="m-icon-swapright"></i></a></li>
			<?php Yii::app()->db->createCommand()->delete('cads_Adsnotification');
		} else{
			echo "<li><p>You don't have any New Ad notification</p></li>";
		} ?>
		
<?php	}

	public function actionUnflaged($id=NULL)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{		
			
			$Adunflaged = Ads::model()->findbyPk($id);
			$Adunflaged->ad_flaged=0;
			if($Adunflaged->save()):
				Yii::app()->user->setFlash('success', "Your Ad is Unflaged!");
				$this->redirect(array('Admin/PostAds'));
			endif;
		}
		else
		{			
			$this->redirect(array('Admin/login'));
		}
	}
	public function actionDashboard()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$userid = Yii::app()->session['userid'];
			$userData = Yii::app()->db->createCommand()
					->select('users.*')
					->from('cads_Users users')
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
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionAddfield()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(isset($_POST['submit']))
			{
				//echo "<pre>"; print_r($_POST); die;
				$label = $_POST['label'];
				$inputType = $_POST['inputType'];
				if($inputType=='SelectDropdown')
				{
					$numop = $_POST['optionsValue'];
					for($i=1; $i<=$numop; $i++)
					{
						$sel = 'selctionVal'.$i;
						$fieldval[] = $_POST[$sel];
					}
				}
				if($inputType=='RadioButton')
				{
					$fieldval[] = $_POST['firstRadioBttnLabal'];
					$fieldval[] = $_POST['secondRadioBttnLabal'];
				}
				if($inputType=='Checkboxes')
				{
					$numop = $_POST['checkOptionsValue'];
					for($i=1; $i<=$numop; $i++)
					{
						$sel = 'checkVal'.$i;
						$fieldval[] = $_POST[$sel];
					}
				}
				if($inputType=='textArea')
				{
					$fieldval="";
				}			
				if($inputType=='text')
				{
					$fieldval="";
				}
				$fval = serialize($fieldval);
				$model=new Fields;
				$model->field_label = $label;
				$model->fieldtype = $inputType;
				$model->field_value = $fval;
				if($model->save()==true)
				{
					Yii::app()->user->setFlash('success', "Fields added Successfully..!!");
					$this->redirect(array('Page/managefields'));
				}
				//echo "<pre>"; print_r($fieldval); die;
			}
			
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addfield', array('userData' => ''));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	public function actionEditfield($id=NULL)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1){
			if(isset($_POST['submit']))
			{
				//echo "<pre>"; print_r($_POST); die;
				$label = $_POST['label'];
				$inputType = $_POST['inputType'];
				if($inputType=='SelectDropdown')
				{
					$numop = $_POST['optionsValue'];
					for($i=1; $i<=$numop; $i++)
					{
						$sel = 'selctionVal'.$i;
						$fieldval[] = $_POST[$sel];
					}
				}
				if($inputType=='RadioButton')
				{
					$fieldval[] = $_POST['firstRadioBttnLabal'];
					$fieldval[] = $_POST['secondRadioBttnLabal'];
				}
				if($inputType=='Checkboxes')
				{
					$numop = $_POST['checkOptionsValue'];
					for($i=1; $i<=$numop; $i++)
					{
						$sel = 'checkVal'.$i;
						$fieldval[] = $_POST[$sel];
					}
				}
				if($inputType=='textArea')
				{
					$fieldval="";
				}			
				if($inputType=='text')
				{
					$fieldval="";
				}
				$fval = serialize($fieldval);
				$model['field_label'] = $label;
				$model['fieldtype'] = $inputType;
				$model['field_value'] = $fval;
				$update = Yii::app()->db->createCommand()
								->update('cads_fields', 
									$model,
									'id=:id',array(':id'=>$_POST['fid'])
								);
				
					Yii::app()->user->setFlash('success', "Fields added Successfully..!!");
					$this->redirect(array('Page/managefields'));			
				//echo "<pre>"; print_r($fieldval); die;
			}
			$fields = Fields::model()->findByPk($id);
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('editfield', array('fields' => $fields));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	public function actionDeletefield($id=NULL)
	{
		$deleteCategory = Fields::model()->findByPk($id);
			//$deleteCategory->status = 0;
			if($deleteCategory->delete() == true):
				Yii::app()->user->setFlash('success', "Fields has been deleted Successfully.");
				$this->redirect(array('Page/managefields'));
			endif;
	}
	
	public function actionCategories(){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1):
			/* $criteria=new CDbCriteria();
			$criteria->select = '*';
			$criteria->condition = 'category_status =:state';
			$criteria->params = array(':state' => 1);
			$criteria->order = 'id ASC';			
			$item_count = Categories::model()->count($criteria);			
			$pagesCategories = new CPagination($item_count);
			$pagesCategories->setPageSize(40);			
			$pagesCategories->applyLimit($criteria);			
			$allcategory = Categories::model()->findAll($criteria);
			
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('categories',array('allcategory'=>$allcategory,'pagesCategories'=>$pagesCategories, 'item_count'=>$item_count,'page_size'=>Yii::app()->params['listPerPage']));
			$this->renderPartial('//layouts/admin/footer'); */
			
			$criteria=new CDbCriteria();
			
			$criteria->condition = 'category_status =:state';
			
			$criteria->params = array(':state' => 1);
			if(isset($_GET['q'])){
				$q = $_GET['q'];
				$criteria->compare('category_name', $q, true);
			}
			$criteria->order = 'category_order DESC';
			$count=Categories::model()->count($criteria);
			$pages=new CPagination($count);
			// results per page
			$pages->pageSize=5;
			$pages->applyLimit($criteria);
			$allcategory=Categories::model()->findAll($criteria);
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('categories', array(
			'allcategory' => $allcategory,
			 'pages' => $pages
			));
			$this->renderPartial('//layouts/admin/footer');
			
		else:
			$this->redirect(array('Admin/login'));
		endif;
	}
	
	public function actionDeletecategory($id = Null){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1):
		
			$deleteCatsubcat = Catsubcat::model()->findAll('category_id =:catid', array(':catid'=>$id));
			
			$subCatArrayforDelete = array();
			if(!empty($deleteCatsubcat)){
				foreach($deleteCatsubcat as $singledeleteCatsubcat){
					$deleteSCategory = Subcategories::model()->findByPk($singledeleteCatsubcat->subcategory_id);
					if(!empty($deleteSCategory)){
						$deleteSCategory->delete();
					}
				}
			}
			$deleteCategory = Categories::model()->findByPk($id);
			
			if($deleteCategory->delete() == true):
				Yii::app()->user->setFlash('success', "Category has been deleted Successfully.");
				$this->redirect(array('Admin/categories'));
			else:
				Yii::app()->user->setFlash('success', "Sorry Category cannot be deleted.");
				$this->redirect(array('Admin/categories'));
			endif;
		else:
			$this->redirect(array('Admin/login'));	 
		endif;
	}

	public function actionUpdatecategory($id = Null)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(isset($_POST['submit']))
			{
					$model = Categories::model()->findbyPk($id);
					$model->category_name=$_POST['category'];
					$model->category_order=$_POST['category_order'];
					$model->category_status=$_POST['category_status'];
					$oldImageName =  $model->category_image;
					
					if(isset($_FILES['categoryimage']['name']) && $_FILES['categoryimage']['name'] !== ''){			
						
						$extensions = array("jpeg","jpg","png");
						$file_name = $_FILES['categoryimage']['name'];
						$file_size =$_FILES['categoryimage']['size'];
						$file_tmp =$_FILES['categoryimage']['tmp_name'];
						$file_type=$_FILES['categoryimage']['type'];
						$fileext=explode('.',$file_name);
						
						$count = count($fileext);
						$count1 = $count-1;
						$fileext1=$fileext[$count1];  
						$file_ext=strtolower($fileext1);  
						
						if(in_array($file_ext,$extensions ) === false){
							Yii::app()->user->setFlash('error', "Please select valid image.");
							$this->redirect(array('Admin/updatecategory/'.$id));
						}  else {
							$newfileName = time().$_FILES['categoryimage']['name'];
							move_uploaded_file($file_tmp,"images/categorythumb/".$newfileName);
							
							/* $thumbnail_width = 36;		
							$thumbnail_height = 35;
							$thumb_beforeword = "thumb";
							$thumb_path = "images/categorythumb/";
							$size = getimagesize($file_tmp);
							$original_width = $size[0];
							$original_height = $size[1];

									$thumb_create = imagecreatetruecolor($thumbnail_width,$thumbnail_height);
									switch($file_ext){
										case 'jpg':
											$source = imagecreatefromjpeg($file_tmp);
											break;
										case 'jpeg':
											$source = imagecreatefromjpeg($file_tmp);
											break;
										case 'png':
											$source = imagecreatefrompng($file_tmp);
											break;
										default:
											$source = imagecreatefromjpeg($file_tmp);
									}

									imagecopyresized($thumb_create,$source,0,0,0,0,$thumbnail_width,$thumbnail_height,$original_width,$original_height);
									$newfileName = time().$_FILES['categoryimage']['name'];
									$thumbnail = $thumb_path.$newfileName;
									
									switch($file_ext){
										case 'jpg' || 'jpeg':
											imagejpeg($thumb_create,$thumbnail,100);
											break;
										case 'png':
											$background = imagecolorallocate($thumb_create ,  255, 255, 255, 127);
											imagecolortransparent($thumb_create, $background);
											imagepng($thumb_create,$thumbnail,100);
											break;
										default:
											imagejpeg($thumb_create,$thumbnail,100);
									} */
						}
						if(!empty($newfileName)){
							$model->category_image = $newfileName;
						}
						$model->created_date = new CDbExpression('NOW()');						
					}
					
					if($model->save()):
						Yii::app()->user->setFlash('success', "Category has been updated.");
						$this->redirect(array('Admin/categories'));
					else:
						Yii::app()->user->setFlash('success', "Sorry Something missing. Please try again..");
						$redirecturl = 'Admin/categories/'.$id;
						$this->redirect(array($redirecturl));
					endif;
					
			}else{
					$category = Categories::model()->findByPk($id);		
					$this->renderPartial('//layouts/admin/header');
					$this->renderPartial('//layouts/admin/sidebar');
					$this->render('updatecategory', array('category'=>$category));
					$this->renderPartial('//layouts/admin/footer');
			}
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionAddcategory(){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1){

			if(isset($_POST['submit'])){
			
				$model=new Categories;							
				
				
				$extensions = array("jpeg","jpg","png");
				$file_name = $_FILES['categoryimage']['name'];
				$file_size =$_FILES['categoryimage']['size'];
				$file_tmp =$_FILES['categoryimage']['tmp_name'];
				$file_type=$_FILES['categoryimage']['type'];
				$fileext=explode('.',$file_name);
				$count = count($fileext);
				$count1 = $count-1;
				$fileext1=$fileext[$count1];  
				$file_ext=strtolower($fileext1);
				
				
				if(in_array($file_ext,$extensions ) === false){
					
					Yii::app()->user->setFlash('error', "Please select valid image.");
					$this->redirect(array('Admin/addcategory'));
				} else {
					$newfileName = time().$_FILES['categoryimage']['name'];
							move_uploaded_file($file_tmp,"images/categorythumb/".$newfileName);
					/* $thumbnail_width = 36;		
					$thumbnail_height = 35;
					$thumb_beforeword = "thumb";
					$thumb_path = "images/categorythumb/";
					$size = getimagesize($file_tmp);
					$original_width = $size[0];
					$original_height = $size[1];
					
					$thumb_create = imagecreatetruecolor($thumbnail_width,$thumbnail_height);
						switch($file_ext){
							case 'jpg':
								$source = imagecreatefromjpeg($file_tmp);
								break;
							case 'jpeg':
								$source = imagecreatefromjpeg($file_tmp);
								break;

							case 'png':
								$source = imagecreatefrompng($file_tmp);
								break;
							default:
								$source = imagecreatefromjpeg($file_tmp);
						}

						imagecopyresized($thumb_create,$source,0,0,0,0,$thumbnail_width,$thumbnail_height,$original_width,$original_height);
						
						$newfileName = time().$_FILES['categoryimage']['name'];
						$thumbnail = $thumb_path.$newfileName;
						
						switch($file_ext){
							case 'jpg' || 'jpeg':
								imagejpeg($thumb_create,$thumbnail,100);
								break;
							case 'png':
								$background = imagecolorallocate($thumb_create , 255, 255, 255, 127);
								imagecolortransparent($thumb_create, $background);
								imagepng($thumb_create,$thumbnail,100);
								break;
							default:
								imagejpeg($thumb_create,$thumbnail,100);
						} */
				}
				if(!empty($newfileName)){
					$model->category_image = $newfileName;
				}
				
				$model->category_name=$_POST['category'];
				$model->category_order = $_POST['category_order'];
				$model->category_status = 1;
				$model->created_date = new CDbExpression('NOW()');
				
				if($model->save()){
					Yii::app()->user->setFlash('success', "New Category has been list for business.");
					$this->redirect(array('Admin/categories'));	
				}else{
					Yii::app()->user->setFlash('successs', "Sorry Something missing. Please try again.");
					$this->redirect(array('Admin/categories'));	
				}
			}
			
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addcategory');
			$this->renderPartial('//layouts/admin/footer');
		} else {
			$this->redirect(array('Admin/login'));
		}	
	}

	
	/*   Add Subcategorys Function */
	/*   Subcategories Controller start from here  */
	
	public function actionSubcategories(){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1):
			
			$criteria=new CDbCriteria();
			
			$criteria->condition = 'subcategory_status =:state';
			
			$criteria->params = array(':state' => 1);
			if(isset($_GET['q'])){
				$q = $_GET['q'];
				$criteria->compare('subcategory_name', $q, true);
			}
			$criteria->order = 'subcategory_name ASC';
			$count=Subcategories::model()->count($criteria);
			$pages=new CPagination($count);
			// results per page
			$pages->pageSize=5;
			$pages->applyLimit($criteria);
			
			$allcategory=Subcategories::model()->with('Categories')->findAll($criteria);
			
			
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('subcategories', array(
			'allcategory' => $allcategory,
			 'pages' => $pages
			));
			$this->renderPartial('//layouts/admin/footer');
			
			/* $pages = (isset($_GET['page']) ? $_GET['page'] : 1);
			if(!empty($_GET["q"])){
				$search = $_GET["q"];
			}
			
			
			if (!empty($search)){
				$item_count = Yii::app()->db->createCommand()
						->select('count(*) as count')
						->from('cads_subcategories s')
						->join('cads_categories c', 's.categoryid=c.id')
						->where('s.categorystatus=:status', array(':status'=>1))
						->andWhere(array('like','s.categoryname',"%$search%"))
						->orWhere(array('like','c.category_name',"%$search%"))
						->queryRow();
				
				$allcategory = Yii::app()->db->createCommand()
					->select('s.id, s.categoryname, s.categorystatus,c.category_name')
					->from('cads_subcategories s')
					->join('cads_categories c', 's.categoryid=c.id')
					->where('s.categorystatus=:status', array(':status'=>1))	
					->andWhere(array('like','s.categoryname',"%$search%"))
					->orWhere(array('like','c.category_name',"%$search%"))
					->limit(Yii::app()->params['listPerPage'], $pages-1)
					->queryAll();
			} else {
					$allcategory = Yii::app()->db->createCommand()
						->select('s.id, s.categoryname, s.categorystatus,c.category_name')
						->from('cads_subcategories s')
						->join('cads_categories c', 's.categoryid=c.id')
						->where('s.categorystatus=:status', array(':status'=>1))
						->limit(Yii::app()->params['listPerPage'], $pages-1)
						->queryAll();
					
					$item_count = Yii::app()->db->createCommand()
						->select('count(*) as count')
						->from('cads_subcategories s')
						->join('cads_categories c', 's.categoryid=c.id')
						->where('s.categorystatus=:status', array(':status'=>1))
						
						->queryRow();
				
			}
			
			//print_r($allcategory); die;
			
			
			$pages = new CPagination($item_count);
			$pages->setPageSize(Yii::app()->params['listPerPage']);
			
			
			
				
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('subcategories', array('allcategory'=>$allcategory,'item_count'=>$item_count,'page_size'=>Yii::app()->params['listPerPage'], 'pages'=>$pages));
			$this->renderPartial('//layouts/admin/footer'); */

		else:
			$this->redirect(array('Admin/login'));
		endif;
	}
	public function actionAddsubcategory(){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1){
		
		$allcategory = Yii::app()->db->createCommand()
			->select('id,category_name')
			->from('cads_Categories')
			->where('category_status=:status', array(':status'=>1))
			->queryAll();
			
		$Fieldsmodel = Fields::model()->findall();
	
			if(isset($_POST['submit'])){	
				
				if(!empty($_POST['fieldsids'])){
					$extrafidids = implode(",",$_POST['fieldsids']);
				}else{
					$extrafidids = '';
				}
	
				$model = Subcategories::model()->find("subcategory_name = :categoryname", array(':categoryname'=>$_POST['subcategory_name']) ); 
			
				if ($model == null) {
					if($_FILES['categoryimage']['name'] !== ''){
						$model = new Subcategories;					
						$extensions = array("jpeg","jpg","png");
						$file_name = $_FILES['categoryimage']['name'];
						$file_size =$_FILES['categoryimage']['size'];
						$file_tmp =$_FILES['categoryimage']['tmp_name'];
						$file_type=$_FILES['categoryimage']['type'];
						$fileext=explode('.',$file_name);
						$count = count($fileext);
						$count1 = $count-1;
						$fileext1=$fileext[$count1];  
						$file_ext=strtolower($fileext1);
						
						
						if(in_array($file_ext,$extensions ) === false){						
							Yii::app()->user->setFlash('successs', "Please select valid image.");
							$this->redirect(array('Admin/addsubcategory'));
						} else {						
								$newfileName = time().$_FILES['categoryimage']['name'];
								move_uploaded_file($file_tmp,"images/subcategorythumb/".$newfileName);
						}
					
						
					}else{
						$newfileName = '';
					}
					$model=new Subcategories;	
					$model->subcategory_name = $_POST['subcategory_name'];
					if($newfileName !== ''){
						$model->subcategory_image = $newfileName;
					}
					$model->created_date = new CDbExpression('NOW()');				
					$model->subcategory_status = 1;
					
					if($model->save()){			
						
						$subcategoryID = $model->id;
						$CategoryID = $_POST['category_id'];
						$relationmodel = new Catsubcat;
						$relationmodel->category_id = $CategoryID;
						$relationmodel->subcategory_id = $subcategoryID;
						$relationmodel->fields_id = $extrafidids;
						if($relationmodel->save()){
							Yii::app()->user->setFlash('success', "New Subcategory has been Added.");
							$this->redirect(array('Admin/subcategories'));
						}
					}else{
						Yii::app()->user->setFlash('successs', "Sorry Something missing. Please try again.");
						$this->redirect(array('Admin/subcategories'));	
					}
				
				} else {
					Yii::app()->user->setFlash('success', "Already Exist Please Try with another Subcategory!");
					$this->redirect(array('Admin/addsubcategory'));
				}

				
			}
			
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addsubcategory', array('allcategory'=>$allcategory,'Fieldsmodel'=>$Fieldsmodel));
			$this->renderPartial('//layouts/admin/footer');
		} else {
			$this->redirect(array('Admin/login'));
		}	
	}
	public function actionUpdatesubcategory($id = Null)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$allcategory = Yii::app()->db->createCommand()
			->select('id,category_name')
			->from('cads_Categories')
			->where('category_status=:status', array(':status'=>1))
			->queryAll();
			
			$Fieldsmodel = Fields::model()->findall();
			
			$Realtion = Catsubcat::model()->find('subcategory_id=:catid', array(':catid'=>$id));
			$category_id = $Realtion->category_id;
			$relationID = $Realtion->id;
			
			if(isset($_POST['submit']))
			{
					$model = Subcategories::model()->findbyPk($id);
					$model->subcategory_name=$_POST['subcategory_name'];
					$model->subcategory_status=$_POST['subcategory_status'];
					$oldImageName =  $model->subcategory_image;
					$extrafids = implode(",",$_POST['fieldsids']);
					if(isset($_FILES['categoryimage']['name']) && $_FILES['categoryimage']['name'] !== ''){			
						
						$extensions = array("jpeg","jpg","png");
						$file_name = $_FILES['categoryimage']['name'];
						$file_size =$_FILES['categoryimage']['size'];
						$file_tmp =$_FILES['categoryimage']['tmp_name'];
						$file_type=$_FILES['categoryimage']['type'];
						$fileext=explode('.',$file_name);
						
						$count = count($fileext);
						$count1 = $count-1;
						$fileext1=$fileext[$count1];  
						$file_ext=strtolower($fileext1); 
						
						if(in_array($file_ext,$extensions ) === false){
							Yii::app()->user->setFlash('error', "Please select valid image.");
							$this->redirect(array('Admin/updatesubcategory/'.$id));
						}  else {	
							$newfileName = time().$_FILES['categoryimage']['name'];
							move_uploaded_file($file_tmp,"images/subcategorythumb/".$newfileName);	
						}
						if(!empty($newfileName)){
							$model->subcategory_image = $newfileName;
						} 
												
					}
					$model->created_date = new CDbExpression('NOW()');					
					
					if($model->save()):
						$Catsubcatmodel = Catsubcat::model()->findbyPk($relationID);
						$Catsubcatmodel->category_id=$_POST["category_id"];
						$Catsubcatmodel->subcategory_id=$model->id;
						$Catsubcatmodel->fields_id=$extrafids;
						if($Catsubcatmodel->save()):
							Yii::app()->user->setFlash('success', "Subcategory has been updated.");
							$this->redirect(array('Admin/subcategories'));
						endif;
					else:
						Yii::app()->user->setFlash('success', "Sorry Something missing. Please try again..");
						$redirecturl = 'Admin/subcategories/'.$id;
						$this->redirect(array($redirecturl));
					endif;
					
			}else{
					/* $subcategory = Subcategories::model()->with('Catsubcat')->findByPk($id);		
					echo '<pre>';
					print_r($subcategory);
					die; */
					$subcategory = Yii::app()->db->createCommand()
							->select('subcategories.*, subcateRelation.fields_id field_id')
							->from('cads_Subcategories subcategories')
							->leftJoin('cads_Catsubcat_relation subcateRelation','subcateRelation.subcategory_id=subcategories.id')
							->where('subcategories.id =:subcatId', array(':subcatId' =>$id))
							->queryRow();	
					
					$this->renderPartial('//layouts/admin/header');
					$this->renderPartial('//layouts/admin/sidebar');
					$this->render('updatesubcategory', array('subcategory'=>$subcategory,'allcategory'=>$allcategory,'category_id'=>$category_id,'Fieldsmodel'=>$Fieldsmodel,'Realtion'=>$Realtion));
					$this->renderPartial('//layouts/admin/footer');
			}
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionDeletesubcategory($id = Null){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1):
			$deleteCategory = Subcategories::model()->findByPk($id);	
			if($deleteCategory == true){
				$deleteCatsubcat = Catsubcat::model()->find('subcategory_id=:catid', array(':catid'=>$id));	
				if($deleteCatsubcat == true){
					if($deleteCatsubcat->delete() == true):
							Yii::app()->user->setFlash('success', "Subcategory has been deleted Successfully.");
							$this->redirect(array('Admin/subcategories'));
					else:
						Yii::app()->user->setFlash('success', "Sorry Subcategory cannot be deleted.");
						$this->redirect(array('Admin/subcategories'));
					endif;
				}else{
					if($deleteCategory->delete() == true):
							Yii::app()->user->setFlash('success', "Subcategory has been deleted Successfully.");
							$this->redirect(array('Admin/subcategories'));
					else:
						Yii::app()->user->setFlash('success', "Sorry Subcategory cannot be deleted.");
						$this->redirect(array('Admin/subcategories'));
					endif;
				}	
			}else{
				Yii::app()->user->setFlash('success', "Sorry Subcategory cannot be deleted.");
				$this->redirect(array('Admin/subcategories'));
			}
			
			
			
			
			/* if($deleteCatsubcat->delete() == true):
				if($deleteCategory->delete() == true):
					Yii::app()->user->setFlash('success', "Subcategory has been deleted Successfully.");
					$this->redirect(array('Admin/subcategories'));
				endif;
			else:
				Yii::app()->user->setFlash('success', "Sorry Subcategory cannot be deleted.");
				$this->redirect(array('Admin/subcategories'));
			endif; */
			
		else:
			$this->redirect(array('Admin/login'));	 
		endif;
	}
	
	/*  Locations Functions */
	public function actionLocations()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{			
			/* $cities = Yii::app()->db->createCommand()
					->select('city.*, state.statename, country.countryname')
					->from('cads_City city')
					->leftJoin('cads_CityState cityState', 'city.id=cityState.cityid')
					->leftJoin('cads_State state', 'state.id=cityState.stateid')
					->leftJoin('cads_CountryState stateCountry', 'cityState.stateid=stateCountry.stateid')
					->leftJoin('cads_Country country', 'country.id=stateCountry.countryid')
					->where('city.status =:status', array(':status' => 1))
					->order('city.id DESC')
					->queryAll();
					
			$states = Yii::app()->db->createCommand()
					->select('State.*, country.countryname')
					->from('cads_State State')
					->leftJoin('cads_CountryState StateCountry', 'State.id = StateCountry.stateid')
					->leftJoin('cads_Country country', 'country.id=StateCountry.countryid')
					->where('State.status =:status', array(':status' => 1))
					->order('State.id DESC')
					->queryAll();
			
			$countries = Yii::app()->db->createCommand()
						->select('Country.*')
						->from('cads_Country Country')
						->Join('cads_ContinentCountry CountryContinent', 'Country.id = CountryContinent.countryid')
						->Join('cads_Continent Continent', 'Continent.id=CountryContinent.continentid')
						->where('Country.status =:status', array(':status' => 1))
						->order('Country.id DESC')
						->queryAll();
					
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('locations',array('countries'=>$countries,'states'=>$states,'cities'=>$cities));
			$this->renderPartial('//layouts/admin/footer'); */
			
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('locations');
			$this->renderPartial('//layouts/admin/footer');
			
		} else {
			$this->redirect(array('Admin/login'));
		}
	}
	public function actionCityPaginationList() {
		if($_POST['page'])
			{
				$page = $_POST['page'];
				$cur_page = $page;
				$page -= 1;
				$per_page = 40;
				$previous_btn = true;
				$next_btn = true;
				$first_btn = true;
				$last_btn = true;
				$start = $page * $per_page;
				//echo $condition;die;
				$sql = "SELECT city.*, state.statename, country.countryname from cads_City city left Join cads_CityState cityState ON city.id=cityState.cityid left Join cads_State state ON state.id=cityState.stateid left Join cads_CountryState stateCountry ON cityState.stateid=stateCountry.stateid left Join cads_Country country ON country.id=stateCountry.countryid where city.status= 1 order by city.cityname limit $start,$per_page";
				$cities =  Yii::app()->db->createCommand($sql)->queryAll();
				$rrt=Yii::app()->db->createCommand()
					->select('count(*) cnt')
					->from('cads_City city')
					->leftJoin('cads_CityState cityState', 'city.id=cityState.cityid')
					->leftJoin('cads_State state', 'state.id=cityState.stateid')
					->leftJoin('cads_CountryState stateCountry', 'cityState.stateid=stateCountry.stateid')
					->leftJoin('cads_Country country', 'country.id=stateCountry.countryid')
					->where('city.status =:status', array(':status' => 1))					
					->order('city.id ASC')
					->queryRow();
				
				$msg = $cities;
				$data['allcnt']=$tot=$rrt['cnt'];
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
			if($tot<=40)
			{
				$msg .= "<div class='pagination'><ul>";
				$msg = $msg . "</ul></div>";   // Content for pagination
				echo $msg;
			}
			else
			{	
				$msg .= "<div class='pagination'><ul>";
			
				 // FOR ENABLING THE FIRST BUTTON
				if ($first_btn && $cur_page > 1):
					$msg .= "<li p='1' class='active'>First</li>";
				elseif($first_btn > 1):
					$msg .= "<li p='1' class='inactive'>First</li>";
				endif;
				
				 // FOR ENABLING THE PREVIOUS BUTTON
				if($previous_btn && $cur_page > 1):
					$pre = $cur_page - 1;
					$msg .= "<li p='$pre' class='active'>Previous</li>";
				elseif($previous_btn > 1):
					$msg .= "<li class='inactive'>Previous</li>";
				endif;
				
				for ($i = $start_loop; $i <= $end_loop; $i++):
					if ($cur_page == $i)
						$msg .= "<li p='$i' id='set$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
					else
						$msg .= "<li p='$i' id='set$i' class='active'>{$i}</li>";
				endfor;
				 // TO ENABLE THE NEXT BUTTON
				if($next_btn && $cur_page < $no_of_paginations): 
					$nex = $cur_page + 1;
					$msg .= "<li p='$nex' class='active'>Next</li>";
				elseif($next_btn  < $no_of_paginations):
					$msg .= "<li class='inactive'>Next</li>";
				endif;
				
				// TO ENABLE THE END BUTTON
				if ($last_btn && $cur_page < $no_of_paginations):
					$msg .= "<li p='$no_of_paginations' class='active'>Last</li>";
				elseif($last_btn < $no_of_paginations): 
					$msg .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
				endif;
					$msg = $msg . "</ul></div>";   // Content for pagination
					echo $msg;
			}
	}
		//echo "<pre>"; print_r($cities); die;
	$this->renderPartial('cityResults', array('data'=>$data,'cities'=>$cities));
	}
	public function actionStatePaginationList()
	{
		if($_POST['page'])
			{
				$page = $_POST['page'];
				$cur_page = $page;
				$page -= 1;
				$per_page = 40;
				$previous_btn = true;
				$next_btn = true;
				$first_btn = true;
				$last_btn = true;
				$start = $page * $per_page;
				//echo $condition;die;
				$sql = "SELECT State.*, country.countryname from cads_State State left Join cads_CountryState stateCountry ON State.id=stateCountry.stateid left Join cads_Country country ON country.id=stateCountry.countryid where State.status= 1 order by State.statename limit $start,$per_page";
				$states =  Yii::app()->db->createCommand($sql)->queryAll();
				$rrt=Yii::app()->db->createCommand()
					->select('count(*) cnt')
					->from('cads_State State')
					->leftJoin('cads_CountryState StateCountry', 'State.id = StateCountry.stateid')
					->leftJoin('cads_Country country', 'country.id=StateCountry.countryid')
					->where('State.status =:status', array(':status' => 1))
					->order('State.id ASC')
					->queryRow();
				
				$msg = $states;
				$data['allcnt']=$tot=$rrt['cnt'];
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
			if($tot<=40)
			{
				$msg .= "<div class='paginationRest'><ul>";
				$msg = $msg . "</ul></div>";   // Content for pagination
				echo $msg;
			}
			else
			{	
				$msg .= "<div class='paginationRest'><ul>";
			
				 // FOR ENABLING THE FIRST BUTTON
				if ($first_btn && $cur_page > 1):
					$msg .= "<li p='1' class='active'>First</li>";
				elseif($first_btn > 1):
					$msg .= "<li p='1' class='inactive'>First</li>";
				endif;
				
				 // FOR ENABLING THE PREVIOUS BUTTON
				if($previous_btn && $cur_page > 1):
					$pre = $cur_page - 1;
					$msg .= "<li p='$pre' class='active'>Previous</li>";
				elseif($previous_btn > 1):
					$msg .= "<li class='inactive'>Previous</li>";
				endif;
				
				for ($i = $start_loop; $i <= $end_loop; $i++):
					if ($cur_page == $i)
						$msg .= "<li p='$i' id='set$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
					else
						$msg .= "<li p='$i' id='set$i' class='active'>{$i}</li>";
				endfor;
				 // TO ENABLE THE NEXT BUTTON
				if($next_btn && $cur_page < $no_of_paginations): 
					$nex = $cur_page + 1;
					$msg .= "<li p='$nex' class='active'>Next</li>";
				elseif($next_btn  < $no_of_paginations):
					$msg .= "<li class='inactive'>Next</li>";
				endif;
				
				// TO ENABLE THE END BUTTON
				if ($last_btn && $cur_page < $no_of_paginations):
					$msg .= "<li p='$no_of_paginations' class='active'>Last</li>";
				elseif($last_btn < $no_of_paginations): 
					$msg .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
				endif;
					$msg = $msg . "</ul></div>";   // Content for pagination
					echo $msg;
			}
	}
		//echo "<pre>"; print_r($cities); die;
		$this->renderPartial('stateResults', array('data'=>$data,'states'=>$states));
	}
	public function actionCountryPaginationList()
	{
		if($_POST['page'])
			{
				$page = $_POST['page'];
				$cur_page = $page;
				$page -= 1;
				$per_page = 40;
				$previous_btn = true;
				$next_btn = true;
				$first_btn = true;
				$last_btn = true;
				$start = $page * $per_page;
				//echo $condition;die;
				$sql = "select Country.* FROM cads_Country Country LEFT JOIN cads_ContinentCountry CountryContinent ON Country.id = CountryContinent.countryid LEFT JOIN cads_Continent Continent ON  Continent.id=CountryContinent.continentid where Country.status =1 order by Country.countryname limit $start,$per_page";
				$country =  Yii::app()->db->createCommand($sql)->queryAll();
				$rrt=Yii::app()->db->createCommand()
					->select('count(*) cnt')
						->from('cads_Country Country')
						->Join('cads_ContinentCountry CountryContinent', 'Country.id = CountryContinent.countryid')
						->Join('cads_Continent Continent', 'Continent.id=CountryContinent.continentid')
						->where('Country.status =:status', array(':status' => 1))
						->order('Country.id ASC')
					->queryRow();
				
				$msg = $country;
				$data['allcnt']=$tot=$rrt['cnt'];
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
			if($tot<=40)
			{
				$msg .= "<div class='paginationcoun'><ul>";
				$msg = $msg . "</ul></div>";   // Content for pagination
				echo $msg;
			}
			else
			{	
				$msg .= "<div class='paginationcoun'><ul>";
			
				 // FOR ENABLING THE FIRST BUTTON
				if ($first_btn && $cur_page > 1):
					$msg .= "<li p='1' class='active'>First</li>";
				elseif($first_btn > 1):
					$msg .= "<li p='1' class='inactive'>First</li>";
				endif;
				
				 // FOR ENABLING THE PREVIOUS BUTTON
				if($previous_btn && $cur_page > 1):
					$pre = $cur_page - 1;
					$msg .= "<li p='$pre' class='active'>Previous</li>";
				elseif($previous_btn > 1):
					$msg .= "<li class='inactive'>Previous</li>";
				endif;
				
				for ($i = $start_loop; $i <= $end_loop; $i++):
					if ($cur_page == $i)
						$msg .= "<li p='$i' id='set$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
					else
						$msg .= "<li p='$i' id='set$i' class='active'>{$i}</li>";
				endfor;
				 // TO ENABLE THE NEXT BUTTON
				if($next_btn && $cur_page < $no_of_paginations): 
					$nex = $cur_page + 1;
					$msg .= "<li p='$nex' class='active'>Next</li>";
				elseif($next_btn  < $no_of_paginations):
					$msg .= "<li class='inactive'>Next</li>";
				endif;
				
				// TO ENABLE THE END BUTTON
				if ($last_btn && $cur_page < $no_of_paginations):
					$msg .= "<li p='$no_of_paginations' class='active'>Last</li>";
				elseif($last_btn < $no_of_paginations): 
					$msg .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
				endif;
					$msg = $msg . "</ul></div>";   // Content for pagination
					echo $msg;
			}
	}
		//echo "<pre>"; print_r($cities); die;
		$this->renderPartial('countryResults', array('data'=>$data,'country'=>$country));
	}
	
	public function actionAddcountry()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(isset($_POST['submit']))
			{
				
				if($_POST['id'] != '')
				{
					$model = Countries::model()->findbyPk($_POST['id']);	
					$model->countryname = $_POST['country'];
					$model->countrycode = $_POST['c_code'];
					
					if($model->save()):
						$update1 = Yii::app()->db->createCommand()
								->update('cads_ContinentCountry', 
									array('continentid' => $_POST['continent']),
									'countryid=:countryid',
									array(':countryid'=>$_POST['id'])
								);	
						Yii::app()->user->setFlash('message','Country Has been Updated successfully!');
						$this->redirect(array('Admin/locations/'));		
					else:
						Yii::app()->user->setFlash('message','Country is not Updated successfully. Please try Again');
						$this->redirect(array('Admin/locations'));
					endif;
				}

				$model=new Countries;
				$model->countryname=$_POST['country'];
				$model->countrycode=$_POST['c_code'];
				$model->status = 1;
				
				if($model->save())
				{
					$model1=new ContinentCountry;
					$model1->Countryid=$model->id;
					$model1->Continentid=$_POST['continent'];
					if($model1->save())
					{
						Yii::app()->user->setFlash('message','New Country has been Added successfully');
						$this->redirect(array('Admin/locations'));
					}
					else
					{
						Yii::app()->user->setFlash('message','Something went wrong please try again!');
						$this->redirect(array('Admin/addcountry'));
					}
				}
				else
				{
					Yii::app()->user->setFlash('message','Something went wrong please try again!');
					$this->redirect(array('Admin/addcountry'));
				}
			}
			
			$continents = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_Continent Continent')
						->order('Continent.continentname ASC')
						->queryAll();
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addcountry', array('continents'=>$continents));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionUpdatecountry($id)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{	
			$country = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_Country Country')
						->Join('cads_ContinentCountry CountryContinent', 'Country.id = CountryContinent.countryid')
						->Join('cads_Continent Continent', 'Continent.id=CountryContinent.continentid')
						->where('Country.id =:id', array(':id' => $id))
						->queryRow();
			
			$continents = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_Continent Continent')
						->order('Continent.continentname ASC')
						->queryAll();
						
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addcountry',array('continents'=>$continents,'country'=>$country));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionDeletecountry($id)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if($id!='')	{
				$update1 = Yii::app()->db->createCommand()
				->update('cads_Country', 
					array('status' => 0),
					'id=:id',
					array(':id'=>$id)
				);
				Yii::app()->user->setFlash('message','Country Has been Removed!');	
				$this->redirect(array('Admin/locations'));
			} else {
				$this->redirect(array('Admin/locations'));
			}
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionAddstate()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(isset($_POST['submit'])):
					$model=new States;
					$model->statename=$_POST['state'];
					$model->statecode=$_POST['s_code'];
					$model->status = 1;
					if($model->save())
					{
						$model1=new CountryState;
						$model1->Stateid=$model->id;
						$model1->Countryid = $_POST['country'];
						if($model1->save())
						{
							Yii::app()->user->setFlash('message','State has been Added successfully.');
							$this->redirect(array('Admin/locations'));
						}
						else
						{
							Yii::app()->user->setFlash('message','State is not Added successfully! Please try Again');
							$this->redirect(array('Admin/addstate'));
						}
					}
					else
					{
						Yii::app()->user->setFlash('message','State is not Added successfully! Please try Again');
						$this->redirect(array('Admin/addstate'));	
					}	
			endif;
			
			$countries = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_Country Country')
					->order('Country.countryname ASC')
					->Where('Country.status =:id', array(':id' => 1))
					->queryAll();	
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addstate',array('countries'=>$countries));	
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}	
	}
	
	public function actionUpdatestate($id)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(isset($_POST['submit']) && $_POST['id'] != '' ):
					$model = States::model()->findbyPk($_POST['id']);	
					$model->statename = $_POST['state'];
					$model->statecode = $_POST['s_code'];
					if($model->save()):
						
						$updateStateCountry = Yii::app()->db->createCommand()
								->update('cads_CountryState', 
									array('countryid' => $_POST['country']),
									'stateid=:stateid',
									array(':stateid'=>$_POST['id'])
								);
						Yii::app()->user->setFlash('message','State Has been Updated successfully!');		
						$this->redirect(array('Admin/locations'));
					else:
						Yii::app()->user->setFlash('message','State is not Updated successfully. Please try Again');
						$this->redirect(array('Admin/locations'));
					endif;
			endif;
			
			if($id != '')
			{
				$state = Yii::app()->db->createCommand()
					->select('State.*, country.id countryid')
					->from('cads_State State')
					->leftJoin('cads_CountryState StateCountry', 'State.id = StateCountry.Stateid')
					->leftJoin('cads_Country country', 'country.id=StateCountry.Countryid')
					->where('State.id =:id', array(':id' =>$id))
					->andWhere('State.status =:status', array(':status' => 1))
					->queryRow();	

				$countries = Yii::app()->db->createCommand()
							->select('*')
							->from('cads_Country')
							->Where('status =:id', array(':id' => 1))
							->queryAll();
							
				$this->renderPartial('//layouts/admin/header');
				$this->renderPartial('//layouts/admin/sidebar');
				$this->render('updatestate',array('countries'=>$countries,'state'=>$state));
				$this->renderPartial('//layouts/admin/footer');
			}
			else
			{
				$this->redirect(array('Admin/locations'));
			}
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
	public function actionDeletestate($id= Null )
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if($id!='')
			{
				$update1 = Yii::app()->db->createCommand()
								->update('cads_State', 
									array('status' => 0),
									'id=:id',
									array(':id'=>$id)
								);
				
				Yii::app()->user->setFlash('message','State Has been Removed!');	
				$this->redirect(array('Admin/locations'));
			}
			else
			{
				$this->redirect(array('Admin/locations'));
			}
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionAddcity()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(isset($_POST['submit']))
			{	
				$model=new Cities;
				$model->cityname=$_POST['city'];
				$model->city_zipcode=$_POST['city_zip'];
				$model->status = 1;
				if($model->save())
				{
					$model1=new CityState;
					$model1->cityid=$model->id;
					$model1->stateid=$_POST['state'];
					if($model1->save()) {
						Yii::app()->user->setFlash('message','City Has been Added Successfully!');		
						$this->redirect(array('Admin/locations'));
					}
					else
					{
						Yii::app()->user->setFlash('message','City Cannot be Added Successfully! Please Try Again');		
						$this->redirect(array('Admin/addcity'));	
					}
				}
				else
				{
					Yii::app()->user->setFlash('message','City Cannot be Added Successfully! Please Try Again');	
					$this->redirect(array('Admin/addcity'));	
				}	
			}
			$countries = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_Country Country')
						->Where('Country.status =:id', array(':id' => 1))
						->order('Country.id ASC')
						->queryAll();
						
						
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addcity',array('countries'=>$countries));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionUpdatecity($id)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(isset($_POST['submit']) && $_POST['id'] != '')
				{	
					$updatearr['id']=$_POST['id'];		
					$updatearr['cityname']=$_POST['city'];
					$updatearr['city_zipcode']=$_POST['city_zip'];
					$updatearr1['stateid']=$_POST['state'];
					
					$update = Yii::app()->db->createCommand()
								->update('cads_City', 
									$updatearr,
									'id=:id',
									array(':id'=>$_POST['id'])
								);
					$update1 = Yii::app()->db->createCommand()
								->update('cads_CityState', 
									$updatearr1,
									'cityid=:cityid',
									array(':cityid'=>$_POST['id'])
								);
					Yii::app()->user->setFlash('message','City Has been Added Successfully!');				
					$this->redirect(array('Admin/locations'));
				}		
			$city = Yii::app()->db->createCommand()
					->select('city.*, state.statename statename, state.id stateid, country.countryname countryname, country.id countryid')
					->from('cads_City city')
					->leftJoin('cads_CityState cityState', 'city.id=cityState.cityid')
					->leftJoin('cads_State state', 'state.id=cityState.stateid')
					->leftJoin('cads_CountryState stateCountry', 'cityState.stateid=stateCountry.stateid')
					->leftJoin('cads_Country country', 'country.id=stateCountry.countryid')
					->where('city.id =:id', array(':id' =>$id))
					->queryRow();				
			$countries = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_Country Country')
						->Where('Country.status =:id', array(':id' => 1))
						->order('Country.id DESC')
						->queryAll();
						
			$states = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_State State')
						->Where('State.status =:id', array(':id' => 1))
						->order('State.id DESC')
						->queryAll();	
					
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('updatecity',array('countries'=>$countries,'city'=>$city, 'states' =>$states));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Admin/login'));
		}
	}
	
	
	public function actionDeletecity($id = null)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if($id != '')
			{
				$update1 = Yii::app()->db->createCommand()
								->update('cads_City', 
									array('status' => 0),
									'id=:id',
									array(':id'=>$id)
								);
				Yii::app()->user->setFlash('message','City Has been Removed!');	
				$this->redirect(array('Admin/locations'));
			}
			else
			{
				$this->redirect(array('Admin/locations'));
			}
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	/* Start Change State Dropdown in City Add Form */
	public function actionGetstates($country_val)
	{	
		$states = Yii::app()->db->createCommand()
					->select('stateid')
					->from('cads_CountryState')
					->where(array('like', 'countryid', "$country_val"))
					->queryAll();
		if(count($states) > 0):
			foreach($states as $sta)
			{
				$stateid = $sta['stateid'];
				$statename[] = Yii::app()->db->createCommand()
						->select('*')
						->from('cads_State')
						->where(array('like', 'id', "$stateid"))
						->andWhere('status =:id', array(':id' => 1))
						->queryRow();
			}
			
			echo '<select name="state"  tabindex="-1" data-placeholder="Choose a State" class="span12 select2_category select2-offscreen">
			<option value="" selected="selected">Select State</option>';
			foreach($statename as $state)
			{
				$sid = $state['id'];
				$sname = $state['statename'];			
				echo '<option value="'.$sid.'">'.$sname.'</option>';				
			}
			echo '</select>';
		else:
			echo '<h4 style="color:red;">Not available any state associated with this country</h4>';
		endif;
		
	}
	/* End Change State Dropdown in City Add Form */
	
	/* Start Search country in location Country Section */
	public function actionCountrysearch($searchinput)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		
		$searchcountries = Yii::app()->db->createCommand()
					->select('countryname')
					->from('cads_Country')
					->where(array('like', 'countryname', "%$searchinput%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryAll();
		
		echo '<ul>';
		foreach($searchcountries as $country){
			$countryname = $country['countryname'];
			echo '<li id="'.$countryname.'" onclick="selectcountry(this);">'.$countryname.'</li>';
			}
		echo '</ul>';
	}
	
	/* Start Search country in location Country Section */
	public function actionAdvertismentsearch($searchinput)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		
		$searchcountries = Yii::app()->db->createCommand()
					->select('post_title')
					->from('cads_Ads')
					->where(array('like', 'post_title', "%$searchinput%"))
					->andWhere('ad_status =:status', array(':status' => 1))
					->queryAll();
		
		echo '<ul>';
		foreach($searchcountries as $country){
			$countryname = $country['post_title'];
			echo '<li id="'.$countryname.'" onclick="selectadvertisment(this);">'.$countryname.'</li>';
			}
		echo '</ul>';
	}
	
	public function actionSearchAdvertisment($search_input)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		$Adssearches = Yii::app()->db->createCommand()
					->select('post.id as postid,post.post_title,post.ad_flaged,city.cityname,user.login_email')
					->from('cads_Ads post')
					->leftJoin('cads_City city','post.city_id=city.id')
					->leftJoin('cads_Users user','post.user_id=user.id')	
					->where(array('like', 'post.post_title', "%$search_input%"))
					->andWhere('post.ad_status =:id', array(':id' => 1))
					->queryAll();	
		
		
	echo '<thead><tr><th>Ads</th><th>User Email</th><th>City</th><th>Update</th><th>Delete</th></tr></thead><tbody>';
		foreach($Adssearches as $adds){
			$adname = $adds['post_title'];
			$adid = $adds['postid'];
			$confirmmessage = "'Are you sure about this?'";
			$upurl = $base_url.'/Admin/editAd/'.$adid;
			$delurl = $base_url.'/Admin/deleteAd/'.$adid;
			$outputdisplay =  '<tr class=""><td>'.$adname;
			
			if($adds['ad_flaged']==1){
				
			$outputdisplay .= '<br/><font color="#d30000">This Ad is Flaged</font>';
			
			}
			$outputdisplay .= '</td><td>'.$adds['login_email'].'</td><td>'.$adds['cityname'].'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
			
			}
		$outputdisplay .= '</tbody>'; 
		echo $outputdisplay;
	
	}
	
	/*******************************section for category search******************************/
	
	public function actionCatsearch($searchinput)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		
		$searchcountries = Yii::app()->db->createCommand()
					->select('category_name')
					->from('cads_Categories')
					->where(array('like', 'category_name', "%$searchinput%"))
					->andWhere('category_status =:status', array(':status' => 1))
					->queryAll();
	 
		echo '<ul>';
		foreach($searchcountries as $country){
			$countryname = $country['category_name'];
			echo '<li id="'.$countryname.'" onclick="selectcat(this);">'.$countryname.'</li>';
			}
		echo '</ul>';
	}
	
	public function actionSearchCat($search_input)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		$Adssearches = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_Categories')
					->where(array('like', 'category_name', "%$searchinput%"))
					->andWhere('category_status =:status', array(':status' => 1))
					->queryAll();
		
		
		echo '<thead><tr><th>Category</th><th>Update</th><th>Delete</th></tr></thead><tbody>';
			foreach($Adssearches as $adds){
				$adname = $adds['category_name'];
				$adid = $adds['id'];
				$confirmmessage = "'Are you sure about this?'";
				$upurl = $base_url.'/Admin/updatecategory/'.$adid;
				$delurl = $base_url.'/Admin/deletecategory/'.$adid;
				echo '<tr class=""><td>'.$adname.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
				
				}
			echo '</tbody>'; 
		
		}
	
	/***************************************search for sub category*************************************************/
	public function actionSubcatsearch($searchinput)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		
		$searchcountries = Yii::app()->db->createCommand()
					->select('subcategory_name')
					->from('cads_Subcategories')
					->where(array('like', 'subcategory_name', "%$searchinput%"))
					->andWhere('subcategory_status =:status', array(':status' => 1))
					->queryAll();
	 
		echo '<ul>';
		foreach($searchcountries as $country){
			$countryname = $country['subcategory_name'];
			echo '<li id="'.$countryname.'" onclick="selectsubcat(this);">'.$countryname.'</li>';
			}
		echo '</ul>';
	}
	/*******************************section for page search******************************/
	
	public function actionPagesearch($searchinput)
	{
		
		$base_url = Yii::app()->getBaseUrl(true);
		
		$searchcountries = Yii::app()->db->createCommand()
					->select('page_name')
					->from('cads_pages')
					->where(array('like', 'page_name', "%$searchinput%"))
					->queryAll();
		
		echo '<ul>';
		foreach($searchcountries as $country){
			$countryname = $country['page_name'];
			echo '<li id="'.$countryname.'" onclick="selectpage(this);">'.$countryname.'</li>';
			}
		echo '</ul>';
	}
	
	public function actionSearchPages($search_input)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		$Adssearches = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_pages')
					->where(array('like', 'page_name', "%$search_input%"))
					->queryAll();
					
					
		echo '<thead><tr>
								<th class="hidden-phone">Page Name</th>
								<th class="hidden-phone">Page Title</th>
								<th class="hidden-phone">Page Meta Tag</th>
								<th class="hidden-phone">Page Key Word</th>
								<th>Action</th>
                              </tr>
                           </thead><tbody>';
			foreach($Adssearches as $adds){
				$adname = $adds['page_name'];
				$adid = $adds['id'];
				$confirmmessage = "'Are you sure about this?'";
				$upurl = $base_url.'/Page/updatepage/'.$adid;
				$delurl = $base_url.'/Page/deletepage/'.$adid;
				echo '<tr class=""><td class="hidden-phone" style="display:none;">'.$adid.'</td><td>'.$adname.'</td><td class="hidden-phone">'.$adds['page_title'].'</td><td class="hidden-phone">'.$adds['page_meta'].'</td><td class="hidden-phone">'.$adds['page_keyword'].'</td><td class="tableActs" style="width:15%"><a class="btn mini green-stripe" href="'.$upurl.'">Update</a><a class="btn mini red-stripe"  href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
				
				}
			echo '</tbody>'; 
		
		}
	
	
	public function actionSearchcountry($search_input)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		$countrysearches = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_Country')
					->where(array('like', 'countryname', "%$search_input%"))
					->andWhere('status =:id', array(':id' => 1))
					->queryAll();
		echo '<thead><tr><th>Country Name</th><th>Update</th><th>Delete</th></tr></thead><tbody>';
		foreach($countrysearches as $country){
			$countryname = $country['countryname'];
			$countryid = $country['id'];
			$confirmmessage = "'Are you sure about this?'";
			$upurl = $base_url.'/Admin/updatecountry/'.$countryid;
			$delurl = $base_url.'/Admin/deletecountry/'.$countryid;
			echo '<tr class=""><td>'.$countryname.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
			
			}
		echo '</tbody>';			
		
	}
	/* End Search country in location Country Section */
	
	/* Start Search State in State section by search input field */
	public function actionStatesearch($searchinput)
	{
		$searchstates = Yii::app()->db->createCommand()
					->select('statename')
					->from('cads_State')
					->where(array('like', 'statename', "%$searchinput%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryAll();
		echo '<ul>';
		foreach($searchstates as $state){
			$statename = $state['statename'];
			echo '<li id="'.$statename.'" onclick="selectstate(this);">'.$statename.'</li>';
		}
		echo '</ul>';			
	}
	
	public function actionSearchstate($state_search, $country_search = Null)
	{
		
		$base_url = Yii::app()->getBaseUrl(true);
		if($state_search != '' && $country_search == '')
		{
				
			$statesearches = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_State')
					->where(array('like', 'statename', "%$state_search%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryAll();
		echo '<tr><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr>';
		foreach($statesearches as $state){
			$statename = $state['statename'];
			$stateid = $state['id'];
			$countryid = Yii::app()->db->createCommand()
					->select('countryid')
					->from('cads_CountryState')
					->where(array('like', 'stateid', "%$stateid%"))
					->queryRow();
			$country_id = $countryid['countryid'];
			$countryname = Yii::app()->db->createCommand()
					->select('countryname')
					->from('cads_Country')
					->where(array('like', 'id', "$country_id"))
					->queryRow();
					$country_name = $countryname['countryname'];
			$confirmmessage = "'Are you sure about this?'";
			$delurl = $base_url.'/Admin/deletestate/'.$stateid;
			$upurl = $base_url.'/Admin/updatestate/'.$stateid;
			echo '<tr class=""><td>'.$statename.'</td><td>'.$country_name.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
			
			}
		echo '</tbody>';	
		}
		if($state_search == '' && $country_search != '')
		{
			
			$countrysearches = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_Country')
					->where(array('like', 'countryname', "%$country_search%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryRow();		
			$country_id = $countrysearches['id'];
			$statesearch = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_CountryState')
					->where(array('like', 'countryid', "%$country_id%"))
					->queryAll();
			
			foreach($statesearch as $state) {
				$state_id = $state['stateid'];
				 $statedetails[] = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_State')
					->where(array('like', 'id', "%$state_id%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryRow();
				}
				
				
			echo '<tr><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr>';
		foreach($statedetails as $state){
			$statename = $state['statename'];
			$stateid = $state['id'];
			$delurl = $base_url.'/Admin/deletestate/'.$stateid;
			$upurl = $base_url.'/Admin/updatestate/'.$stateid;
			$confirmmessage = "'Are you sure about this?'";
			echo '<tr class=""><td>'.$statename.'</td><td>'.$country_search.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
			
			}
		echo '</tbody>';		
				
		}
		if($state_search == '' && $country_search == '')
		{
			$statesearches = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_State state')
					->where('status =:id', array(':id' => 1))
					->order('state.id DESC')
					->queryAll();
			echo '<tr><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr>';
			foreach($statesearches as $state){
				$statename = $state['statename'];
				$stateid = $state['id'];
				$countryid = Yii::app()->db->createCommand()
						->select('countryid')
						->from('cads_CountryState')
						->where(array('like', 'stateid', "$stateid"))
						->queryRow();
				$country_id = $countryid['countryid'];
				$countryname = Yii::app()->db->createCommand()
						->select('countryname')
						->from('cads_Country')
						->where(array('like', 'id', "%$country_id%"))
						->andWhere('status =:status', array(':status' => 1))
						->queryRow();
						$country_name = $countryname['countryname'];
				$confirmmessage = "'Are you sure about this?'";
				$delurl = $base_url.'/Admin/deletestate/'.$stateid;
				$upurl = $base_url.'/Admin/updatestate/'.$stateid;
				echo '<tr class=""><td>'.$statename.'</td><td>'.$country_name.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
				
				}
			echo '</tbody>';		
				
		}
	}	
	/* End Search State in State section by search input field */
	
	/* Start Search City in Location City Section */
	public function actionCitysearch($searchinput)
	{
		$searchcities = Yii::app()->db->createCommand()
					->select('city.*,city.id  cityid,state.*,country.*')
					->from('cads_City city')
					->leftJoin('cads_CityState cityState', 'cityState.cityid=city.id')
					->leftJoin('cads_State state', 'state.id=cityState.stateid')
					->leftJoin('cads_CountryState stateCountry', 'state.id=stateCountry.stateid')
					->leftJoin('cads_Country country', 'country.id=stateCountry.countryid')
					->where(array('like', 'city.cityname', "$searchinput%"))
					->limit(25)
					->queryAll();
		
		/* echo '<pre>';
		print_r($searchcities);
		die; */
		/* $searchcities = Yii::app()->db->createCommand()
			->select('cityname')
			->from('cads_City')
			->where(array('like', 'cityname', "$searchinput%"))
			->andWhere('status =:status', array(':status' => 1))
			->queryAll(); */
		echo '<ul>';
		if($searchcities != null):	
			foreach($searchcities as $city)
			{
					$state = $city['statename'];
					$country = $city['countryname'];
					$cityid = $city['cityid'];
					$statenamearray =explode(" ",$state);
					$statenamestring = implode("_",$statenamearray);
					$countrynamearray =explode(" ",$country);
					$countrynamestring = implode("_",$countrynamearray);
				$cityname = $city['cityname'];
				echo '<li id="'.$city['cityid'].'" onclick="selectcityindi(this);">'.$cityname.', '.$state.', '.$country.'</li>';
			}
		else:
			echo '<li onclick="selectcity(this);">Result Not Found!</li>';
		endif;
		
		echo '</ul>';
	}
	
	
	
	public function actionSearchcity($cityinput , $stateinput = null , $countryinput = null) {
		$base_url = Yii::app()->getBaseUrl(true);
		if ($cityinput != '' && $stateinput == '' && $countryinput == ''){
		$searchcities = Yii::app()->db->createCommand()
			->select('*')
			->from('cads_City')
			->where(array('like', 'id', "%$cityinput%"))
			->andWhere('status =:status', array(':status' => 1))
			->queryRow();
		$cityname = $searchcities['cityname'];
		$city_id = $searchcities['id'];
		$searchstate = Yii::app()->db->createCommand()
			->select('stateid')
			->from('cads_CityState')
			->where(array('like', 'cityid', "%$city_id%"))
			->queryRow();
		$state_id = $searchstate['stateid'];
		$statename = Yii::app()->db->createCommand()
					->select('statename')
					->from('cads_State')
					->where(array('like', 'id', "%$state_id%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryRow();
		$state_name = 	$statename['statename'];	
		$countryid = Yii::app()->db->createCommand()
					->select('countryid')
					->from('cads_CountryState')
					->where(array('like', 'stateid', "%$state_id%"))
					->queryRow();	
		$country_id = $countryid['countryid'];
		$countryname = Yii::app()->db->createCommand()
					->select('countryname')
					->from('cads_Country')
					->where(array('like', 'id', "%$country_id%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryRow();
					//echo
		$country_name = $countryname['countryname'];
		$delurl = $base_url.'/Admin/deletecity/'.$city_id;
		$upurl = $base_url.'/Admin/updatecity/'.$city_id;
		$confirmmessage = "'Are you sure about this?'";
		echo '<thead><tr><th>City Name</th><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr></thead><tbody>';
		echo '<tr class=""><td>'.$cityname.'</td><td>'.$state_name.'</td><td>'.$country_name.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
		echo '</tbody>';
	}
	
	if ($cityinput == '' && $stateinput == '' && $countryinput == ''){
		$searchcitiess = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_City City')
					->where('status =:status', array(':status' => 1))
					->order('City.id DESC')
					->queryAll();
					
		if($searchcitiess != null):
			echo '<thead><tr><th>City Name</th><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr></thead><tbody>';
			foreach($searchcitiess as $searchcities):
				$cityname = $searchcities['cityname'];
			    $city_id = $searchcities['id'];
				$searchstate = Yii::app()->db->createCommand()
							->select('stateid')
							->from('cads_CityState')
							->where(array('like', 'cityid', "%$city_id%"))
							->queryRow();
				$state_id = $searchstate['stateid'];
				$statename = Yii::app()->db->createCommand()
							->select('statename')
							->from('cads_State')
							->where(array('like', 'id', "%$state_id%"))
							->andWhere('status =:status', array(':status' => 1))
							->queryRow();
				$state_name = 	$statename['statename'];	
				$countryid = Yii::app()->db->createCommand()
							->select('countryid')
							->from('cads_CountryState')
							->where(array('like', 'stateid', "%$state_id%"))
							->queryRow();	
				$country_id = $countryid['countryid'];
				$countryname = Yii::app()->db->createCommand()
							->select('countryname')
							->from('cads_Country')
							->where(array('like', 'id', "%$country_id%"))
							->andWhere('status =:status', array(':status' => 1))
							->queryRow();
				$country_name = $countryname['countryname'];
				$delurl = $base_url.'/Admin/deletecity/'.$city_id;
				$upurl = $base_url.'/Admin/updatecity/'.$city_id;
				$confirmmessage = "'Are you sure about this?'";
				
				echo '<tr class=""><td>'.$cityname.'</td><td>'.$state_name.'</td><td>'.$country_name.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
			endforeach;
			echo '</tbody>';
		endif;	
	}
	
	if ($stateinput != '' && $cityinput == '' && $countryinput == ''){
		$searchstate = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_State')
					->where(array('like', 'statename', "%$stateinput%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryRow();
		$state_id = $searchstate['id'];
		$cityids = Yii::app()->db->createCommand()
					->select('cityid')
					->from('cads_CityState')
					->where(array('like', 'stateid', "%$state_id%"))
					->queryAll();
		$countryid = Yii::app()->db->createCommand()
					->select('countryid')
					->from('cads_CountryState')
					->where(array('like', 'stateid', "%$state_id%"))
					->queryRow();	
		$country_id = $countryid['countryid'];
		$countryname = Yii::app()->db->createCommand()
					->select('countryname')
					->from('cads_Country')
					->where(array('like', 'id', "%$country_id%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryRow();
		$country_name = $countryname['countryname'];
		foreach($cityids as $city)
		{
			$city_id = $city['cityid'];
			$citydetails[] = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_City')
					->where(array('like', 'id', "%$city_id%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryRow();
		}
		
		$delurl = $base_url.'/Admin/deletecity/'.$city_id;
		$upurl = $base_url.'/Admin/updatecity/'.$city_id;
		//echo $upurl; die;
		$confirmmessage = "'Are you sure about this?'";
		echo '<thead><tr><th>City Name</th><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr></thead><tbody>';
		foreach($citydetails as $detail)
		{
			$cityname = $detail['cityname'];
			$city_id = $detail['id'];
			echo '<tr class=""><td>'.$cityname.'</td><td>'.$stateinput.'</td><td>'.$country_name.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
		}
		echo '</tbody>';
	}
	
	if ($countryinput != '' && $stateinput == '' && $cityinput == ''){
		$countryid = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_Country')
					->where(array('like', 'countryname', "%$countryinput%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryRow();
		$country_id = $countryid['id'];
		$stateids = Yii::app()->db->createCommand()
					->select('stateid')
					->from('cads_CountryState')
					->where(array('like', 'countryid', "%$country_id%"))
					->queryAll();
		foreach($stateids as $sid)
		{
			$state_id = $sid['stateid'];
			$cityids[] = Yii::app()->db->createCommand()
					->select('cityid')
					->from('cads_CityState')
					->where(array('like', 'stateid', "%$state_id%"))
					->queryAll();
		}
		foreach($cityids as $cids)
		{
			foreach($cids as $cid)
			{
				$city_id[] = $cid['cityid'];
			}
			
		}
		$i=0;
		foreach($city_id as $c_id)
		{
			$citydetails[] = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_City')
					->where(array('like', 'id', "%$c_id%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryRow();
			$stateid = Yii::app()->db->createCommand()
					->select('stateid')
					->from('cads_CityState')
					->where(array('like', 'cityid', "%$c_id%"))
					->queryRow();
			$state_id = $stateid['stateid'];	
			$statename = Yii::app()->db->createCommand()
					->select('statename')
					->from('cads_State')
					->where(array('like', 'id', "%$state_id%"))
					->andWhere('status =:status', array(':status' => 1))
					->queryRow();
			$citydetails[$i]['statename'] = $statename['statename'];	
			$i++;
		}
		$confirmmessage = "'Are you sure about this?'";
		$delurl = $base_url.'/Admin/deletecity/'.$city_id;
		$upurl = $base_url.'/Admin/updatecity/'.$city_id;
		echo '<thead><tr><th>City Name</th><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr></thead><tbody>';
		foreach($citydetails as $detail)
		{
			$cityname = $detail['cityname'];
			$city_id = $detail['id'];
			$statename = $detail['statename'];
			echo '<tr class=""><td>'.$cityname.'</td><td>'.$statename.'</td><td>'.$countryinput.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
		}
		echo '</tbody>';
	}
	
	}
	/* End Search City in Location City Section */
	public function actionPostAds()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$criteria=new CDbCriteria();	
			//$criteria->condition = 'ad_status = 1';
			if(isset($_GET['q'])){
				$q = $_GET['q'];
				$criteria->compare('post_title', $q, true);
			}
			$criteria->order = 'updated_date DESC';
			$count=Ads::model()->count($criteria);
			$pages=new CPagination($count);
			
			$pages->pageSize=5;
			$pages->applyLimit($criteria);
		
			$posts=Ads::model()->with('Cities','Users')->findAll($criteria);
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('postads',array('posts'=>$posts,'pages' => $pages));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{			
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actionApproveAd($id = null){
		
		$base_url = Yii::app()->getBaseUrl(true);
		$adDetail = Yii::app()->db->createCommand()
			->select('email,contact_person')
			->from('cads_Ads')
			->andWhere('id =:status', array(':status' => $id))
			->queryRow();
		$currentdate = date('Y-m-d h:i:s');
		$command = Yii::app()->db->createCommand();		
		if($command->update('cads_Ads', array('updated_date'=>$currentdate,'ad_status'=>1), 'id=:id', array(':id'=>$id)) == true):
		
			// Multiple recipients
				$to = $adDetail['email']; // note the comma
				// Subject
				$subject = 'Tripken Ads';

				// Message
				/* $message = '<html>
							<head>
								<title>Regarding Ad Approval</title>
							</head>
							<body>
							  <p>Dear '.$adDetail['contact_person'].',<br/><br/>Your Ad has been Approved on ads.tripken.com successfully. </p>
							   <p>View your Ad by <a target="_blank" href="'.$base_url.'/Site/Adsdetail/'.$id.'">Click Here</a>  </p>
							  <p>Thanks for Connecting with Us. </p>
							  <br />
							   <p>Thanks & Regards<br/> Tripken Team.</p>
							</body>
							</html>'; */
				$message = '
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
										<p>Dear '.$adDetail['contact_person'].',<br/><br/>Your Ad has been Approved on ads.tripken.com successfully. </p>
									    <p>View your Ad by <a target="_blank" href="'.$base_url.'/Site/Adsdetail/'.$id.'">Click Here</a>  </p>
									    <p>Thanks for Connecting with Us. </p>
									    <br />
									   <p>Thanks & Regards<br/> Tripken Team.</p>
									</td>
							  </tr>						  
							  <tr>
									<td align="left" valign="top" height="90" style="background-color:#1baea6;">
										<table width="100%" cellspacing="0" cellpadding="0">
											<tr>
												<td align="left" valign="top" style="float: left;text-align:center;width: 100%;padding: 23px 0 0 0;margin: 0px auto;">
													<ul class="foot-link">
													<li style="display:inline-block;"><a style="color:#fff;" target="_blank" href="https://blog.tripken.com">Blog</a> </li>
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
												<p style="color:#fff;" class="copy">Copyright © 2017 ads.tripken.com, LLC. All rights reserved.</p>
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
				$headers[] = 'To: '.$adDetail['email'].' <'.$adDetail['email'].'>';
				$headers[] = 'From: Tripken Team <angel.tripken@gmail.com>';
				// Mail it
				if(mail($to, $subject, $message, implode("\r\n", $headers))):
					Yii::app()->user->setFlash('success', "Ad has been Approved Successfully.");
					$this->redirect(array('Admin/Unapproved_ads'));
				else:
					Yii::app()->user->setFlash('success', "Something went Wrong. Please try again");
					$this->redirect(array('Admin/Unapproved_ads'));
				endif;	
		else:
			Yii::app()->user->setFlash('success', "Something went Wrong. Please try again");
			$this->redirect(array('Admin/Unapproved_ads'));
		endif; 	
	}
	
	public function actionUnapproved_ads()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			$criteria=new CDbCriteria();
			$criteria->condition = 'ad_status = 0';			
			if(isset($_GET['q'])){
				$q = $_GET['q'];
				$criteria->compare('post_title', $q, true);
			}
			$criteria->order = 'updated_date DESC';
			$count=Ads::model()->count($criteria);
			$pages=new CPagination($count);
			
			$pages->pageSize=5;
			$pages->applyLimit($criteria);
			
			$posts=Ads::model()->with('Cities','Users')->findAll($criteria);
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('unpostads',array('posts'=>$posts,'pages' => $pages));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{			
			$this->redirect(array('Admin/login'));
		}
	}
	
	public function actioneditAd($id=NULL)
	{
		ini_set("memory_limit",-1);
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(isset($_POST['submit'])) {
				
				$Ads = Ads::model()->findbyPk($id);
				
				$multi_cat			= $_POST['multi_cat'];
				if(isset($_POST['subcategorydrop'])){
					$multi_subcat	    = $_POST['subcategorydrop'];
				}
								
				$Ads->post_title	= $_POST['ad_title'];
				$Ads->url	= $_POST['url'];
				$Ads->description	= $_POST['elm1'];
				$Ads->latitude  = $_POST['r_lattitude'];
				$Ads->longitude = $_POST['r_longitude'];
				$Ads->phone			= $_POST['phone'];
				$Ads->city_id		= $_POST['city_id'];
				
				/* $Ads->contact_person = $_POST['fname'];
				$Ads->phone = $_POST['phone'];
				$Ads->postal_code = $_POST['postalcode']; */
				$Ads->updated_date	= date('Y-m-d h:i:s');
				
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
								echo "in if"; die;
								Yii::app()->user->setFlash('error', 'Image cannot be update. Please try with .jpeg,.png image type extensions and below 2Mb size');
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
				
				//die;
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
					//$Adselected_cat = AdSelected::model()->findAllByAttributes(array('ad_id'=>$id));
										
					/* Selected Ads Category and Subcategory relation data saved */
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
					/* Selected Ads Category and Subcategory relation data saved */
					
					Yii::app()->user->setFlash('success', "Ads Details are Updated.");
					$requestPageType= $_POST['requestPageType'];
					if($requestPageType == 1){
						$this->redirect(array('Admin/PostAds'));
					}else{
						$this->redirect(array('Admin/Unapproved_ads'));
					}
				else:
					Yii::app()->user->setFlash('success', "Sorry Something missing. Please try again..");
						$redirecturl = 'Admin/editAd/'.$id;
					$this->redirect(array($redirecturl));
				endif;
				
			} elseif($id!=''){	
			
				$imgs = AdsImages::model()->findAll('ad_id=:adid', array(':adid'=>$id));
				$AdSelected = AdSelected::model()->findAll('ad_id=:adid', array(':adid'=>$id));
				
				
				
				/* Check Selected Categories Array */
				$selectedCategories = AdSelected::model()->findAll('ad_id=:adid', array(':adid'=>$id));
				if(!empty($selectedCategories)){
					foreach($selectedCategories as $Slectdcat){
						$Adsselectedcat[]=$Slectdcat['category_id'];
						$AdsselectedSubcat[]=$Slectdcat['subcategory_id'];
					}
				}else{
					$Adsselectedcat= array();
					$AdsselectedSubcat =array();
				}
				/* Check Selected Categories Array */
			
				$Categories = Categories::model()->findAll();
				$SubCategories = Subcategories::model()->findAll();
				$Adscity = Cities::model()->findAll();
				$ad = Ads::model()->findByPk($id);
				
				$postStatus = $ad['ad_status'];
				
				
				$cusctyid = $ad['city_id'];
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
			
				$this->renderPartial('//layouts/admin/header');
				$this->renderPartial('//layouts/admin/sidebar');
				$this->render('editAd',array('pageStateType' => $postStatus, 'ad'=>$ad,'AdSelected'=>$AdSelected,'imgs'=>$imgs,'Categories'=>$Categories,'Adsselectedcat'=>$Adsselectedcat,'AdsselectedSubcat'=>$AdsselectedSubcat,'SubCategories'=>$SubCategories,'Adscity'=>$Adscity,'location'=>$location));
				$this->renderPartial('//layouts/admin/footer');
			} else {				
				$this->redirect(array('Admin/PostAds'));
			}
		}
		else{
			$this->redirect(array('Admin/login'));	 
		}
	}
	public function actionDeleteAdsimage($id = Null){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1):
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
	public function actiondeleteAd($id = Null){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1):
		if($id!=''):
			
			$deleteimg = AdsImages::model()->findAll('ad_id=:adid', array(':adid'=>$id));
			$deleteAd = Ads::model()->findByPk($id);
			//echo "<pre>"; print_r($deleteimg); die;
			if(!empty($deleteimg))
			{
				foreach($deleteimg as $img)
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
			//echo "<pre>"; print_r($deleteimg); die;
			
			//$deleteCategory->status = 0;
			if($deleteAd->delete() == true):
				AdsImages::model()->deleteAll('ad_id=:ad_id', array(':ad_id' => $id));
				AdSelected::model()->deleteAll('ad_id=:ad_id', array(':ad_id' => $id));
				Messages::model()->deleteAll('ads_id=:ad_id', array(':ad_id' => $id));
				Yii::app()->user->setFlash('success', "Ad has been deleted Successfully.");
				$this->redirect(array('Admin/PostAds'));
			else:
				Yii::app()->user->setFlash('success', "Sorry Ad cannot be deleted.");
				$this->redirect(array('Admin/PostAds'));
			endif;
		
		else:
			$this->redirect(array('Admin/PostAds'));
		endif;
		else:
			$this->redirect(array('Admin/login'));	 
		endif;
	}
	public function actiondeleteUnpostAd($id = Null){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1):
		if($id!=''):
			
			$deleteimg = AdsImages::model()->findAll('ad_id=:adid', array(':adid'=>$id));
			$deleteAd = Ads::model()->findByPk($id);
			//echo "<pre>"; print_r($deleteimg); die;
			if(!empty($deleteimg))
			{
				foreach($deleteimg as $img)
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
			//echo "<pre>"; print_r($deleteimg); die;
			
			//$deleteCategory->status = 0;
			if($deleteAd->delete() == true):
				AdsImages::model()->deleteAll('ad_id=:ad_id', array(':ad_id' => $id));
				AdSelected::model()->deleteAll('ad_id=:ad_id', array(':ad_id' => $id));
				Messages::model()->deleteAll('ads_id=:ad_id', array(':ad_id' => $id));
				Yii::app()->user->setFlash('success', "Ad has been deleted Successfully.");
				$this->redirect(array('Admin/Unapproved_ads'));
			else:
				Yii::app()->user->setFlash('success', "Sorry Ad cannot be deleted.");
				$this->redirect(array('Admin/Unapproved_ads'));
			endif;
		
		else:
			$this->redirect(array('Admin/Unapproved_ads'));
		endif;
		else:
			$this->redirect(array('Admin/login'));	 
		endif;
	}
	public function actionMessages(){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1):
			
			$criteria=new CDbCriteria();
			
			//$criteria->condition = 'category_status =:state';
			
			//$criteria->params = array(':state' => 1);
			if(isset($_GET['q'])){
				$q = $_GET['q'];
				$criteria->compare('message_text', $q, true);
			}
			$criteria->order = 'id DESC';
			$count=Messages::model()->count($criteria);
			$pages=new CPagination($count);
			// results per page
			$pages->pageSize=5;
			$pages->applyLimit($criteria);
			
			$allmessage=Messages::model()->findAll($criteria);
			//print_r($allmessage); die;
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('messages', array(
			'allmessage' => $allmessage,
			 'pages' => $pages
			));
			$this->renderPartial('//layouts/admin/footer');
		else:
			$this->redirect(array('Admin/login'));
		endif;
	}
	public function actionDeletemessage($id = Null){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1):
			$deleteMessages = Messages::model()->findByPk($id);
			//$deleteCategory->status = 0;
			if($deleteMessages->delete() == true):
				Yii::app()->user->setFlash('success', "Messages has been deleted Successfully.");
				$this->redirect(array('Admin/messages'));
			else:
				Yii::app()->user->setFlash('success', "Sorry Messages cannot be deleted.");
				$this->redirect(array('Admin/messages'));
			endif;
		else:
			$this->redirect(array('Admin/login'));	 
		endif;
	}
	public function actionViewmessage($id = Null){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1):
			$ViewMessages = Messages::model()->findByPk($id);
			
			$Adsownerdetails = Yii::app()->db->createCommand()
			->select('U.login_email,UM.firstname,UM.lastname,UM.user_contact')
			->from('cads_Users U')
			->leftJoin('cads_UsersMeta UM','U.id=UM.user_id')
			->Where('U.id =:userid', array(':userid' => $ViewMessages['owner_id']))
			->queryRow();
			
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('Viewmessage', array('ViewMessages'=>$ViewMessages,'Adsownerdetails'=>$Adsownerdetails));
			$this->renderPartial('//layouts/admin/footer');
		else:
			$this->redirect(array('Admin/login'));	 
		endif;
	}
	public function actionOrders(){
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			
			$criteria=new CDbCriteria();
			
			/* $criteria->condition = 'status =:state';
			
			$criteria->params = array(':state' => 1); */
			if(isset($_GET['q'])){
				$q = $_GET['q'];
				$criteria->compare('id', $q, true);
			}
			$criteria->order = 'id ASC';
			$count=Orders::model()->count($criteria);
			$pages=new CPagination($count);
			// results per page
			$pages->pageSize=10;
			$pages->applyLimit($criteria);
			
			$OrderList=Orders::model()->findAll($criteria);
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('orderlist',array('OrderList'=>$OrderList,'pages' => $pages));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
	public function actionDeleteorder($id = null){
	
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(Yii::app()->db->createCommand()
			->delete('cads_Orders', 'id = '.$id)):
					
					Yii::app()->user->setFlash('register','Order has been Deleted Successfully.');
					$this->redirect(array('Admin/orders'));
			else:
				Yii::app()->user->setFlash('error','Some error Occured.');
				$this->redirect(array('Admin/orders'));
			endif;
		}
		
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
		
	public function actionUpdateorder($id = null){
	
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
		
			if(isset($_POST['update'])){
			//$id = $_POST['id'];	
			$update['order_status']=$_POST['order_status'];
					
			$update = Yii::app()->db->createCommand()
				->update('cads_Orders', 
					$update,
					'id=:idpack',
					array(':idpack'=>$id)
				);
				
				
				if($update){
					Yii::app()->user->setFlash('register','Order has been updated Successfully.');
					$this->redirect(array('Admin/updateorder/'.$id));
				}
				else{
					Yii::app()->user->setFlash('error','Order could not be updated..!!');
					$this->redirect(array('Admin/updateorder/'.$id));
				}				
			
			}
			else{
				
			$criteria=new CDbCriteria();
			$count = Orders::model()->findbyPk($id);
			
			$criteria=new CDbCriteria();
			$count = Orders::model()->findbyPk($id);
			$orderData = Orders::model()->findbyPk($id);
				
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('updateorder',array('orderData'=>$orderData));
			$this->renderPartial('//layouts/admin/footer');
			
			}
		}
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
}
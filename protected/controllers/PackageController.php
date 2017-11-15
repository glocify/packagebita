<?php
class PackageController extends CController
{
	
	
	public function actionPackagesearch($searchinput)
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
	
	
	
	public function actionSearchpackage($search_input)
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
								<a href="'.$base_url.'/Package/updatepackage/'.$userid.'" class="btn mini green-stripe">Update</a> 
								<a onclick="return confirm('.$confirmmessage.');" href="'.$base_url.'/Package/deletepackage/'.$userid.'" class="btn mini red-stripe">Delete</a>
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
	
	public function actionPackagelist()
	{		
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
			$count=Packages::model()->count($criteria);
			$pages=new CPagination($count);
			// results per page
			$pages->pageSize=10;
			$pages->applyLimit($criteria);
			
			$Packagelist=Packages::model()->findAll($criteria);
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('packagelist',array('packagelist'=>$Packagelist,'pages' => $pages));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
	
	
	public function actionAddpackage()
	{		
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			
				if(isset($_POST['submit']))
				{
					
					
					///Check Package
					$res = Yii::app()->db->createCommand()->select('id')->from('cads_Packages')->where('name =:name', array(':name' => $_POST['name']))->queryRow();
					if(empty($res))
					{
						$model=new Packages;
						$model->name= $_POST['name'];
						$model->description= $_POST['description'];
						$model->en_key = $this->generateRandomString(15);
						
						$extensions = array("jpeg","jpg","png");
						$file_name = $_FILES['packageimage']['name'];
						$file_size =$_FILES['packageimage']['size'];
						$file_tmp =$_FILES['packageimage']['tmp_name'];
						$file_type=$_FILES['packageimage']['type'];
						$fileext=explode('.',$file_name);
						$count = count($fileext);
						$count1 = $count-1;
						$fileext1=$fileext[$count1];  
						$file_ext=strtolower($fileext1);
						
						
						if(in_array($file_ext,$extensions ) === false){
							
							Yii::app()->user->setFlash('error', "Please select valid image.");
							$this->redirect(array('Package/addpackage'));
						} else {
							$newfileName = time().$_FILES['packageimage']['name'];
									move_uploaded_file($file_tmp,"images/packagesimage/".$newfileName);
						}
						if(!empty($newfileName)){
							$model->image = $newfileName;
						}
						$model->price= $_POST['price'];
						$model->click= $_POST['clicks'];
						$model->status= $_POST['status'];
						if($model->save())
						{
								Yii::app()->user->setFlash('register','Package has been added Successfully.');
								$this->redirect(array('Package/packagelist'));
						}
						else
						{
								Yii::app()->user->setFlash('error','Some error Occured.');
								$this->redirect(array('Package/addpackage'));
						}
					}
					else
					{
						Yii::app()->user->setFlash('error','Package with this name already exist.. Try again wih different package name..!!');
						$this->redirect(array('Package/addpackage'));
					}
				
				}
			
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addpackage');
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	public function actionDeletepackage($id){
	
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if(Yii::app()->db->createCommand()
			->delete('cads_Packages', 'id = '.$id)):
					
					Yii::app()->user->setFlash('register','Package Deleted Successfully.');
					$this->redirect(array('Package/packagelist'));
			else:
				Yii::app()->user->setFlash('error','Some error Occured.');
				$this->redirect(array('Package/packagelist'));
			endif;
		}
		
		else
		{
			$this->redirect(array('Site/Signin'));
		}
	}
		
	public function actionUpdatepackage($id){
		
			if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
			{
			
				if(isset($_POST['update'])){
				$id = $_POST['id'];	
				//echo "<pre>"; print_r($_POST); die;

				$update['name']=$_POST['name'];
				$update['description']=$_POST['description'];
				$update['price']=$_POST['price'];
				
				
					$model = Packages::model()->findbyPk($id);
					$oldImageName =  $model->image;
					
					
					if(isset($_FILES['packageimage']['name']) && $_FILES['packageimage']['name'] !== ''){			
						
						$extensions = array("jpeg","jpg","png");
						$file_name = $_FILES['packageimage']['name'];
						$file_size =$_FILES['packageimage']['size'];
						$file_tmp =$_FILES['packageimage']['tmp_name'];
						$file_type=$_FILES['packageimage']['type'];
						$fileext=explode('.',$file_name);
						
						$count = count($fileext);
						$count1 = $count-1;
						$fileext1=$fileext[$count1];  
						$file_ext=strtolower($fileext1);  
						
						if(in_array($file_ext,$extensions ) === false){
							Yii::app()->user->setFlash('error', "Please select valid image.");
							$this->redirect(array('Package/updatepackage/'.$id));
						}  else {
							$newfileName = time().$_FILES['packageimage']['name'];
							move_uploaded_file($file_tmp,"images/packagesimage/".$newfileName);
						}
						if(!empty($newfileName)){
							$update['image']= $newfileName;
							unlink("images/packagesimage/".$oldImageName);
						}				
					}
				
				$update['click']=$_POST['clicks'];
				$update['status']=$_POST['status'];
						
				$update = Yii::app()->db->createCommand()
					->update('cads_Packages', 
						$update,
						'id=:idpack',
						array(':idpack'=>$id)
					);
					
					
					if($update){
						Yii::app()->user->setFlash('register','Package has been updated Successfully.');
						$this->redirect(array('Package/updatepackage/'.$id));
					}
					else{
						Yii::app()->user->setFlash('error','Package could not be updated..!!');
						$this->redirect(array('Package/updatepackage/'.$id));
						
					}				
				
				}
				else{
					
				$criteria=new CDbCriteria();
				$count = Packages::model()->findbyPk($id);
				
				$criteria=new CDbCriteria();
				$count = Packages::model()->findbyPk($id);
				$packageData = Packages::model()->findbyPk($id);
					
				$this->renderPartial('//layouts/admin/header');
				$this->renderPartial('//layouts/admin/sidebar');
				$this->render('updatepackage',array('packageData'=>$packageData));
				$this->renderPartial('//layouts/admin/footer');
				
				}
			}
			else
			{
				$this->redirect(array('Site/Signin'));
			}
		}
	
}

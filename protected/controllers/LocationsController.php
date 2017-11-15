<?php
class LocationController extends CController
{
	public function actionLocations()
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			echo "gdshfgdshgf"; die;
			$cities = Yii::app()->db->createCommand()
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
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('User/login'));
		}
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
								->update('trpCountryContinent', 
									array('continentid' => $_POST['continent']),
									'countryid=:countryid',
									array(':countryid'=>$_POST['id'])
								);	
						Yii::app()->user->setFlash('message','Country Has been Updated successfully!');
						$this->redirect(array('Location/locations'));		
					else:
						Yii::app()->user->setFlash('message','Country is not Updated successfully. Please try Again');
						$this->redirect(array('Location/locations'));
					endif;
				}

				$model=new Countries;
				$model->countryname=$_POST['country'];
				$model->countrycode=$_POST['c_code'];
				$model->status = 1;
				if($model->save())
				{
					$model1=new CountryContinent;
					$model1->countryid=$model->id;
					$model1->continentid=$_POST['continent'];
					if($model1->save())
					{
						Yii::app()->user->setFlash('message','New Country has been Added successfully');
						$this->redirect(array('Location/locations'));
					}
					else
					{
						Yii::app()->user->setFlash('message','Something went wrong please try again!');
						$this->redirect(array('Location/addcountry'));
					}
				}
				else
				{
					Yii::app()->user->setFlash('message','Something went wrong please try again!');
					$this->redirect(array('Location/addcountry'));
				}
			}
			
			$continents = Yii::app()->db->createCommand()
						->select('*')
						->from('trpContinent Continent')
						->order('Continent.continentname ASC')
						->queryAll();
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addcountry', array('continents'=>$continents));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
	public function actionUpdatecountry($id)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{	
			$country = Yii::app()->db->createCommand()
						->select('*')
						->from('trpCountry Country')
						->Join('trpCountryContinent CountryContinent', 'Country.id = CountryContinent.countryid')
						->Join('trpContinent Continent', 'Continent.id=CountryContinent.continentid')
						->where('Country.id =:id', array(':id' => $id))
						->queryRow();
			
			$continents = Yii::app()->db->createCommand()
						->select('*')
						->from('trpContinent Continent')
						->order('Continent.continentname ASC')
						->queryAll();
						
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addcountry',array('continents'=>$continents,'country'=>$country));
			$this->renderPartial('//layouts/admin/footer');
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
	public function actionDeletecountry($id)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if($id!='')
			{
						$update1 = Yii::app()->db->createCommand()
								->update('trpCountry', 
									array('status' => 0),
									'id=:id',
									array(':id'=>$id)
								);
					Yii::app()->user->setFlash('message','Country Has been Removed!');	
				$this->redirect(array('Location/locations'));
			}
			else
			{
				$this->redirect(array('Location/locations'));
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
								->update('trpState', 
									array('status' => 0),
									'id=:id',
									array(':id'=>$id)
								);
				
				Yii::app()->user->setFlash('message','State Has been Removed!');	
				$this->redirect(array('Location/locations'));
			}
			else
			{
				$this->redirect(array('Location/locations'));
			}
		}
		else
		{
			$this->redirect(array('User/login'));
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
						$model1=new StateCountry;
						$model1->stateid=$model->id;
						$model1->countryid = $_POST['country'];
						if($model1->save())
						{
							Yii::app()->user->setFlash('message','State has been Added successfully.');
							$this->redirect(array('Location/locations'));
						}
						else
						{
							Yii::app()->user->setFlash('message','State is not Added successfully! Please try Again');
							$this->redirect(array('Location/addstate'));
						}
					}
					else
					{
						Yii::app()->user->setFlash('message','State is not Added successfully! Please try Again');
						$this->redirect(array('Location/addstate'));	
					}	
			endif;
			
			$countries = Yii::app()->db->createCommand()
					->select('*')
					->from('trpCountry Country')
					->order('Country.countryname ASC')
					->Where('Country.status =:id', array(':id' => 1))
					->queryAll();	
			$this->renderPartial('//layouts/admin/header');
			$this->renderPartial('//layouts/admin/sidebar');
			$this->render('addstate',array('countries'=>$countries));	
		}
		else
		{
			$this->redirect(array('User/login'));
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
								->update('trpStateCountry', 
									array('countryid' => $_POST['country']),
									'stateid=:stateid',
									array(':stateid'=>$_POST['id'])
								);
						Yii::app()->user->setFlash('message','State Has been Updated successfully!');		
						$this->redirect(array('Location/locations'));
					else:
						Yii::app()->user->setFlash('message','State is not Updated successfully. Please try Again');
						$this->redirect(array('Location/locations'));
					endif;
			endif;
			
			if($id != '')
			{
				$state = Yii::app()->db->createCommand()
					->select('State.*, country.id countryid')
					->from('trpState State')
					->leftJoin('trpStateCountry StateCountry', 'State.id = StateCountry.stateid')
					->leftJoin('trpCountry country', 'country.id=StateCountry.countryid')
					->where('State.id =:id', array(':id' =>$id))
					->andWhere('State.status =:id', array(':id' => 1))
					->queryRow();	

				$countries = Yii::app()->db->createCommand()
							->select('*')
							->from('trpCountry')
							->Where('status =:id', array(':id' => 1))
							->queryAll();
							
				$this->renderPartial('//layouts/admin/header');
				$this->renderPartial('//layouts/admin/sidebar');
				$this->render('updatestate',array('countries'=>$countries,'state'=>$state));
				$this->renderPartial('//layouts/admin/footer');
			}
			else
			{
				$this->redirect(array('Location/locations'));
			}
		}
		else
		{
			$this->redirect(array('User/login'));
		}
	}
	
	public function actionDeletecity($id = null)
	{
		if(isset(Yii::app()->session['userid']) && Yii::app()->session['userid'] == 1)
		{
			if($id != '')
			{
				$update1 = Yii::app()->db->createCommand()
								->update('trpCity', 
									array('status' => 0),
									'id=:id',
									array(':id'=>$id)
								);
				Yii::app()->user->setFlash('message','City Has been Removed!');	
				$this->redirect(array('Location/locations'));
			}
			else
			{
				$this->redirect(array('Location/locations'));
			}
		}
		else
		{
			$this->redirect(array('User/login'));
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
				$model->status = 1;
				if($model->save())
				{
					$model1=new CityState;
					$model1->cityid=$model->id;
					$model1->stateid=$_POST['state'];
					if($model1->save()) {
						Yii::app()->user->setFlash('message','City Has been Added Successfully!');		
						$this->redirect(array('Location/locations'));
					}
					else
					{
						Yii::app()->user->setFlash('message','City Cannot be Added Successfully! Please Try Again');		
						$this->redirect(array('Location/addcity'));	
					}
				}
				else
				{
					Yii::app()->user->setFlash('message','City Cannot be Added Successfully! Please Try Again');	
					$this->redirect(array('Location/addcity'));	
				}	
			}
			$countries = Yii::app()->db->createCommand()
						->select('*')
						->from('trpCountry Country')
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
			$this->redirect(array('User/login'));
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
					$updatearr1['stateid']=$_POST['state'];
					
					$update = Yii::app()->db->createCommand()
								->update('trpCity', 
									$updatearr,
									'id=:id',
									array(':id'=>$_POST['id'])
								);
					$update1 = Yii::app()->db->createCommand()
								->update('trpCityState', 
									$updatearr1,
									'cityid=:cityid',
									array(':cityid'=>$_POST['id'])
								);
					Yii::app()->user->setFlash('message','City Has been Added Successfully!');				
					$this->redirect(array('Location/locations'));
				}		
			$city = Yii::app()->db->createCommand()
					->select('city.*, state.statename statename, state.id stateid, country.countryname countryname, country.id countryid')
					->from('trpCity city')
					->leftJoin('trpCityState cityState', 'city.id=cityState.cityid')
					->leftJoin('trpState state', 'state.id=cityState.stateid')
					->leftJoin('trpStateCountry stateCountry', 'cityState.stateid=stateCountry.stateid')
					->leftJoin('trpCountry country', 'country.id=stateCountry.countryid')
					->where('city.id =:id', array(':id' =>$id))
					->queryRow();				
			$countries = Yii::app()->db->createCommand()
						->select('*')
						->from('trpCountry Country')
						->Where('Country.status =:id', array(':id' => 1))
						->order('Country.id DESC')
						->queryAll();
						
			$states = Yii::app()->db->createCommand()
						->select('*')
						->from('trpState State')
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
			$this->redirect(array('User/login'));
		}
	}	
	
	public function actionGetstates($country_val)
	{	
		$states = Yii::app()->db->createCommand()
					->select('stateid')
					->from('trpStateCountry')
					->where(array('like', 'countryid', "$country_val"))
					->queryAll();
		if(count($states) > 0):
			foreach($states as $sta)
			{
				$stateid = $sta['stateid'];
				$statename[] = Yii::app()->db->createCommand()
						->select('*')
						->from('trpState')
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
	
	public function actionCountrysearch($searchinput)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		$searchcountries = Yii::app()->db->createCommand()
					->select('countryname')
					->from('trpCountry')
					->where(array('like', 'countryname', "$searchinput%"))
					->andWhere('status =:id', array(':id' => 1))
					->queryAll();
					echo '<ul>';
		foreach($searchcountries as $country){
			$countryname = $country['countryname'];
			echo '<li id="'.$countryname.'" onclick="selectcountry(this);">'.$countryname.'</li>';
			}
			echo '</ul>';
	}
	
	public function actionSearchcountry($search_input)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		$countrysearches = Yii::app()->db->createCommand()
					->select('*')
					->from('trpCountry')
					->where(array('like', 'countryname', "%$search_input%"))
					->andWhere('status =:id', array(':id' => 1))
					->queryAll();
		echo '<thead><tr><th>Country Name</th><th>Update</th><th>Delete</th></tr></thead><tbody>';
		foreach($countrysearches as $country){
			$countryname = $country['countryname'];
			$countryid = $country['id'];
			$confirmmessage = "'Are you sure about this?'";
			$upurl = $base_url.'/Location/updatecountry/'.$countryid;
			$delurl = $base_url.'/Location/deletecountry/'.$countryid;
			echo '<tr class=""><td>'.$countryname.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
			
			}
		echo '</tbody>';			
		
	}
	
	public function actionStatesearch($searchinput)
	{
		$searchstates = Yii::app()->db->createCommand()
					->select('statename')
					->from('trpState')
					->where(array('like', 'statename', "$searchinput%"))
					->andWhere('status =:id', array(':id' => 1))
					->queryAll();
		echo '<ul>';
		foreach($searchstates as $state){
			$statename = $state['statename'];
			echo '<li id="'.$statename.'" onclick="selectstate(this);">'.$statename.'</li>';
			}
			echo '</ul>';			
	}
	public function actionCountrysearchstate($searchinput)
	{
		
		$searchcountries = Yii::app()->db->createCommand()
					->select('countryname')
					->from('trpCountry')
					->where(array('like', 'countryname', "$searchinput%"))
					->andWhere('status =:id', array(':id' => 1))
					->queryAll();
					echo '<ul>';
		foreach($searchcountries as $country){
			$countryname = $country['countryname'];
			echo '<li id="'.$countryname.'" onclick="selectcountrystate(this);">'.$countryname.'</li>';
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
					->from('trpState')
					->where(array('like', 'statename', "%$state_search%"))
					->andWhere('status =:id', array(':id' => 1))
					->queryAll();
		echo '<tr><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr>';
		foreach($statesearches as $state){
			$statename = $state['statename'];
			$stateid = $state['id'];
			$countryid = Yii::app()->db->createCommand()
					->select('countryid')
					->from('trpStateCountry')
					->where(array('like', 'stateid', "$stateid"))
					->queryRow();
			$country_id = $countryid['countryid'];
			$countryname = Yii::app()->db->createCommand()
					->select('countryname')
					->from('trpCountry')
					->where(array('like', 'id', "$country_id"))
					->queryRow();
					$country_name = $countryname['countryname'];
			$confirmmessage = "'Are you sure about this?'";
			$delurl = $base_url.'/Location/deletestate/'.$stateid;
			$upurl = $base_url.'/Location/updatestate/'.$stateid;
			echo '<tr class=""><td>'.$statename.'</td><td>'.$country_name.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
			
			}
		echo '</tbody>';	
		}
		if($state_search == '' && $country_search != '')
		{
			
			$countrysearches = Yii::app()->db->createCommand()
					->select('*')
					->from('trpCountry')
					->where(array('like', 'countryname', "$country_search"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();		
			$country_id = $countrysearches['id'];
			$statesearch = Yii::app()->db->createCommand()
					->select('*')
					->from('trpStateCountry')
					->where(array('like', 'countryid', "$country_id"))
					->queryAll();
			
			foreach($statesearch as $state) {
				$state_id = $state['stateid'];
				 $statedetails[] = Yii::app()->db->createCommand()
					->select('*')
					->from('trpState')
					->where(array('like', 'id', "$state_id"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();
				}
				
				
			echo '<tr><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr>';
		foreach($statedetails as $state){
			$statename = $state['statename'];
			$stateid = $state['id'];
			$delurl = $base_url.'/Location/deletestate/'.$stateid;
			$upurl = $base_url.'/Location/updatestate/'.$stateid;
			$confirmmessage = "'Are you sure about this?'";
			echo '<tr class=""><td>'.$statename.'</td><td>'.$country_search.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
			
			}
		echo '</tbody>';		
				
		}
		if($state_search == '' && $country_search == '')
		{
			$statesearches = Yii::app()->db->createCommand()
					->select('*')
					->from('trpState state')
					->where('status =:id', array(':id' => 1))
					->order('state.id DESC')
					->queryAll();
			echo '<tr><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr>';
			foreach($statesearches as $state){
				$statename = $state['statename'];
				$stateid = $state['id'];
				$countryid = Yii::app()->db->createCommand()
						->select('countryid')
						->from('trpStateCountry')
						->where(array('like', 'stateid', "$stateid"))
						->queryRow();
				$country_id = $countryid['countryid'];
				$countryname = Yii::app()->db->createCommand()
						->select('countryname')
						->from('trpCountry')
						->where(array('like', 'id', "$country_id"))
						->andWhere('status =:id', array(':id' => 1))
						->queryRow();
						$country_name = $countryname['countryname'];
				$confirmmessage = "'Are you sure about this?'";
				$delurl = $base_url.'/Location/deletestate/'.$stateid;
				$upurl = $base_url.'/Location/updatestate/'.$stateid;
				echo '<tr class=""><td>'.$statename.'</td><td>'.$country_name.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
				
				}
			echo '</tbody>';		
				
		}
	}
	
	public function actionCountrysearchcity($searchinput)
	{
		
		$searchcountries = Yii::app()->db->createCommand()
					->select('countryname')
					->from('trpCountry')
					->where(array('like', 'countryname', "$searchinput%"))
					->andWhere('status =:id', array(':id' => 1))
					->queryAll();
					echo '<ul>';
		foreach($searchcountries as $country){
			$countryname = $country['countryname'];
			echo '<li id="'.$countryname.'" onclick="selectcountrycity(this);">'.$countryname.'</li>';
			}
			echo '</ul>';
	}
	
	public function actionStatesearchcity($searchinput)
	{
		$searchstates = Yii::app()->db->createCommand()
					->select('statename')
					->from('trpState')
					->where(array('like', 'statename', "$searchinput%"))
					->andWhere('status =:id', array(':id' => 1))
					->queryAll();
		echo '<ul>';
		foreach($searchstates as $state){
			$statename = $state['statename'];
			echo '<li id="'.$statename.'" onclick="selectstatecity(this);">'.$statename.'</li>';
			}
			echo '</ul>';			
	}
	
	public function actionCitysearch($searchinput)
	{
		$searchcities = Yii::app()->db->createCommand()
					->select('cityname')
					->from('trpCity')
					->where(array('like', 'cityname', "$searchinput%"))
					->andWhere('status =:id', array(':id' => 1))
					->queryAll();
					echo '<ul>';
		if($searchcities != null):	
			foreach($searchcities as $city)
			{
				$cityname = $city['cityname'];
				echo '<li id="'.$cityname.'" onclick="selectcity(this);">'.$cityname.'</li>';
			}
		else:
			echo '<li onclick="selectcity(this);">Result Not Found!</li>';
		endif;
		
		echo '</ul>';
	}
	
	public function actionSearchcity($cityinput , $stateinput = null , $countryinput = null)
	{
		$base_url = Yii::app()->getBaseUrl(true);
		if ($cityinput != '' && $stateinput == '' && $countryinput == ''){
		$searchcities = Yii::app()->db->createCommand()
					->select('*')
					->from('trpCity')
					->where(array('like', 'cityname', "$cityinput%"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();
		$cityname = $searchcities['cityname'];
		$city_id = $searchcities['id'];
		$searchstate = Yii::app()->db->createCommand()
					->select('stateid')
					->from('trpCityState')
					->where(array('like', 'cityid', "$city_id"))
					->queryRow();
		$state_id = $searchstate['stateid'];
		$statename = Yii::app()->db->createCommand()
					->select('statename')
					->from('trpState')
					->where(array('like', 'id', "$state_id"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();
		$state_name = 	$statename['statename'];	
		$countryid = Yii::app()->db->createCommand()
					->select('countryid')
					->from('trpStateCountry')
					->where(array('like', 'stateid', "$state_id"))
					->queryRow();	
		$country_id = $countryid['countryid'];
		$countryname = Yii::app()->db->createCommand()
					->select('countryname')
					->from('trpCountry')
					->where(array('like', 'id', "$country_id"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();
					//echo
		$country_name = $countryname['countryname'];
		$delurl = $base_url.'/Location/deletecity/'.$city_id;
		$upurl = $base_url.'/Location/updatecity/'.$city_id;
		$confirmmessage = "'Are you sure about this?'";
		echo '<thead><tr><th>City Name</th><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr></thead><tbody>';
		echo '<tr class=""><td>'.$cityname.'</td><td>'.$state_name.'</td><td>'.$country_name.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
		echo '</tbody>';
	}
	
	if ($cityinput == '' && $stateinput == '' && $countryinput == ''){
		$searchcitiess = Yii::app()->db->createCommand()
					->select('*')
					->from('trpCity City')
					->where('status =:id', array(':id' => 1))
					->order('City.id DESC')
					->queryAll();
					
		if($searchcitiess != null):
			echo '<thead><tr><th>City Name</th><th>State Name</th><th>Country Name</th><th>Update</th><th>Delete</th></tr></thead><tbody>';
			foreach($searchcitiess as $searchcities):
				$cityname = $searchcities['cityname'];
			    $city_id = $searchcities['id'];
				$searchstate = Yii::app()->db->createCommand()
							->select('stateid')
							->from('trpCityState')
							->where(array('like', 'cityid', "$city_id"))
							->queryRow();
				$state_id = $searchstate['stateid'];
				$statename = Yii::app()->db->createCommand()
							->select('statename')
							->from('trpState')
							->where(array('like', 'id', "$state_id"))
							->andWhere('status =:id', array(':id' => 1))
							->queryRow();
				$state_name = 	$statename['statename'];	
				$countryid = Yii::app()->db->createCommand()
							->select('countryid')
							->from('trpStateCountry')
							->where(array('like', 'stateid', "$state_id"))
							->queryRow();	
				$country_id = $countryid['countryid'];
				$countryname = Yii::app()->db->createCommand()
							->select('countryname')
							->from('trpCountry')
							->where(array('like', 'id', "$country_id"))
							->andWhere('status =:id', array(':id' => 1))
							->queryRow();
				$country_name = $countryname['countryname'];
				$delurl = $base_url.'/Location/deletecity/'.$city_id;
				$upurl = $base_url.'/Location/updatecity/'.$city_id;
				$confirmmessage = "'Are you sure about this?'";
				
				echo '<tr class=""><td>'.$cityname.'</td><td>'.$state_name.'</td><td>'.$country_name.'</td><td><a class="edit" href="'.$upurl.'">Update</a></td><td><a class="delete" href="'.$delurl.'" onclick="return confirm('.$confirmmessage.');">Delete</a></td></tr>';
			endforeach;
			echo '</tbody>';
		endif;	
	}
	
	if ($stateinput != '' && $cityinput == '' && $countryinput == ''){
		$searchstate = Yii::app()->db->createCommand()
					->select('*')
					->from('trpState')
					->where(array('like', 'statename', "$stateinput"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();
		$state_id = $searchstate['id'];
		$cityids = Yii::app()->db->createCommand()
					->select('cityid')
					->from('trpCityState')
					->where(array('like', 'stateid', "$state_id"))
					->queryAll();
		$countryid = Yii::app()->db->createCommand()
					->select('countryid')
					->from('trpStateCountry')
					->where(array('like', 'stateid', "$state_id"))
					->queryRow();	
		$country_id = $countryid['countryid'];
		$countryname = Yii::app()->db->createCommand()
					->select('countryname')
					->from('trpCountry')
					->where(array('like', 'id', "$country_id"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();
		$country_name = $countryname['countryname'];
		foreach($cityids as $city)
		{
			$city_id = $city['cityid'];
			$citydetails[] = Yii::app()->db->createCommand()
					->select('*')
					->from('trpCity')
					->where(array('like', 'id', "$city_id"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();
		}
		
		$delurl = $base_url.'/Location/deletecity/'.$city_id;
		$upurl = $base_url.'/Location/updatecity/'.$city_id;
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
					->from('trpCountry')
					->where(array('like', 'countryname', "$countryinput"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();
		$country_id = $countryid['id'];
		$stateids = Yii::app()->db->createCommand()
					->select('stateid')
					->from('trpStateCountry')
					->where(array('like', 'countryid', "$country_id"))
					->queryAll();
		foreach($stateids as $sid)
		{
			$state_id = $sid['stateid'];
			$cityids[] = Yii::app()->db->createCommand()
					->select('cityid')
					->from('trpCityState')
					->where(array('like', 'stateid', "$state_id"))
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
					->from('trpCity')
					->where(array('like', 'id', "$c_id"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();
			$stateid = Yii::app()->db->createCommand()
					->select('stateid')
					->from('trpCityState')
					->where(array('like', 'cityid', "$c_id"))
					->queryRow();
			$state_id = $stateid['stateid'];	
			$statename = Yii::app()->db->createCommand()
					->select('statename')
					->from('trpState')
					->where(array('like', 'id', "$state_id"))
					->andWhere('status =:id', array(':id' => 1))
					->queryRow();
			$citydetails[$i]['statename'] = $statename['statename'];	
			$i++;
		}
		$confirmmessage = "'Are you sure about this?'";
		$delurl = $base_url.'/Location/deletecity/'.$city_id;
		$upurl = $base_url.'/Location/updatecity/'.$city_id;
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
	
}

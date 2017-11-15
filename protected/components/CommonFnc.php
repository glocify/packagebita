<?php

class CommonFnc extends CApplicationComponent {
 
 public function PageList() {
 
    $value = Yii::app()->db->createCommand()
			 ->select('*')
			 ->from('cads_pages')
			 //->where(array('like','page_location','footer'))
			 ->queryAll();
 
     return $value;
 }
 
  public function getUserName($id = null) {
 
    $value = Yii::app()->db->createCommand()
			 ->select('name')
			 ->from('cads_Users')
			 ->where('id =:userid', array(':userid' => $id))
			 ->queryRow();			

     return $value['name'];
 }
 
 public function getCountsBasedOnUerID($userid = null){
	$getcounts = Yii::app()->db->createCommand()
			 ->select('profile_count count')
			 ->from('cads_click_count')
			 ->where('user_id =:userid', array(':userid' => $userid))
			 ->queryAll();
	$counts = 0;
	if(!empty($getcounts)){
		foreach($getcounts as $getcount ){
			$counts += $getcount['count'];
		}	
	}
	return $counts;
 }
 
 public function checkPackageActive($userid = null){
	$getPackageCounts = Yii::app()->db->createCommand()
			 ->select('b.click clicks')
			 ->from('cads_Orders a')
			 ->leftJoin('cads_Packages b', 'a.package_id = b.id')
			 ->where('a.userid =:userid', array(':userid' => $userid))
			 ->queryAll();
	$countSet = 0;		
	if(!empty($getPackageCounts)){
		foreach($getPackageCounts as $packcount){
				$countSet += $packcount['clicks'];
			}
	}	
	if($countSet == 0){
		return false;
	}else{
		$countSet;
		$clicked_counts = $this->getCountsBasedOnUerID($userid);
		$balanceCount = $countSet - $clicked_counts;
		if($balanceCount > 0){
			return true;
		}else{
			return false;
		}
	}
	
 }
 
 public function getPackageDetailBasedonOrder($OrderID = null){
	$getPackageCounts = Yii::app()->db->createCommand()
			 ->select('b.*')
			 ->from('cads_Orders a')
			 ->leftJoin('cads_Packages b', 'a.package_id = b.id' )
			 ->where('a.id =:orderId', array(':orderId' => $OrderID))
			 ->queryRow();
	return $getPackageCounts ;
 }
 public function getClickedCounts($postID = null){
	$getPackageCounts = Yii::app()->db->createCommand()
			 ->select('b.ad_count count, b.ad_id adid')
			 ->from('cads_Ads a')
			 ->leftJoin('cads_adcount b', 'a.id = b.ad_id' )
			 ->where('a.id =:postID', array(':postID' => $postID))
			 ->queryAll();
	$counts = 0;
	if(!empty($getPackageCounts)){
		foreach($getPackageCounts as $package ){
			$counts += $package['count'];
		}	
	}
	return $counts; 
 }
 public function getPendingCounts($userid = null){
	 $getPackageCounts = Yii::app()->db->createCommand()
			 ->select('b.click clicks')
			 ->from('cads_Orders a')
			 ->leftJoin('cads_Packages b', 'a.package_id = b.id' )
			 ->where('a.userid =:userid', array(':userid' => $userid))
			 ->queryAll();
	$countSet = 0;		
	if(!empty($getPackageCounts)){
		foreach($getPackageCounts as $packcount){
				$countSet += $packcount['clicks'];
			}
	}	
	if($countSet == 0){
		return $countSet;
	}else{
		$clicked_counts = $this->getCountsBasedOnUerID($userid);
		$balanceCount = $countSet - $clicked_counts;
		if($balanceCount > 0){
			return $balanceCount;
		}else{
			return $countSet;
		}
	}
 }

}
?>

<?php $base_url = Yii::app()->getBaseUrl(true);?>
<div class="search-barr add-srch ad-detail">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			
			</div>
		</div>
	</div>
</div>
<div class="housesfor-rent">
	
</div>
<div class="housearae">
	<div class="container">
		<div class="row">	
			<div class="col-sm-8 lefths">
					
				<div class="desprtion">
					<h2><?php echo $pagedata['page_title'];?></h2>
					<?php echo $pagedata['page_body'];?>
						
				</div>		
						
							
			</div>	
			<div class="col-sm-4 righths">
				<div class="top_bc"><h3 class="rest-h">Pages Overview</h3></div>
				<div class="ryt-view">
					<ul class="overview-list">
						  <?php if(!empty($PageList)){
							  foreach($PageList as $currntpglist){?>
								<li>
								<?php if($currntpglist['page_title']=='Business Listing'){?>
								<a title="<?php echo $currntpglist['page_title'];?>" href="http://package.glocify.org/owner.html" target="_blank">
								<?php } else {?>
								<a title="<?php echo $currntpglist['page_title'];?>" href="<?php echo $base_url; ?>/<?php echo $currntpglist['page_name'];?>.html">
								<?php } ?>
								<?php echo $currntpglist['page_title'];?></a></li>						  
							  <?php }
						  } else {?>
						  <li class="">No Pages Found</a></li>
						  <?php } ?>
					</ul>
				</div>		
			</div>	
		</div>
	</div>
</div>
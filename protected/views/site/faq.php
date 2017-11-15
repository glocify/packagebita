<div class="faq" >
<div class="container">
<div class="row">
  <!-- Nav tabs -->


  <!-- Tab panes -->
<div role="tabpanel"> 
  
	  <!-- Nav tabs -->
	  <?php if(!empty($faq_type)){?>
	  <ul class="nav nav-tabs" role="tablist">
			<?php $i=0; foreach($faq_type as $faqtkey=>$faqval){ ++$i;?>
			<?php if(isset($managestr) && $managestr!=''){
				?>
			<li role="presentation"  <?php if($managestr==$faqtkey){ ?>class="active"<?php } ?>><a href="#<?php echo $faqtkey;?>" aria-controls="<?php echo $faqtkey;?>" role="tab" data-toggle="tab"><?php echo $faqval;?></a></li>
			<?php }else{
				?>
			<li role="presentation" <?php if($i==1){?>class="active"<?php } ?>><a href="#<?php echo $faqtkey;?>" aria-controls="<?php echo $faqtkey;?>" role="tab" data-toggle="tab"><?php echo $faqval;?></a></li>
			<?php } } ?>
		
	  </ul>
	  <?php } ?>
	  <!-- Tab panes -->
  
	<?php if(!empty($faq_type)){ ?>  
	<div class="tab-content">
		<?php $j=0; foreach($faq_type as $faqtkey=>$faqval){ ++$j;
		if(isset($managestr) && $managestr!=''){ ?>
		<div role="tabpanel" class="tab-pane <?php if($managestr==$faqtkey){ echo "active"; } ?>" id="<?php echo $faqtkey;?>"> 
		<?php } else{ ?>
		<div role="tabpanel" class="tab-pane <?php if($j==1){?>active<?php } ?>" id="<?php echo $faqtkey;?>"> 
		<?php } ?>
			<!-- ACCORDION NO.1 -->
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php 
				$count = 0;
				foreach($faqData as $faqData_single):	
				if($faqData_single['faq_type'] == $faqval):	
?>
				<div class="panel panel-default">
					<div class="panel-heading edu1 active-state" role="tab" id="headingOne">
						<h4 class="panel-title"> 
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $count; ?>" aria-expanded="false" aria-controls="collapse<?php echo $count; ?>" class="collapsed"><?php echo $faqData_single['faq_question']?></a></h4>
					</div>
					<div id="collapse<?php echo $count; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body"><?php echo $faqData_single['faq_answer']?> </div>
					</div>
				</div>
<?php 
			endif;
			$count++;
			endforeach;
?>				
				
				
				
			  </div>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>

</div></div></div>

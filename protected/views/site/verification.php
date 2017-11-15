<?php
$base_url = Yii::app()->getBaseUrl(true);
?>
<div class="body-pattren-bg site-map">
	 
			<div class="verifiedpage">
            <h1>
				<?php if(Yii::app()->user->hasFlash('message')): ?>
					<div class="flash-success">
						<?php echo Yii::app()->user->getFlash('message'); ?>
					</div>
				<?php endif; ?>
			</h1>  	
			</div>
        
</div>

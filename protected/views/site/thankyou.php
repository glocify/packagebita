<?php if(Yii::app()->user->hasFlash('register')):?>
						<div class="info">
							<h4 style="color:red;"><?php echo Yii::app()->user->getFlash('register'); ?></h4>
						</div>
					<?php endif; ?>
					<?php if(Yii::app()->user->hasFlash('error')):?>
						<div class="info">
							<h4 style="color:red;"><?php echo Yii::app()->user->getFlash('error'); ?></h4>
						</div>
					<?php endif; ?>
<div class="msg_thnk"><h1>Thank you</h1></div>
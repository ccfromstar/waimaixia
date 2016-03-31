
<div style="border:1px solid #ddd;">

        <h6 style="line-height:30px;background:#eee;border-bottom:1px solid #ddd;text-indent:5px;">我的个人信息</h6>
        <div class="content" style="padding:10px;line-height:30px;">
			<p>您好，<?php echo Yii::app()->user->name;?></p>
			<p>您的授权角色：<?php echo $role;?></p>
			<p>欢迎使用 <?php echo Yii::app()->name;?> 管理系统</p>
			<p>现在时间： <?php echo date('Y-m-d H:i:s');?></p>
        </div>

</div>

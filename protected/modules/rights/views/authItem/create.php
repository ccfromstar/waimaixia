

<div class="createAuthItem">

	<h3 class="title"><?php echo Rights::t('core', 'Create :type', array(
		':type'=>Rights::getAuthItemTypeName($_GET['type']),
	)); ?></h3>

	<?php $this->renderPartial('_form', array('model'=>$formModel)); ?>

</div>
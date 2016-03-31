<?php $this->breadcrumbs = array(
	'用户权限'=>Rights::getBaseUrl(),
	Rights::t('core', 'Generate items'),
); ?>

<div id="generator">

	<h3 class="title">项目节点</h3>

	<p style="height:28px;line-height: 28px;"><?php echo Rights::t('core', 'Please select which items you wish to generate.'); ?></p>

	<div class="form" style="border:none;">

		<?php $form=$this->beginWidget('CActiveForm',array('htmlOptions' =>array('style' => 'border:none;'))); ?>

			<div class="row" style="height:auto;padding:0;margin:0;border:none;">

				<table class="items" border="0" cellpadding="0" cellspacing="0">

					<tbody>

						<tr>
							<th style="height:25px;background:#EEF3F7;border-bottom:2px solid #82B9D7;" colspan="3"><?php echo Rights::t('core', 'Application'); ?></th>
						</tr>

						<?php $this->renderPartial('_generateItems', array(
							'model'=>$model,
							'form'=>$form,
							'items'=>$items,
							'existingItems'=>$existingItems,
							'displayModuleHeadingRow'=>true,
							'basePathLength'=>strlen(Yii::app()->basePath),
						)); ?>

					</tbody>

				</table>

			</div>

			<div class="row">

   				<?php echo CHtml::link(Rights::t('core', 'Select all'), '#', array(
   					'onclick'=>"jQuery('.items').find(':checkbox').attr('checked', 'checked'); return false;",
   					'class'=>'selectAllLink')); ?>
   				/
				<?php echo CHtml::link(Rights::t('core', 'Select none'), '#', array(
					'onclick'=>"jQuery('.items').find(':checkbox').removeAttr('checked'); return false;",
					'class'=>'selectNoneLink')); ?>

			</div>

   			<div class="buttons">

				<?php echo CHtml::submitButton(Rights::t('core', 'Generate')); ?>

			</div>

		<?php $this->endWidget(); ?>

	</div>

</div>
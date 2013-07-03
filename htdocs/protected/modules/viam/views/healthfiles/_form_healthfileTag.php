<div>
	<span style="width:200px;">
	    <?php echo CHtml::activeTextField($model, '[' . $index . ']tag', array('size' => 20, 'maxlength' => 255)); ?>
	    <?php echo CHtml::error($model, '[' . $index . ']tag'); ?>
	</span>
	<span style="width:100px;">
	    <?php echo CHtml::link('Delete', '#', array('onclick' => 'deleteChild(this, ' . $index . '); return false;'));
	    ?>
	</span>
</div>

<?php
Yii::app()->clientScript->registerScript('deleteChild', "
function deleteChild(elm, index)
{
    element=$(elm).parent().parent();
    /* animate div */
    $(element).animate(
    {
        opacity: 0.25, 
        left: '+=50', 
        height: 'toggle'
    }, 500,
    function() {
        /* remove div */
        $(element).remove();
    });
}", CClientScript::POS_END);
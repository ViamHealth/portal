<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'',
);
?>

<script>
window.location.replace("<?php echo $this->createUrl('/goals/index/'); ?>");
</script>
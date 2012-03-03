<?php $extra_css = array(DIR_TEMPLATE_WEB.'/js/jquery-ui-redmond/jquery-ui-1.8.7.custom.css'); ?>
<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:cycles_create'); ?>
</h1>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if(isset($this->create_success)): ?>
	<div class = "alert alert-success">
		<?php Lang::out('msg:cycle_created'); ?>
	</div>
<?php endif; ?>
<?php include 'admin.cycles.form.tpl.php'; ?>

<script type = "text/javascript" src = "<?php echo DIR_TEMPLATE_WEB; ?>/js/jquery-ui-1.8.7.custom.min.js"></script>
<script type = "text/javascript">
$(document).ready(function(){
	$("#start-field,#end-field").datepicker({
		autoSize:true,
		dateFormat:'yy-mm-dd'
	});
});
</script>
<?php include 'foot.tpl.php'; ?>
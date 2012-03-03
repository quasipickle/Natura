<?php $extra_css = array(DIR_TEMPLATE_WEB.'/js/jquery-ui-redmond/jquery-ui-1.8.7.custom.css'); ?>
<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:cycle_edit'); ?>
</h1>
<br />
<?php if(isset($this->error)): ?>
	<div class = "message error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
	<br />
<?php endif; ?>
<?php if(isset($this->edit_success)): ?>
	<div class = "message success">
		<?php Lang::out('msg:cycle_edit_success'); ?>
	</div>
	<br />
<?php endif; ?>

<?php
# Set variable the form file uses to determine whether it is displaying a create or edit form
$editing = TRUE;

include 'admin.cycles.form.tpl.php'; ?>

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
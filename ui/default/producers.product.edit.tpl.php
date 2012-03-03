<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:product_edit'); ?>
</h1>
<?php if(isset($this->edit_success)): ?>
	<p class = "alert alert-success">
		<?php Lang::out('msg:product_save_success'); ?>
	</p>
	<br />
<?php endif; ?>

<?php
	# This variable is checked by producers.product.form.tpl.php to determine
	# whether to output a "create" button or an edit button and hidden id field
	$editing = TRUE; 
?>
<?php include 'producers.product.form.tpl.php'; ?>
<?php include 'foot.tpl.php'; ?>
<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:create_product'); ?>
</h1>

<?php if(isset($this->create_success)): ?>
	<div class = "alert alert-success">
		<?php Lang::out('msg:product_create_success'); ?>
	</div>
<?php endif; ?>
	
<?php include 'producers.product.form.tpl.php'; ?>
<?php include 'foot.tpl.php'; ?>
<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:producers'); ?>
</h1>
<p>
	<h3>
		<a href = "<?php echo DIR_WEB; ?>/producers/product/new/">
			<?php Lang::out('menu:create_product'); ?>
		</a>
	</h3>
</p>
<p>
	<h3>
		<a href = "<?php echo DIR_WEB; ?>/producers/products/view/">
			<?php Lang::out('menu:manage_products'); ?>
		</a>
	</h3>
</p>
<p>
	<h3>
		<a href = "<?php echo DIR_WEB; ?>/producers/orders/">
			<?php Lang::out('menu:view_purchase_orders'); ?>
		</a>
	</h3>
</p>
<p>
	<h3>
		<a href = "<?php echo DIR_WEB; ?>/producers/profile/">
			<?php Lang::out('menu:change_producer_profile');?>
		</a>
	</h3>
</p>
<?php include 'foot.tpl.php'; ?>
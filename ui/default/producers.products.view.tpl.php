<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:products'); ?>
</h1>
<?php if(isset($this->delete_success)): ?>
	<div class = "alert alert-success">
		<?php Lang::out('msg:product_deleted'); ?>
	</div>
	<br />
<?php endif; ?>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
	<br />
<?php endif; ?>

<?php if($this->products): ?>
	<table class = "table table-striped">
		<thead>
			<tr>
				<th>
					<?php Lang::out('lbl:product_name'); ?>
				</th>
				<th>
					<?php Lang::out('lbl:product_description'); ?>	
				</th>
				<th>
					<?php Lang::out('lbl:product_price'); ?>	
				</th>
				<th>
					<?php Lang::out('lbl:product_units'); ?>	
				</th>
				<th>
					<?php Lang::out('lbl:product_count'); ?>	
				</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->products as $Product): ?>
				<tr>
					<td>
						<?php echo $Product->name; ?>
					</td>
					<td>
						<?php echo $Product->description; ?>
					</td>
					<td>
						$<?php echo number_format($Product->price,2); ?>
					</td>
					<td>
						<?php echo $Product->units; ?>
					</td>
					<td>
						<?php if($Product->count === NULL):
							Lang::out('info:inventory_unlimited');
							else:
								echo $Product->count;
							endif; ?>
					</td>
					<td>					
						<form method = "post" action = "">
							<div>
								<a href = "<?php echo DIR_WEB; ?>/producers/product/edit/?id=<?php echo htmlentities($Product->id); ?>" class = "btn"><?php Lang::out('btn:product_edit'); ?></a>
								<input type = "hidden" name = "id" value = "<?php echo htmlentities($Product->id); ?>" />
								<input type = "submit" name = "delete" class = "btn btn-danger" value = "<?php Lang::outSafe('btn:delete_product'); ?>" />
							</div>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<?php Lang::out('info:products_none'); ?>
<?php endif; ?>
<?php include 'foot.tpl.php'; ?>
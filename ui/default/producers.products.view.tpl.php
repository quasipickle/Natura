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
	<table class = "table table-striped table-inline-form">
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
				<th>
					<?php Lang::out('lbl:product_categories'); ?>
				</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->products as $Product): ?>
				<tr>
					<td class = "product-name">
						<?php echo $Product->name; ?>
					</td>
					<td class = "product-description">
						<?php echo $Product->description; ?>
					</td>
					<td class = "product-price">
						$<?php echo number_format($Product->price,2); ?>
					</td>
					<td class = "product-units">
						<?php echo $Product->units; ?>
					</td>
					<td class = "product-count">
						<?php if($Product->count === NULL):
								Lang::out('info:inventory_unlimited');
							else:							
								echo $Product->count;
						endif; ?>
					</td>
					<td class = "product-categories">
						<?php	$category_string = implode($Product->categories,', '); 
								$category_id_string = implode(array_keys($Product->categories),',');
						?>
						<span class = "category_string" title = "<?php echo $category_string; ?>">
							<?php
								echo substr($category_string,0,10); 
								if(strlen($category_string) > 10)
									echo '&hellip;'
							?>
						</span>
						<span class = "category_id_string hide"><?php echo $category_id_string; ?></span>
					</td>
					<td>					
						<form method = "post" action = "">
							<div>
								<a href = "<?php echo DIR_WEB; ?>/producers/product/edit/?id=<?php echo htmlentities($Product->id); ?>" class = "btn edit-product"><?php Lang::out('btn:product_edit'); ?></a>
								<input type = "hidden" name = "id" value = "<?php echo htmlentities($Product->id); ?>" />
								<input type = "submit" name = "delete" class = "btn btn-danger" value = "<?php Lang::outSafe('btn:delete_product'); ?>" />
							</div>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div id = "edit-form" class = "modal hide">
		<div class = "modal-header">
			<h3>
				<?php Lang::out('ttl:product_edit'); ?>
			</h3>
		</div>
		<div class = "modal-body" style = "max-height:350px;overflow:auto">
			<?php 
				$hide_buttons = TRUE;
				include 'producers.product.form.tpl.php'; 
			?>
		</div>
		<div class = "modal-footer">
			<a href = "#" id = "save-product" class = "btn btn-primary" data-loading-text = "Saving&hellip;" data-saved-text = "Saved!">
				<?php Lang::out('btn:product_save'); ?>
			</a>
			<a href = "#" id = "cancel-change" class = "btn" data-dismiss = "modal">
				<?php Lang::out('btn:cancel'); ?>
			</a>
		</div>
	</div>
	<script type = "text/javascript" src = "<?php echo DIR_TEMPLATE_WEB; ?>/js/product.view.js"></script>
<?php else: ?>
	<?php Lang::out('info:products_none'); ?>
<?php endif; ?>
<?php include 'foot.tpl.php'; ?>
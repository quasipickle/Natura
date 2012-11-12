<?PHP

#
# This file is included by producers.product.new.tpl.php and producers.product.edit.tpl.php
# It is the form used to create/modify products, which is almost identical on both pages
#

?>
<p>
	<strong><?php Lang::out('info:fields_required'); ?></strong>
</p>
<?php if(isset($this->error) && $this->error): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
	<br />

<?php endif; ?>
<form method = "post" action = "">
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:product_name'); ?>
		</label>
		<input type = "text" name = "name" value = "<?php echo htmlentities($this->name); ?>">
	</div>
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:product_description'); ?>
		</label>
		<input type = "text" name = "description" value = "<?php echo htmlentities($this->description); ?>" size = "50" />
	</div>
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:product_price'); ?>
		</label>
		<div class = "input-prepend">
			<span class = "add-on">
				$
			</span>
			<input type = "text" name = "price" class = "only_decimal span1" value = "<?php echo htmlentities(number_format($this->price,2)); ?>"/>
			<span class = "help-inline"><?php Lang::out('info:per_unit'); ?></span>
		</div>
	</div>
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:product_units'); ?>
		</label>
		<input type = "text" name = "units" value = "<?php echo htmlentities($this->units); ?>" class = "span2"/>
		<span class = "help-inline"><?php Lang::out('info:product_units'); ?></span>
	</div>
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:product_count'); ?>
		</label>
		<input type = "text" name = "count" class = "only_integer span1" value = "<?php echo htmlentities($this->count); ?>" />
		<span class = "help-inline">
			<?php Lang::out('info:product_count'); ?>
		</span>
	</div>		
	<?php if(count($this->all_categories)): ?>
		<div class = "control-group">
			<label>
				<?php Lang::out('lbl:product_categories'); ?>
			</label>			
			<?php foreach($this->all_categories as $Category): ?>
				<label class = "checkbox" for = "category_<?php echo $Category->id; ?>">
					<input 
						type = "checkbox" 
						name = "categories[<?php echo $Category->id; ?>]"
						id = "category_<?php echo $Category->id; ?>"
						value = "<?php echo htmlentities($Category->name_hr); ?>" 
						<?php if(isset($this->categories[$Category->id])): ?>checked = "checked"<?php endif; ?>/> 
						<?php echo $Category->name_hr; ?>
				</label><br />
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if(!isset($hide_buttons)): # Will be set if loading for a modal window ?>
		<div class = "form-actions">
			<?php if(isset($editing)): ?>
				<input type = "submit" name = "edit" class = "btn" value = "<?php Lang::outSafe('btn:product_save'); ?>" />
				<input type = "hidden" name = "id" value = "<?php echo htmlentities($this->id); ?>" />
			<?php else: ?>
				<input type = "submit" name = "create" class = "btn" value = "<?php Lang::outSafe('btn:create_product'); ?>" />
			<?php endif; ?>
		</div>
	<?php endif; ?>
</form>

<script type = "text/javascript" src = "<?php echo DIR_TEMPLATE_WEB; ?>/js/product.form.js"></script>

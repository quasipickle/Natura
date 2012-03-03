<?php
$button_name = 'create';
$button_key = 'btn:cycle_create';
$cancel_key = 'btn:cycle_create_cancel';

if(isset($editing))
{
	$button_name = 'edit';
	$button_key = 'btn:cycle_edit';
	$cancel_key = 'btn:cycle_edit_cancel';
}?>

<form method = "post" action = "">
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:cycle_name'); ?>
		</label>
		<input type = "text" size = "20" name = "name" value = "<?php echo htmlentities($this->name); ?>" />
	</div>
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:cycle_start'); ?>
		</label>
		<div class = "controls input-prepend">
			<span class = "add-on">
				<i class = "icon-calendar"></i>
			</span>
			<input type = "text" class = "date span2" name = "start" id = "start-field" value = "<?php echo htmlentities($this->start); ?>" />
			<span class = "help-inline"><?php Lang::out('info:cycle_start'); ?></small>
		</div>
	</div>
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:cycle_end'); ?>
		</label>
		<div class = "controls input-prepend">
			<span class = "add-on">
				<i class = "icon-calendar"></i>
			</span>
			<input type = "text" class = "date span2" name = "end" id = "end-field" value = "<?php echo htmlentities($this->end); ?>" />
			<span class = "help-inline"><?php Lang::out('info:cycle_end'); ?></span>
		</div>
	</div>
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:cycle_category'); ?>
		</label>
		<span class = "help-block"><?php Lang::out('info:cycle_category'); ?></span>
		<div class = "controls">
			<?php foreach($this->all_categories as $Category): ?>
				<label class = "checkbox" for="category_<?php echo $Category->id; ?>">
					<input 
						type = "checkbox" 
						id = "category_<?php echo $Category->id; ?>" 
						name = "category[<?php echo $Category->id; ?>]" 
						value = "<?php echo htmlentities($Category->id); ?>" 
						<?php if(isset($this->categories[$Category->id])): ?>checked = "checked"<?php endif; ?>
					/><?php echo $Category->name_hr; ?>
				</label>
			<?php endforeach; ?>
		</div>
	</div>
	<div class = "form-actions">
		<input 
				type = "submit" 
				class = "btn btn-primary"
				name = "<?php echo $button_name; ?>"
				value = "<?php Lang::outSafe($button_key); ?>" />
		&nbsp;
		<a 
			href = "<?php echo DIR_WEB; ?>/admin/cycles/"
			class = "button button-warning"><?php Lang::out($cancel_key); ?></a>
	</div>
</form>
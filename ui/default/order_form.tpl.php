<?php if(count($this->products) == 0): ?>
	<?php Lang::out('msg:order.none'); ?>
<?php else: ?>
	<form method = "post" action = "">
		<input type = "hidden" name = "active_cycle" value = "<?php echo htmlentities($this->active_cycle); ?>" />
		<?php if(isset($this->order_id)): ?>
			<input type = "hidden" name = "id" value = "<?php echo $this->order_id; ?>" />
		<?php endif; ?>
		<div class = "tabbable">
			<ul class = "nav nav-tabs">
				<?php foreach($this->products as $id => $Category): ?>
					<li<?php if($id == 0): ?> class = "active" <?php endif; ?>>
						<a href = "#category-<?php echo $id; ?>" data-toggle = "tab"><?php echo $Category->name; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class = "tab-content">
				<?php foreach($this->products as $category_id => $Category): ?>
					<div class = "tab-pane <?php if($category_id == 0): ?>active<?php endif; ?>" id = "category-<?php echo $category_id; ?>">
						<table class = "table table-striped" id = "products">
							<?php foreach($Category->products as $Producer): ?>
								<thead>
									<tr>
										<th colspan = "4">
											<h3>
												<?php echo $Producer->name; ?><a href = "#" class = "hide-products btn btn-mini">Hide products</a><a href = "#" class = "show-products hidden btn btn-mini btn-success">Show products</a>
											</h3>
										</th>	
									</tr>				
									<tr>
										<th>
											<?php Lang::out('lbl:product'); ?>
										</th>
										<td>
											<?php Lang::out('lbl:product_price'); ?>
										</th>
										<td>
											<?php Lang::out('lbl:available'); ?>
										</th>
										<th style = "width: 45px;">
										</th>
									</tr>
								</thead>
								<tbody>	
									<?php foreach($Producer->products as $Product): ?>
										<tr>
											<td>
												<?php echo $Product->name; ?>
												<div class = "help-block"><?php echo $Product->description; ?></div>
											</td>
											<td>
												$<?php echo number_format($Product->price,2); ?>/<?php echo $Product->units; ?>
											</td>
											<td>
												<?php
													if($Product->count != NULL):
														echo $Product->count;
													else:
														Lang::out('info:inventory_unlimited');
													endif;
												?>
											</td>
											<td>
												<?php if($this->order_can_be_updated): ?>
													<input 
														type = "text" 
														name = "products[<?php echo $Product->id; ?>]"
														class = "product span1" 
														size = "3" 
														value = "<?php if(isset($this->ordered_items[$Product->id])) echo $this->ordered_items[$Product->id]->count; ?>"/>
												<?php else:
														if(isset($this->ordered_items[$Product->id])) 
															echo $this->ordered_items[$Product->id]->count;
													  endif; ?>
											</td>
										</tr>	
									<?php endforeach; ?>
								</tbody>
							<?php endforeach; ?>
						</table>
					</div>
				<?php endforeach; ?>
			</div><!-- .tab-content -->
		</div>
		<div class = "form-actions">
			<?php if($this->order_can_be_updated == FALSE): ?>
				<?php Lang::out('msg:order_edit_window_expired'); ?>
				<br />
				<br />
				<a href = "<?php echo DIR_WEB; ?>/members/order/view/" class = "btn btn-primary">
					<i class = "icon-arrow-left icon-white"></i><?php Lang::out('btn:back_to_orders'); ?>
				</a>
				<a href = "<?php echo DIR_WEB; ?>/members/order/view/?id=<?php echo $this->order_id; ?>&amp;download" class = "btn">
					<i class = "icon-download-alt"></i>
					<?php Lang::out('btn:download'); ?>
				</a>
			<?php else: ?>
				<input type = "submit" name = "submit" value = "<?php 
					if(isset($this->viewing_order)) 
						Lang::outSafe('btn:order_update');
					else
						Lang::outSafe('btn:order_place');
				?>" class = "btn btn-primary" />
				<a href = "#" id = "show-ordered" class = "hidden btn"><?php Lang::out('btn:order_show_ordered'); ?></a>
				<a href = "#" id = "show-all" class = "hidden btn"><?php Lang::out('btn:order_show_all'); ?></a>
			<?php endif; ?>
		</div>
	</form>
	<script type = "text/javascript" src = "<?php echo DIR_TEMPLATE_WEB; ?>/js/order.js"></script>
<?php endif; ?>
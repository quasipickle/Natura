<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>
			<?php echo Lang::get('ttl:page').' '.ORGANIZATION_NAME; ?>
		</title>
		<link rel = "stylesheet" type = "text/css" href = "<?php echo DIR_TEMPLATE_WEB; ?>/js/jquery-ui-bootstrap/jquery-ui-1.8.16.custom.css" />
		<link rel = "stylesheet" type = "text/css" href = "<?php echo DIR_TEMPLATE_WEB; ?>/bootstrap/css/bootstrap.min.css" />
		<link rel = "stylesheet" type = "text/css" href = "<?php echo DIR_TEMPLATE_WEB; ?>/style.css" />
		
		<script type = "text/javascript" src = "http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type = "text/javascript" src = "<?php echo DIR_TEMPLATE_WEB; ?>/bootstrap/js/bootstrap.min.js"></script>
		<script type = "text/javascript" src = "<?php echo DIR_TEMPLATE_WEB; ?>/js/script.js"></script>
	</head>
	<body>
		<div id = "body">
			<div id = "content">
				<div class = "navbar">
					<div class = "navbar-inner">
						<div class = "container">
							<a href = "<?PHP echo DIR_WEB; ?>/" class = "brand"> <?php echo ORGANIZATION_NAME; ?></a>
							<ul class = "nav pull-right">
								<?php if($this->is_authed): ?>
									<li>
										<a href = "<?php echo DIR_WEB; ?>/logout/">
											<i class = "icon-white icon-off icon-spaced"></i><?php Lang::out('btn:logout'); ?>
										</a>
									</li>
								<?php else: ?>
									<li>
										<a href = "<?php echo DIR_WEB; ?>/login/">
											<i class = "icon-white icon-off icon-spaced"></i><?php Lang::out('btn:login'); ?>
										</a>
									</li>
									<li class = "divider-vertical"></li>
									<li>
										<a href = "<?php echo DIR_WEB; ?>/signup/">
											<i class = "icon-white icon-user icon-spaced"></i><?php Lang::out('btn:signup'); ?>
										</a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div><!-- .navbar -->
				<?php if($this->is_authed): ?>
					<div class = "subnav">
						<ul class = "nav nav-pills">
							<li <?php if($this->member_page): ?>class = "active"<?php endif; ?>>
								<a href = "<?php echo DIR_WEB; ?>/members/"><?php Lang::out('menu:members'); ?></a>
							</li>
							<?php if($this->is_producer): ?>
								<li <?php if($this->producer_page): ?>class = "active"<?php endif; ?>>
									<a href = "<?php echo DIR_WEB; ?>/producers/"><?php Lang::out('menu:producers'); ?></a>
								</li>
							<?php endif; ?>
							<?php if($this->is_admin): ?>
								<li <?php if($this->admin_page): ?>class = "active"<?php endif; ?>>
									<a href = "<?php echo DIR_WEB; ?>/admin/"><?php Lang::out('menu:admin'); ?></a>
								</li>
							<?php endif; ?>
						</ul>
					</div>
					
					<!-- Member nav -->
					<?php if($this->member_page): ?>
						<div class = "subnav">
							<ul class = "nav nav-pills">
								<li>
									<a href = "<?php echo DIR_WEB; ?>/members/order/new/">
										<i class = "icon-shopping-cart icon-spaced"></i><?php Lang::out('menu:place_order'); ?>
									</a>
								</li>
								<li>
									<a href = "<?php echo DIR_WEB; ?>/members/order/view/">
										<i class = "icon-eye-open icon-spaced"></i><?php Lang::out('menu:view_current_order'); ?>
									</a>
								</li>
							</ul>
						</div>
					<?php endif; ?>	
					
					
					<!-- Producer nav -->
					<?php if($this->producer_page): ?>
						<div class = "subnav">
							<ul class = "nav nav-pills">
								<li>
									<a href = "<?php echo DIR_WEB; ?>/producers/product/new/">
										<i class = "icon-plus icon-spaced"></i>
										<?php Lang::out('menu:create_product'); ?>
									</a>
								</li>
								<li class = "producer">
									<a href = "<?php echo DIR_WEB; ?>/producers/products/view/">
										<i class = "icon-pencil icon-spaced"></i>
										<?php Lang::out('menu:manage_products'); ?>
									</a>
								</li>
								<li>
									<a href = "<?php echo DIR_WEB; ?>/producers/orders/">
										<i class = "icon-list-alt icon-spaced"></i>
										<?php Lang::out('menu:view_purchase_orders'); ?>
									</a>
								</li>
								<li>
									<a href = "<?php echo DIR_WEB; ?>/producers/profile/">
										<i class = "icon-user icon-spaced"></i>
										<?php Lang::out('menu:change_producer_profile');?>
									</a>
								</li>
							</ul>
						</div>
					<?php endif; ?>
					
					<!-- Admin nav -->
					<?php if($this->admin_page): ?>
						<div class = "subnav">
							<ul class = "nav nav-pills">
								<li>
									<a href = "<?php echo DIR_WEB; ?>/admin/cycles/">
										<i class = "icon-spaced icon-repeat"></i><?php Lang::out('menu:manage_cycles'); ?>
									</a>
								</li>
								<li>
									<a href = "<?php echo DIR_WEB; ?>/admin/categories/">
										<i class = "icon-spaced icon-tags"></i>
										<?php Lang::out('menu:manage_categories'); ?>
									</a>
								</li>
								<li class = "dropdown">
									<a class = "dropdown-toggle" data-toggle = "dropdown" href = "#"><i class = "icon-spaced icon-list"></i>User lists&nbsp;<span class = "caret"></span></a>
									<ul class = "dropdown-menu">
										<li>
											<a href = "<?php echo DIR_WEB; ?>/admin/users/?members">Members</a>
										</li>
										<li>
											<a href = "<?php echo DIR_WEB; ?>/admin/users/?producers">Producers</a>
										</li>
									</ul>
								</li>
								<?php if(isset($this->pending_memberships)): ?>
									<li>
										<a href = "<?php echo DIR_WEB; ?>/admin/members/approve/">
											<i class = "icon-spaced icon-user"></i>
											<?php Lang::out('menu:approve_memberships'); ?>
											<span class = "approve-pending"><?php echo $this->pending_memberships; ?></span>
										</a>
									</li>
								<?php endif; ?>
								
								<?php if(isset($this->pending_producers)): ?>
									<li>
										<a href = "<?php echo DIR_WEB; ?>/admin/producers/approve/">
											<i class = "icon-spaced icon-user"></i>
											<?php Lang::out('menu:approve_producers'); ?>
											<span class = "approve-pending"><?php echo $this->pending_producers; ?></span>
										</a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; # admin nav ?>
				<?php endif; # if is authed ?>
				<div class = "container">
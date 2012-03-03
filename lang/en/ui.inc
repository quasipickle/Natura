<?PHP
#
# English language user interface files
#
# All English translations of user interface strings are stored here
#

# Generic terms
$lang['ui']['member'] = 'Member';

$lang['ui']['info:not_auth'] = 'Not authorized';
$lang['ui']['info:signup_terms'] = <<<TERMS
<h2>Terms of membership</h2>
<p>
	In addition to Stoney Creek Natural Foods Society bylaws governing member behaviour and responsibility, these additional terms of membership apply:
</p>
<ol>
	<li>
		Stoney Creek Natural Foods Society is not responsible for the quality of the products you purchase.  Product quality is the sole responsibility of the producer.
	</li>
	<li>
		The dollar amount you are required to pay on pickup day may differ from the amount indicated in your invoice.  This is unavoidable due to various factors (weather, etc).  It is the responsibility of the Member to pay the amount required on pickup day in full.  Failure to do so may result in revocation of Membership.
	</li>
	<li>
		Once an order is placed, you have 24 hours to modify it.  After 24 hours, your order is considered placed, and you are responsible for pickup and payment.
	</li>
</ol>
TERMS;
$lang['ui']['lbl:membership_term_agree'] = 'I agree to the terms of membership.';


# Text to display on the login button & link
$lang['ui']['btn:login']  = 'Login';
$lang['ui']['btn:logout'] = 'Logout';
$lang['ui']['btn:signup'] = 'Signup';

# Text to display as the window title.  
# The configuration parameter ORGANIZATION_NAME will be appended to this
$lang['ui']['ttl:page'] = 'Welcome to';

# Text to display as the title on the login page
$lang['ui']['ttl:login'] = 'Please login';

$lang['ui']['lbl:email'] = 'Email';
$lang['ui']['lbl:password'] = 'Password';

$lang['ui']['ttl:unknown_page'] = 'Unknown page';
$lang['ui']['info:unknown_page'] = '
<p>
	The requested page does not exist.
</p>
<p>
	If you typed this page address in manually, it is likely misspelled.  If you reached this page by following a link on this site, please contact the <a href = "mailto:'.CONTACT_SYSADMIN.'?subject=Broken link">site administrator</a>.
</p>';

# Caveat with regards to pricing.  Shows up on ordering page and order summaries
$lang['ui']['info:text_price'] = <<<CAVEAT
<p>
<strong>Please note:</strong> The prices and totals indicated on your order summary are a close approximation. The total amount due on pickup day may differ slightly with the variability in package weights (eg. meats), or with any necessary substitutions where there is insufficient stock (for example, when poor weather creates unpredictability in fresh produce crop volumes).
</p>
CAVEAT;


# Tab wording after logging in
$lang['ui']['menu:members'] = 'Members';
$lang['ui']['menu:producers'] = 'Producers';
$lang['ui']['menu:admin'] = 'Administration';

#
# Link text in tool bar
#
# Members
$lang['ui']['menu:place_order'] = 'Place order';
$lang['ui']['menu:view_current_order'] = 'View your orders';
$lang['ui']['menu:view_summaries'] = 'View your order summaries';
$lang['ui']['menu:signup_producer'] = 'Signup as a Producer';

# Producers
$lang['ui']['menu:create_product'] = 'New product';
$lang['ui']['menu:manage_products'] = 'Manage products';
$lang['ui']['menu:view_purchase_orders'] = 'Purchase orders';
$lang['ui']['menu:change_producer_profile'] = 'Edit your producer profile';


# Admin
$lang['ui']['menu:manage_cycles'] = 'Cycles';
$lang['ui']['menu:manage_categories'] = 'Categories';
$lang['ui']['menu:approve_memberships'] = 'Approve memberships';
$lang['ui']['menu:approve_producers'] = 'Approve producers';



# Landing members page
$lang['ui']['ttl:members'] = 'Members';
$lang['ui']['ttl:producers'] = 'Producers';
$lang['ui']['ttl:admin'] = 'Administration';


#
# Create new product page
#
$lang['ui']['ttl:create_product'] = 'Create a new product';
$lang['ui']['lbl:product_name'] = 'Name';
$lang['ui']['lbl:product_description'] = 'Description';
$lang['ui']['lbl:product_units'] = 'Units';

# should list some examples, and explain this value is merely to display to the user
$lang['ui']['info:product_units'] = 'The units this product is sold per, ex: "KG", "LB", "package of 6".  Not used to calculate anything, merely for display to the user.';

$lang['ui']['lbl:product_price'] = 'Price';

# should explain that the price is per unit
$lang['ui']['info:per_unit'] = 'per unit';

$lang['ui']['lbl:product_count'] = 'Count';

# should explain that the number will decrease as orders are placed, and to leave blank if unlimited
$lang['ui']['info:product_count'] = 'The total number of this product you have available.  This number will decrease as orders are placed. If your inventory is unlimited, leave blank.';

$lang['ui']['btn:create_product'] = 'Create product';
$lang['ui']['msg:product_create_success'] = 'Product created successfully.';



#
# Signup
#
$lang['ui']['ttl:signup'] = 'Signup';
$lang['ui']['msg:signup_success'] = <<<SIGNUP
You have successfully signed up.
<br /><br />
Once your membership has been approved, you will receive an email with further instructions.
SIGNUP;
$lang['ui']['lbl:member_first_name'] = 'First name';
$lang['ui']['lbl:member_last_name'] = 'Last name';
$lang['ui']['lbl:member_email'] = 'Email';
$lang['ui']['lbl:member_confirm_email'] = 'Confirm email';
$lang['ui']['lbl:member_password'] = 'Password';
$lang['ui']['lbl:member_password_confirm'] = 'Confirm password';
$lang['ui']['lbl:member_phone'] = 'Phone';
$lang['ui']['info:fields_required'] = 'All fields are required.';
$lang['ui']['info:fields_required_specified'] = 'All fields are required unless otherwise specified.';
$lang['ui']['info:optional'] = 'Optional';

# Signup as a producer
$lang['ui']['btn:producer_signup'] = 'Become a producer';
$lang['ui']['ttl:producer_signup'] = 'Signup to become a producer';
$lang['ui']['info:producer_signup'] = <<<ABOUT
<p>
	Signing up as a producer allows you to sell your products via this online store
</p>
<ul>
	<li>
		You will still be able to purchase goods like a regular member.
	</li>
	<li>
		Becoming a producer is dependant on approval by the store administrator.
	</li>
</ul>
ABOUT;
$lang['ui']['info:update_later'] = 'You can update this information later if necessary.';
$lang['ui']['lbl:business_name'] = 'Business name';
$lang['ui']['lbl:business_about'] = 'About the business';
$lang['ui']['info:business_about'] = 'A few short words about your business, the types of goods you sell, etc.  <strong>Not to be used as an exhaustive list of your products.</strong>';
$lang['ui']['lbl:business_email'] = 'Business email address';
$lang['ui']['info:business_email'] = <<<EMAIL
The email address members can use to contact you for business matters, and the online store will use to contact you for purchase orders.  This will be publicly visible.
<br />
If not provided, the online store will use your member email, but your member email will <strong>not</strong> be publicly visible.
EMAIL;
$lang['ui']['lbl:business_phone'] = 'Business phone number';
$lang['ui']['info:business_phone'] = <<<EMAIL
The phone number members can use to contact you for business matters. This will be publicly visible.
<br />
If not provided, the store administrator may use your member phone number, but your member phone number will <strong>not</strong> be publicly visible.
EMAIL;
$lang['ui']['lbl:producer_signup'] = 'Signup to become a producer';
$lang['ui']['msg:producer_signup_success'] = 'You have successfully signed up as a producer.<br /><br />Once the system administrator approves your application, you will be emailed further instructions.';


# Edit producer profile
$lang['ui']['ttl:producer_profile_edit'] = 'Edit your producer profile';
$lang['ui']['btn:producer_profile_edit'] = 'Update profile';
$lang['ui']['msg:producer_profile_success'] = 'Profile updated';

# Logout
$lang['ui']['msg:logout_success'] = 'You have logged out.';

# Approving
$lang['ui']['ttl:member_approve'] = 'Approve memberships';
$lang['ui']['lbl:member_id'] = 'ID';
$lang['ui']['info:member_approve_none'] = 'There are no new memberships to approve.';
$lang['ui']['msg:member_approve_success'] = 'Membership approved';
$lang['ui']['btn:approve'] = 'Approve';

$lang['ui']['ttl:producers_approve'] = 'Approve producers';
$lang['ui']['msg:producer_approve_success'] = 'Producer approved';
$lang['ui']['info:producers_approve_none'] = 'There are no new producers to approve.';

# Products
$lang['ui']['ttl:products'] = 'Manage products';
$lang['ui']['msg:product_deleted'] = 'Product deleted. Any orders placed for this product will still contain this product.';
$lang['ui']['info:products_none'] = 'You have not created any products.';

# Edit product
$lang['ui']['ttl:product_edit'] = 'Edit product';
$lang['ui']['btn:product_save'] = 'Save changes';
$lang['ui']['msg:product_save_success'] = 'Product edited successfully.';
$lang['ui']['btn:product_edit'] = 'Edit';
$lang['ui']['btn:delete_product'] = 'Delete';
$lang['ui']['lbl:product_categories'] = 'Categories';

# Manage cycles
$lang['ui']['ttl:cycles_manage'] = 'Manage cycles';
$lang['ui']['ttl:cycle_current'] = 'Current cycle';
$lang['ui']['info:cycle_current_none'] = 'There is no current cycle.';

$lang['ui']['btn:cycle_create_cancel'] = 'Cancel';
$lang['ui']['ttl:cycles_future'] = 'Future cycles';
$lang['ui']['info:cycles_future_none'] = 'There are no future cycles.';
$lang['ui']['ttl:cycles_past'] = 'Past cycles';
$lang['ui']['info:cycles_past_none'] = 'There are no past cycles.';

$lang['ui']['ttl:cycle_edit'] = 'Edit a cycle';
$lang['ui']['btn:cycle_edit'] = 'Edit';
$lang['ui']['btn:cycle_edit_cancel'] = 'Cancel';
$lang['ui']['msg:cycle_edit_success'] = 'Cycle successfully edited.';

$lang['ui']['btn:cycle_delete'] = 'Delete';
$lang['ui']['msg:cycle_delete_success'] = 'Cycle successfully deleted.';

$lang['ui']['btn:cycle_end_today'] = 'End today';
$lang['ui']['msg:cycle_end_success'] = 'Cycle ended.  It will be active until 11:59:59 PM tonight.';

$lang['ui']['btn:cycle_send_emails'] = 'Send emails';
$lang['ui']['btn:cycle.download.files'] = 'Get files';
$lang['ui']['btn:cycle.download.txt'] = 'Get files (TXT)';
$lang['ui']['btn:cycle.download.csv'] = 'Get files (CSV)';


# Cycles
$lang['ui']['ttl:cycles_create'] = 'Create a new cycle';
$lang['ui']['btn:cycles_create_go'] = 'Create a new cycle&hellip;';
$lang['ui']['btn:cycle_create'] = 'Create cycle';
$lang['ui']['info:cycle_start'] = 'Format: YYYY-MM-DD.  Cycle will begin at midnight of this day.';
$lang['ui']['info:cycle_end'] = 'Format: YYYY-MM-DD.  Cycle will end at 11:59:59 PM of this day.';
$lang['ui']['msg:cycle_created'] = 'Cycle successfully created.';
$lang['ui']['msg:cycle_emails_sent'] = 'Emails sent.';
$lang['ui']['lbl:cycle_id'] = 'ID';
$lang['ui']['lbl:cycle_name'] = 'Name';
$lang['ui']['lbl:cycle_start'] = 'Start';
$lang['ui']['lbl:cycle_end'] = 'End';
$lang['ui']['lbl:cycle_category'] = 'Categories';
$lang['ui']['info:cycle_category'] = 'Check as many categories as you want.  Only products in the checked categories will be available for this cycle';


# Ordering
$lang['ui']['info:order'] = <<<ABOUT
This page allows you to place an order for the current order cycle.  Your order can be changed for 24 hours after it is first placed, or until the order cycle closes.
ABOUT;
$lang['ui']['info:order_view'] = <<<ABOUT
This page allows you to view and change an order you've already placed.  Orders can be changed for 24 hours after they are first placed, or until the order cycle closes.
ABOUT;
$lang['ui']['lbl:cycle_choose'] = 'Choose an order cycle';
$lang['ui']['info:order_cycle_list'] = 'Choose a cycle to view your order for that cycle.';
$lang['ui']['btn:load_cycle_products'] = 'Show the Products available for the Cycle';
$lang['ui']['btn:show_order_for_cycle'] = 'Show my order for this cycle';
$lang['ui']['msg:order_already'] = 'You have already placed an order for this cycle. <a href = "'.DIR_WEB.'/members/order/view">View your existing order &raquo;</a>.';
$lang['ui']['msg:order.none'] = "You haven't yet placed an order for this cycle. <a href = '".DIR_WEB."/members/order/new/'>Create order &raquo;</a>";
$lang['ui']['msg:order_products_none'] = 'There are currently no products for sale.';
$lang['ui']['info:inventory_unlimited'] = 'Unlimited';
$lang['ui']['lbl:product'] = 'Product';
$lang['ui']['lbl:available'] = '# Available';
$lang['ui']['info:order_cycle_next'] = 'The next available order cycle starts ';
# Removed because I think it's only used on the members/order/view page & it's not really appropriate there
#$lang['ui']['info:order_cycle_none'] = 'There is no order cycle currently running.';
$lang['ui']['info:no_past_orders'] = 'You have no orders to view.';
$lang['ui']['info:no_past_order_summaries'] = 'You have no order summaries to view.';
$lang['ui']['msg:order_saved'] = 'Order saved';
$lang['ui']['msg:order_edit_window_expired'] = 'You cannot update this order because it was first placed more than 24 hours ago.';
$lang['ui']['btn:back_to_orders'] = '&laquo; Back to your orders list';

$lang['ui']['msg:order_placed'] = 'Your order has been placed. <a href = "'.DIR_WEB.'/members/order/view/?id=%ID%">View/Change your order</a>';
$lang['ui']['btn:order_place'] = 'Place order';
$lang['ui']['btn:order_update'] = 'Update order';
$lang['ui']['btn:order_show_ordered'] = 'Show only ordered products&hellip;';
$lang['ui']['btn:order_show_all'] = 'Show all products';
$lang['ui']['ttl:new_order'] = 'New order';
$lang['ui']['ttl:view_order'] = 'View order';
$lang['ui']['lbl:cycle_name'] = 'Cycle name';
$lang['ui']['lbl:cycle_start'] = 'Cycle start';
$lang['ui']['lbl:cycle_end'] = 'Cycle end';
$lang['ui']['lbl:order_placed'] = 'Order placed';

# Viewing purchase orders
$lang['ui']['ttl:purchase_orders'] = 'Purchase orders';
$lang['ui']['msg:purchase_orders_none'] = 'There are no orders for your products this cycle.';
$lang['ui']['lbl:total'] = 'Total';
$lang['ui']['lbl:grand_total'] = 'Grand total';
$lang['ui']['info:order_amounts_not_final'] = "These amounts are for orders in an order cycle that is currently running.  These amounts may increase or decrease before the cycle is over.";
$lang['ui']['lbl:cycle'] = 'Cycle';
$lang['ui']['lbl:cycles_past_view'] = 'View past cycles';
$lang['ui']['btn:view'] = 'View';
$lang['ui']['lbl:invoice_num'] = 'Invoice #:';
$lang['ui']['lbl:orders_individual'] = 'Individual orders';
$lang['ui']['lbl:orders_download_csv'] = 'Download for spreadsheet (CSV)';
$lang['ui']['lbl:orders_download_txt'] = 'Download as text';

# Viewing order summaries
$lang['ui']['ttl:summaries'] = 'Summaries';
$lang['ui']['info:summaries'] = 'This page allows you to view/download order summaries from past order cycles.  Your summary for any active cycle will be available once the cycle has ended.';
$lang['ui']['lbl:cycles_past'] = 'Past cycles';
$lang['ui']['lbl:summary_num'] = 'Summary #';
$lang['ui']['lbl:summary_download_csv'] = 'Download for spreadsheet (CSV)';
$lang['ui']['lbl:summary_download_txt'] = 'Download as text';


# Forgotten password
$lang['ui']['ttl:forgot'] 				= 'Forgotten password';
$lang['ui']['btn:forgot'] 				= 'Forgot password';
$lang['ui']['info:forgot'] 				= 'Enter your email address and a link will be sent to that address. Follow that link to reset your password. <strong>The link will only work for 24 hours.</strong>';
$lang['ui']['btn:forgot_submit'] 		= 'Send email';
$lang['ui']['msg:forgot_email_sent'] 	= 'Email sent. It may take up to 4 hours to arrive.';
$lang['ui']['btn:reset_password'] 		= 'Reset my password';
$lang['ui']['lbl:new_password'] 		= 'New password';
$lang['ui']['lbl:confirm_password'] 	= 'Confirm password';
$lang['ui']['msg:password_reset'] 		= 'Your password has been reset.  You can now <a href = "'.SITE_URL.'/login/">login</a>';

# Categories
$lang['ui']['ttl:manage_categories'] = 'Manage Categories';
$lang['ui']['info:categories_about'] = 'Categories can be used to organize products &amp; facilitate order cycles.  Each product can be in multiple categories, and each order cycle can have products from multiple categories.';
$lang['ui']['msg:no_categories'] = 'There are no categories';
$lang['ui']['lbl:category'] = 'Category';
$lang['ui']['btn:delete_category'] = 'Delete';
$lang['ui']['btn:create_category'] = 'Create Category';
$lang['ui']['lbl:new_category'] = 'New Category';
$lang['ui']['msg:category_created'] = 'New Category created.';
$lang['ui']['msg:category_deleted'] = 'Category deleted.';

# Member & Producer list
$lang['ui']['ttl:list_members'] = 'Members';
$lang['ui']['info:no_members'] = 'No members found';

$lang['ui']['ttl:list_producers'] = 'Producers';
$lang['ui']['info:no_producers'] = 'No producers found';



?>
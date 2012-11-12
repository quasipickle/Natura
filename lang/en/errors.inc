<?PHP
#
# English language error files
#
# All English translations of error messages are stored here
#

# The generic error whenever the DB class gets a database error when executing a query
$lang['err']['db.query'] = 'A query could not be completed due to a database error (#%ERRNO%): %ERROR%';

# Shown when a login fails due to an invalid username or password
$lang['err']['login.wrong'] = 'Invalid credentials.  Please check the email/password and try again.';

# Shown when a login fails because the session id could not be created
$lang['err']['login.generate_session_failed'] = 'Could not login because a unique login ID could not be determined.';

# Show when the session cannot be created.  Most users don't know about sessions, so use simple language
$lang['err']['login.create_session_failure'] = 'Unable to login.';

# Show when the session cannot be verified due to a database error
$lang['err']['session.unable_check'] = 'Unable to create session.';

# Show when the session could not be extended
$lang['err']['session.unable_extend'] = 'Unable to extend your session.';

# Shown when a user doesn't provide a necessary piece of information when signing up
$lang['err']['signup.no_first_name'] = 'Please enter your first name.';
$lang['err']['signup.no_last_name'] = 'Please enter your last name.';
$lang['err']['signup.no_password'] = 'Please enter a password.';
$lang['err']['signup.no_confirm_password'] = 'Please confirm your password';
$lang['err']['signup.no_email'] = 'Please enter an email address.';
$lang['err']['signup.no_confirm_email'] = 'Please confirm your email address.';
$lang['err']['signup.no_agree'] = 'You must agree to the terms of membership.';

# Shown when a confirm field doesn't match when signing up
$lang['err']['signup.no_password_match'] = 'Passwords do not match.';
$lang['err']['signup.no_email_match'] = 'Email addresses do not match.';

$lang['err']['signup.existing_email'] = 'You cannot signup with that email address, as it has already been used.';

# Shown when signup has failed - due to a database error
$lang['err']['signup.failed'] = 'Signup has failed.  Please try again later.';

$lang['err']['signup.producer.no_name'] = 'Please provide a business name.';
$lang['err']['signup.producer.no_about'] = 'Please provide some information about your business.';
$lang['err']['signup.producer.about_length'] = 'Your "About" is too long.';
$lang['err']['signup.producer.already'] = 'You have already signed up as a producer.';
$lang['err']['signup.producer.error'] = 'Producer signup failed due to a database error.';

$lang['err']['product.no_producer'] = 'No producer ID was provided.';
$lang['err']['product.no_name'] = 'Please provide a name.';
$lang['err']['product.no_desc'] = 'Please provide a description.';
$lang['err']['product.no_units'] = 'Please provide the product units.';
$lang['err']['product.no_price'] = 'Please provide a price';

$lang['err']['product.price_not_numeric'] = 'Please enter a number for the price.';
$lang['err']['product.count_not_integer'] = 'Please enter a whole number for the inventory count (ie: not a decimal).';

$lang['err']['product.load.fail'] = 'Product could not be loaded due to a database error.';
$lang['err']['product.category.load.fail'] = 'The Categories for the Product could not be loaded due to a database error.';
$lang['err']['product.load.none'] = 'Product could not be loaded because no product #%NUMBER% exists.';
$lang['err']['products.load.error'] = 'Products could not be loaded due to a database error.';
$lang['err']['products.load.categories.error'] = 'Product categories could not be loaded due to a database error.';

$lang['err']['product.create.no_producer'] = 'Product could not be created because the provided producer #%NUMBER% does not exist.';
$lang['err']['product.create.fail'] = 'Product could not be created due to a database error.';


$lang['err']['product.delete_categories.fail'] = 'Existing Category associations could not be deleted due to a database error.';
$lang['err']['product.delete.not_owner'] = 'You cannot delete this product as you are not the owner of it.';
$lang['err']['product.delete.not_exist'] = 'You cannot delete this product as it does not exist.';
$lang['err']['product.delete.error'] = 'The product could not be deleted due to a database error.';
$lang['err']['product.delete.order_check_error'] = 'The product could not be deleted because a check to see if it has already been ordered, failed.';
$lang['err']['product.deactivate.error'] = 'Cannot de-activate product due to a database error';
$lang['err']['product.deactivate.none'] = 'Product was not de-activated.';
$lang['err']['product.edit.fail'] = 'Product could not be edited due to a database error.';
$lang['err']['product.add_categories.fail'] = 'Categories could not be saved for the Product due to a database error.';

#
# Loading pending members
#
$lang['err']['member.pending_load.error'] = 'Pending members could not be loaded due to a database error.';

#
# Member object
#
$lang['err']['member.load.error'] = 'The member with ID #%ID% could not be loaded due to a database error.';
$lang['err']['member.load.no_exist'] = "The member with ID #%ID% could not be loaded because they don't exist";
$lang['err']['member.approve.error'] = 'The member with ID #%ID% could not be approved due to a database error.';
$lang['err']['member.approve.none'] = 'The member with ID #%ID% was not approved.';
$lang['err']['member.approve.unnecessary'] = 'That member does not need to be approved.';

#
# Producer object
#
$lang['err']['producer.load.error'] = 'The producer with ID #%ID% could not be loaded due to a database error.';
$lang['err']['producer.load.no_exist'] = "The producer with ID #%ID% could not be loaded because they don't exist";
$lang['err']['producers.pending_load.error'] = 'Pending producers could not be loaded due to a database error.';
$lang['err']['producer.approve.error'] = 'The producer with ID #%ID% could not be approved due to a database error.';
$lang['err']['producer.approve.none'] = 'The producer with ID #%ID% was not approved.';
$lang['err']['producer.approve.unnecessary'] = 'That producer does not need to be approved.';
$lang['err']['producer.approve.update_level.error'] = "The member's level could not be updated to reflect the fact they are now a producer, due to a database error.";
$lang['err']['producer.approve.update_level.none'] = "The member's level was not updated to reflect the fact they are now a producer.";
$lang['err']['producer.cycles.old'] = "There was an error while trying to load your old cycles.";
$lang['err']['producer.update.error'] = 'The producer profile could not be saved due to a database error.';
$lang['err']['producer.generate_purchase_order.fail'] = 'The purchase order for producer #%ID% could not be created.';
#
# Cycle object
#
$lang['err']['cycle.load.error'] = 'The cycle with ID #%ID% could not be loaded due to a database error.';
$lang['err']['cycle.load_categories.fail'] = 'The categories for the Cycle could not be loaded due to a database error.';
$lang['err']['cycle.load.no_exist'] = 'The cycle with ID #%ID% could not be loaded because it does not exist.';
$lang['err']['cycle.create.overlap.error'] = 'The cycle could not be created because the check to determine overlap failed.';
$lang['err']['cycle.create.error'] = 'Cycle could not be created due to a database error.';
$lang['err']['cycle.create.name.empty'] = 'Please provide a name.';
$lang['err']['cycle.create.no_start'] = 'Please provide a start date.';
$lang['err']['cycle.create.no_end'] = 'Please provide an end date.';
$lang['err']['cycle.add_categories.fail'] = 'Categories were not saved for the Cycle due to a database error.';
$lang['err']['cycle.create.early'] = 'You cannot create a cycle that begins earlier than today.';
$lang['err']['cycle.create.backwards'] = 'You cannot create a cycle that ends before it begins.';

$lang['err']['cycle.delete.now'] = 'You cannot delete this cycle because it is in progress.  You can only end current cycles';
$lang['err']['cycle.delete.past'] = 'You cannot delete a cycle that has already ended.';
$lang['err']['cycle.delete.error'] = 'The cycle could not be deleted due to a database error.';
$lang['err']['cycle.delete.none'] = 'The cycle was not deleted.';
$lang['err']['cycle.delete_categories.fail'] = 'Cycle categories were not deleted due to a database error.';

$lang['err']['cycle.end.error'] = 'The cycle was not ended due to a database error.';
$lang['err']['cycle.end.none'] = 'The cycle was not ended.';
$lang['err']['cycle.load_current.error'] = 'The current order cycle could not be loaded due to a database error.';
$lang['err']['cycle.load_next.error'] = 'The next order cycle could not be loaded due to a database error.';
$lang['err']['cycle.load_previous.error'] = 'The previous order cycle could not be loaded due to a database error.';

#
# List object
#
$lang['err']['list.type_not_found'] = 'Unable to load a list of objects, as the object type (%TYPE%) was not found.';
$lang['err']['list.load.fail'] = 'Unable to load a list of objects due to a database error';

#
# Edit a cycle
#
$lang['err']['cycle.edit.error'] = 'Cycle was not edited due to a database error.';

#
# Cycles page
#
$lang['err']['cycles.load.error'] = 'Cycles could not be loaded due to a database error.';
$lang['err']['cycle.file_generation_failure'] = 'At least one invoice/PO could not be generated.';
$lang['err']['zip.library_missing'] = 'Unable to generate ZIP file as the zip-file-generating code could not be found.';
$lang['err']['zip.file_cannot_open'] = 'ZIP file could not be created.';
$lang['err']['zip.file_cannot_close'] = 'ZIP file could not be created because there was a problem adding invoices or POs.';
$lang['err']['cycle.download.none'] = 'ZIP file could not be created, as no orders were placed';

#
# Ordering
#
$lang['err']['order.products.load.error'] = 'Unable to load products for ordering due to a database error.';
$lang['err']['order.products.empty'] = 'No order items were provided.';
$lang['err']['order.products.no_count'] = 'Please order at least one item.';
$lang['err']['order.products.amount.bad'] = 'Please enter only whole numbers (1,2,3...etc) as amounts.';
$lang['err']['order.lock.unable'] = 'Your order cannot be completed due to a database error while trying to prevent other orders while yours is being placed.';
$lang['err']['order.inventory.check'] = 'Your order cannot be completed due to a database error while making sure there is enough inventory for your order.';
$lang['err']['order.inventory.inadequate'] = 'Your order was not placed because there is not enough inventory.';
$lang['err']['order.unlock.unable'] = 'The database could not be unlocked after placing your order.';
$lang['err']['order.transaction.start.fail'] = 'Your order could not be placed because the order could not be started.';
$lang['err']['order.transaction.commit.fail'] = 'Your order could not be placed because it could not be completed.';
$lang['err']['order.items.fail'] = 'Your order could not be placed because the items could not be recorded.';
$lang['err']['order.order.fail'] = 'Your order could not be placed because the order could not be created.';
$lang['err']['order.exists'] = 'You have already placed an order for this order cycle.';
$lang['err']['order.amounts.load.fail'] = 'Unable to load your orders due to a database error';
$lang['err']['order.decriment.fail'] = 'Unable to reduce the inventory total to reflect your order.';
$lang['err']['order.not_yours'] = "The requested Order doesn't belong to you.";
$lang['err']['order.load.fail'] = 'Unable to load your order due to a database error: %ERROR%';
$lang['err']['order.load.none'] = 'Unable to load your order - it does not exist.';
$lang['err']['order.generate_invoice.fail'] = 'The invoice for Order #%ID% could not be created.';

$lang['err']['order.delete.fail'] = 'Existing order could not be deleted due to a database error.';
$lang['err']['order.increment.fail'] = 'Unable to reset product inventory amounts after order was deleted.';
$lang['err']['cycles_load_fail'] = 'Current ordering cycles could not be loaded due to a database error';
$lang['err']['order.time_placed.fail'] = 'The order was placed, but Natura was unable to determine when it was placed.';

// this value should be duplicated in ui.inc
$lang['err']['order.edit_window.expired'] = 'You cannot update this order because it was first placed more than 24 hours ago';

$lang['err']['producer.cycles.load'] = 'Relevant old cycles could not be loaded.';
$lang['err']['members.cycles.load'] = 'Old cycle invoices could not be loaded.';
$lang['err']['members.invoice.find'] = 'Unable to find your invoice.';

$lang['err']['mail_not_sent'] = 'Email was not sent.';
$lang['err']['mail_send_failures'] = 'Your email was sent, but some recipients were rejected: %ADDRESSES%.';


#
# Forgotten passwords
#
$lang['err']['forgot_email_blank'] = 'Please enter your email address';
$lang['err']['reset_retrieve_fail'] = 'The reset code could not be retrieved due to a database error.';

$lang['err']['reset_retrieve_none'] = 'The reset code is not valid.  It may have been deleted because it expired.  Please <a href = "'.DIR_WEB.'/forgot/">submit another request to reset your password</a>.';
$lang['err']['reset_generate_code_fail'] = 'A new reset code could not be created.';
$lang['err']['reset_save_fail'] = 'The reset code could not be saved.';
$lang['err']['load_member_by_email_fail'] = 'The ID of the member this code is for could not be found due to a database error.';
$lang['err']['load_member_by_email_none'] = "The provided code isn't associated with a member.";
$lang['err']['set_password_fail'] = 'Your password could not be set due to a database error.';


#
# Categories
#
$lang['err']['category_load_fail'] = 'Category could not be loaded due to a database error.';
$lang['err']['category_load_none'] = 'Category could not be loaded because no Category exists with the specified internal name.';
$lang['err']['new_category_empty'] = 'Please provide a new category name.';
$lang['err']['new_category_not_unique'] = 'The entered Category, or one very similarly named, already exists.';
$lang['err']['new_category_fail'] = 'The Category could not be created due to a database error.';
$lang['err']['category_delete_fail'] = 'The Category could not be deleted due to a database error.';
$lang['err']['category_delete_product_assoc_fail'] = 'Product associations with this Category were not deleted due to a database error.';

?>
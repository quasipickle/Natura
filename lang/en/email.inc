<?PHP
#
# English language email files
#
# All English translations of email subjects & contents are stored here
#

$lang['site_url'] = SITE_URL;//CONSTANTS aren't interpreted in heredocs
$lang['organization_name'] = ORGANIZATION_NAME;

# Member created
$lang['email']['member.created.subject'] = 'A new member has signed up';
$lang['email']['member.created.body'] = <<<MSG
%FIRST_NAME% %LAST_NAME% has just signed up.

Login to {$lang['site_url']}/login/ to approve or deny their membership.
MSG;

# Member approved
$lang['email']['member.approved.subject'] = 'Your membership has been approved';
$lang['email']['member.approved.body'] = <<<MSG
Your membership with {$lang['organization_name']} has been approved.

You may now login to {$lang['site_url']}/login/ to place orders (once an order cycle has started)
MSG;

# Producer created
$lang['email']['producer.created.subject'] = 'A new producer has signed up';
$lang['email']['producer.created.body'] = <<<MSG
%BUSINESS_NAME% has just applied to be a producer.

Login to {$lang['site_url']}/login/ to approve or deny their producership.
MSG;

# Producer approved
$lang['email']['producer.approved.subject'] = 'You have been approved as a producer';
$lang['email']['producer.approved.body'] = <<<MSG
Your business, %BUSINESS_NAME%, has been approved as a producer.

You may now login to {$lang['site_url']}/login/ to edit your producer profile, and manage your products
MSG;

# Cycle over: Member
$lang['email']['cycle.complete.member.subject'] = 'Ordering cycle has completed';
$lang['email']['cycle.complete.member.body'] = <<<MSG
The ordering cycle "%NAME%" (%FROM% > %TO%) has completed.  Please login to {$lang['site_url']}/login/ to view your invoice.
MSG;

# Cycle over: Producer
$lang['email']['cycle.complete.producer.subject'] = 'Ordering cycle has completed';
$lang['email']['cycle.complete.producer.body'] = <<<MSG
The ordering cycle "%NAME%" (%FROM% - %TO%) has completed.  Please login to {$lang['site_url']}/login/ to view your purchase orders.
MSG;

# Forgotten password
$lang['email']['reset.subject'] = ORGANIZATION_NAME.': Forgotten password';
$lang['email']['reset.message'] = 'Someone has requested a password reset for '.ORGANIZATION_NAME.".  If this was you, please click the link below to reset your password. If this was not you, please disregard this message.\n\n{$lang['site_url']}/forgot/?c=%CODE%";

?>
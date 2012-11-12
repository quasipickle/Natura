Natura Installation
===================

1. Download the Zip file
2. Upload the contents to the folder you want Natura installed into.
3. Create your database.
4. Import `db.sql` (included in the zip file) into your new database.
5. Update `config-empty.inc.php` to point to your new database, and change other installation-specific properties.
6. Rename `config-empty.inc.php` to `config.inc.php`
7. Download [PHPExcel](http://phpexcel.codeplex.com/). *Has been tested with 1.7.6, should work with .7 & .8* .  Create a `phpexcel` folder in `/libraries` and upload the `PHPExcel` directory and `PHPExcel.php` file that were included in the PHPExcel folder.

Logging in
----------
Natura comes with default admin account of admin@foobar.com // blah .  There is no built-in way to change a user's email address, so you'll have to edit the database manually to change the admin email address.  Once you've done that, you can go through the "Forgot password" process to change the default password.
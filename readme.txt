=== Password Hash ===
Contributors: wherd
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CPGWMY2N7CTBW
Tags: bcrypt, password, hash, security, password_hash, password_verify
Requires at least: 4.6
Tested up to: 4.7
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Replaces wp_hash_password and wp_check_password with PHP password_hash and password_verify if PHP has support for them.

== Description ==

Password Hash is a WordpPress plugin that improves the WordPress password hashing security by using the builtin [`password_hash`](http://php.net/manual/en/function.password-hash.php) and [`password_verify`](http://php.net/manual/en/function.password-verify.php) functions.

If the installed PHP version doesn't support those methods, it falls back to the standard PHPass method.

== Installation ==

This section describes how to install the plugin and get it working.

= Normal plugin =

1. Upload the plugin files to the `/wp-content/plugins/whrd-password-hash` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

= Must-use plugin =

1. Upload the `whrd-password-hash.php` file into `/wp-content/mu-plugins` folder.

== Changelog ==

= 1.0.0 =
* Initial release.

== Frequently Asked Questions ==

== Upgrade Notice ==

== Screenshots ==

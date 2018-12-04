=== WP HTML Mail - WooCommerce ===
Contributors: haet
Tags: email, template, html mail, mail, message, ninja-forms, wp-e-commerce, caldera-forms, wp-e-commerce
Requires at least: 3.9
Tested up to: 4.9.8
Stable tag: 2.8.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Beautiful responsive HTML mails, fully customizable without any coding knowledge

== Description ==
Beautiful responsive HTML mails, fully customizable without any coding knowledge 
Create your own professional email template within a few minutes without any coding skills. 
Easily change texts, colors, fonts, pictures, alignment and see all your changes immediately in the live preview.


== Installation ==
Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.


== Changelog ==

= 2.8.2 = 
* added support for German Market Double Opt In Email
* added [ORDER_NOTES] placeholder for notes a customer could have entered during checkout. (this field was and is also part of CUSTOMER_DETAILS)


= 2.8.1 = 
* avoid creation of email instances in replace_email_subjects()


= 2.8 =
* allow placeholders for name, company and more in WooCommerce password reset mail
* added settings link to plugin overview and WooCommerce email settings
* added filter to add supported WooCommerce emails haet_mail_supported_woocommerce_emails
* added reset buttons to delete preview settings or all settings (requires wp-html-mail 2.8 or higher)


= 2.7.9 =
* improved compatibility with German Market invoice numbers
* improved compatibility with dynamic pricing plugin


= 2.7.8.3 =
* added WC compatibility tag
* support YITH barcodes now


= 2.7.8 =
* improved MailBuilder


= 2.7.7 =
* added filter to insert custom placeholders to your emails
* removed my own function for customer details in favor of a customized template file


= 2.7.6 =
* The WooCommerce Settings Tab was not displayed correctly if all orders have been deleted


= 2.7.5 =
* Improved CSS inlining
* Fixed output of item meta


= 2.7.4 =
* Subject for German Market order confirmation was not replaced
* Fixed a problem with Paypal Plus and user account emails


= 2.7.3 =
* Fix for "Small Business Regulation" with WooCommerce German Market


= 2.7.2 =
* Fixed a bug with preview of WooCommerce Subscription emails


= 2.7.1 =
* Works with WooCommerce Waitlist now
* Fixed division by zero in case of free order items
* Works with [WooCommerce Dynamic Pricing Discounts](https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts/7119279) now

= 2.7 =
* Mailbuilder shows preview now
* Handles empty orders now
* Detects email templates in theme now
* added padding to body (looks better if someone replies)


= 2.6 =
* added code button to editor toolbar
* added tables to textareas
* added many new placeholders



= 2.5.2 = 
* Fixed a bug blocking PayPal redirect during checkout in some cases


= 2.5.1 = 
* Removed a few notices appearing in WooCommerce 3.x


= 2.5 =
* Added attachments to mailbuilder


= 2.4.1 =
* Fixed a compatibility issue with paypal express ( Fatal error: Call to a member function needs_shipping() )


= 2.4 =
* Removed a closing DIV tag causing other plugins metaboxes to overlay the mailbuilder
* Improved responsive display in Gmail App
* Improved scaling for Outlook with high DPI setting


= 2.3.1 =
* Fixed preview of WooCommerce Germanized Invoice Cancellation email


= 2.3 =
* Copy and paste table settings from one email to another
* Fixed a bug with PayPal redirection


= 2.2.1 =
* Fixed a bug with WGM Order confirmation being cut off


= 2.2 =
* Improved compatibility with WooCommerce Germanized and other WooCommerce addons sending additional emails


= 2.1 =
* Improved to work with WooCommerce German Market


= 2.0.5 =
* Related products have not been saved without editing content


= 2.0.4 =
* Fixed duplicate headlines in global template mode

= 2.0.3 =
* Fixed a bug with related products
* Fixed password reset email
* Fixed New Account email
* Added German and Brasilian translations


= 2.0.2 =
Restore a few hidden WooCommerce Email settings

= 2.0.1 = 
Fixed a bug causing an error message if an order used for preview has been deleted

= 2.0 = 
Added Drag&Drop Mailbuilder to customize email content

= 1.2.1 =
Updated the admin-new-order template

= 1.2 =
Fixed a warning and improved compatibility with other woocommerce plugins

= 1.1 = 
Added support for custom WooCommerce templates 

= 1.0 =
* initial release

== Upgrade Notice ==

== Frequently Asked Questions ==
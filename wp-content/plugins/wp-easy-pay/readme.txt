=== WP Easy Pay - With Square ===
Plugin URI: https://wpexperts.io/
Contributors: wpexpertsio
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=pay@objects.ws&item_name=DonationForPlugin
Tags: square,squareup, wordpress pay,Square for wordpress, square payments,square checkout
Requires at least: 4.5.0
Tested up to: 5.0.1
Stable tag: 2.2
License: GPLv2 or later
Requires PHP: 5.5
License URI: http://www.gnu.org/licenses/gpl-2.0.html

In a few simple steps you can start accepting credit card payments with Square Checkout on your WordPress site.

== Description ==

Easily collect Square payments for simple payments or donations online without coding it yourself or hiring a developer. Skip setting up a complex shopping cart system.

== PlUGIN FEATURES ==

* Collect donation and **simple payment** from single button.
* User can enter custom amount to make payment for donation.
* Square card payment API support is currently available in US, Canada,UK, Australia and Japan only.
* Sandbox support is available for developer testing.
* Notification email will send to admin on successful transaction.

== How To Use ==

* Main admin menu item: Square account Settings.
* Second sub menu item is button settings page.
* When payment success email will send to notification email.
* When using for Donation check on user set a Donation amount otherwise leave unchecked to collect fixed amount

== PREMIUM FEATURES ==

* Accept payment in three different ways: **simple payment**, **donation** and **subscription**.
* Create **multiple button**, each button for each page of your site.
* Place shortcode to show payment gateway at any page.
* **Form builder** feature will let you customize form according to your requirement.
* Send selected fields in **Square Transaction Note** (60 characters Only).
* Admin and User will receive **Notification Email** on successful payment.
* User can enter **custom amount**.
* **Sandbox support** is available to test functionality before moving to production phase.
* Whenever user click on a button, you have an option show payment form in a **modal / popup**. 
* Get **Reports** of your transactions.
* Follow our [Price Plan](https://wpeasypay.com/pricing/) and choose one which fits perfect for your requirement 

= Requirements: =
* [Get Square Account.](http://apiexperts.io/link/square-partners/)
* Valid SSL certificate.
* WordPress 4.4+
* PHP version 5.5 or greater

= Important Notes: =
* Square card payment API support is currently available in US, Canada,UK, Australia and Japan only.
* On your payment button page SSL certificate must be activated for Square payments credit card form.
* This Plugin does not synchronize your products between WooCommerce and Square. If you are interested in this feature then [WooSquare Pro](https://goo.gl/LEJeQG) is the right option for you which include square payment gateway as well.

= More Square Solutions: =

* [WooSquare Pro - Manage Inventory, Auto Sync & Accept Online Payments.](https://goo.gl/LEJeQG)
* [Woocommerce Square Payment Gateway.](https://goo.gl/hgLMoA)
* [WooSquare - Try Manual Sync With Free Version.](https://wordpress.org/plugins/woosquare/)
* [Contact Form 7 With Square Payment Gateway.](http://apiexperts.io/solutions/contact-form-7-square-payment-add/)
* [Gravity Forms Square Payment Gateway.](http://apiexperts.io/solutions/square-for-gravity-forms/)
* [Give Wp Donation Via Square.](https://apiexperts.io/solutions/square-for-givewp/)
* [Woocommerce Subscription With Square Recurring.](https://apiexperts.io/solutions/square-recurring-payments-for-woocommerce-subscriptions/)


= Disclaimer =

WPExperts offer solutions as a third party service provider, we are NOT affiliated, associated, authorized, endorsed by, or in any way officially connected with Square, Inc. The name “Square” as well as related marks and images are registered trademarks of Square, Inc.

== Installation ==

1. Upload the `WP Easy Pay ` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Create a Square account. If you don't have an account, go to https://squareup.com/signup to create one. Register your application with Square.
4. Then go to https://connect.squareup.com/apps and sign in to your Square account. Then click New Application and enter a name for your application and Create App. The application dashboard displays your new app's credentials. One of these credentials is the personal access token. This token gives your application full access to your own Square account than copy Access token, Application id and location and paste it WP Easy Pay  settings.

== Screenshots ==
1. Dashboard ‹ Square — WordPress
2. WPEP Settings ‹ Square — WordPress
3. WPEP Button ‹ Square — WordPress
4. WPEP Button ‹ Square — WordPress
5. WPEP Form With  Ammont Display.
6. WPEP Form Without Ammont Display.

== Changelog ==

= 1.0 2018-03-01 =
* Initial release

= 1.2 2018-04-18 =
* Add - Support for premium plans

= 1.3 2018-04-20 =
* Add - Square Transaction Note fields dynamic for Pro plans.

= 1.5 2018-05-28 =
* Added - Custom Pricing for simple and subscription payment
* Added - First name, Last name and email field in all forms i.e donation, simple and subscription form

= 1.6 2018-08-16 =
* Updated - Freemius SDK

= 1.8 2018-10-16 =
* Add - Dynamic form fields for pro feature.
* Add - Dynamic transaction note for pro feature.
* Add - Admin as well notification email templates for pro feature.
* Update - Popup issues for pro feature.
* Add - Multi Currency support in free version tested upto Version 4.9.8.

= 2.0 2018-10-26 =
* Added - Multiple payment buttons support on same page.
* Fixed - Form fields issue.
* Added - Subscription activation by default.
* Update - Email body template labelling.
* Update - Decimal amount support according to square API.
* Added - Amount field added inside all field tag.
* Added -  Card fields mandatory on subscription button form.

= 2.2 - 18/12/2018 =

* Enhancement – Added compatibility for Wordpress 5.0.1 
* Enhancement – Added compatibility for WooCommerce 3.5.2
* Added - Action hook for wp_easy_payment_success and wp_easy_payment_failed.
* Added - Redirect page after payment success.
* Added - code quality.
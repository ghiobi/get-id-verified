=== Plugin Name ===
Contributors: ghiobi
Donate link: laurendylam.com
Tags: id verification, government id, woocommerce
Requires at least: 5.0
License: GNU GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

== Description ==

A woocommerce plugin that allows merchants to verify the customers by 
their uploaded verification id.

== Features ==

- Persist verifications for returning customers.
- Quick guest checkout.
- Secure storage of identification images.

Future features:
- Add Option for uploads to be optional. (Please contact me to speed this up +1).
- For more features, please contact me.

== Installation ==

Requirements.
- Requires GD2 PHP extension.

This section describes how to install the plugin and get it working.

e.g.

1. Upload `get-id-verified.zip` in the Plugins section of WordPress.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Customizations ==

Actions:
- giv_before_upload_widget: A message above the upload widget.

Filters:
- giv_add_validation_notice: Message sent to user when they dont upload a verification id.
- giv_add_checkout_notice: Message sent to user to notify them that their id will be verified.
- giv_upload_widget_header: Upload widget header.
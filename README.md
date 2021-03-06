# Get Id Verification for Woocommerce

- Contributors: ghiobi
- Donate link: laurendylam.com
- Tags: id verification, government id, woocommerce
- Requires at least: 5.0
- License: GNU GPLv3
- License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

### Description

A Woocommerce plugin that allows merchants to verify the customers by 
their uploaded verification id.

### Features

- Persist verifications for returning customers.
- Quick guest checkout.
- Secure storage of identification images.
- Supports jpg, jpeg, and png images uploads.
- Zero dependencies

Future features:
- Add Option for uploads to be optional. (Please contact me to speed this up +1).
- For more features, please contact me.

### Installation

Requirements.
- Requires GD2 PHP extension.
- Woocommerce

This section describes how to install the plugin and get it working.

e.g.

1. Down the zip file from the [release](https://github.com/ghiobi/get-id-verified/releases) page above.
1. Upload `get-id-verified.zip` in the Plugins section of WordPress.
2. Activate the plugin through the 'Plugins' menu in WordPress.

### Customizations

Actions:
- giv_before_upload_widget: A message above the upload widget.

Filters:
- giv_add_validation_notice: Message sent to user when they dont upload a verification id.
- giv_add_checkout_notice: Message sent to user to notify them that their id will be verified.
- giv_upload_widget_header: Upload widget header.

### Screenshots

![Image 1](./screenshots/1.png)
![Image 2](./screenshots/2.png)
![Image 3](./screenshots/3.png)
![Image 4](./screenshots/4.png)
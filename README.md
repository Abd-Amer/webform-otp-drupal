# Orange webform-otp-drupal
OTP field for Drupal Webform: Bolster webform security with customizable one-time password features. Seamlessly integrates, enhances user trust.

<h2>Overview</h2>
The Drupal Webform OTP Field module enhances the functionality of Drupal Webform by providing a configurable OTP (One-Time Password) field plugin. This plugin allows administrators to add an additional layer of security to webforms by requiring users to enter a one-time password alongside their regular form submissions, The Drupal Webform OTP Field module is a custom-made solution developed in association with Orange.jo services like "\Drupal::service("ojo_extras.sms")->send($code, $recipient, 'OrangeMoney');. 

<h2>Installation</h2>
Download and enable the module like any other Drupal module.
Once enabled, configure the OTP field settings under the Webform configuration options.

<h2>Usage</h2>
After configuring the OTP field settings, add the OTP field plugin to your desired webform.
Users will be prompted to enter a one-time password along with their form submissions.
Administrators can monitor OTP usage and manage settings through the Drupal administration interface.

<h2>Requirements</h2>
Drupal 8 or higher
Drupal Webform module
ojo_extras (custom module)

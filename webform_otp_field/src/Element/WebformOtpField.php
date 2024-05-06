<?php

namespace Drupal\webform_otp_field\Element;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Element\WebformCompositeBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;


/**
 * Provides a 'webform_otp_field'.
 *
 * @FormElement("webform_otp_field")
 */
class WebformOtpField extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
/**
 * Retrieves composite elements for OTP functionality.
 *
 * @param array $element
 *   The array of elements.
 *
 * @return array
 *   The composite elements.
 */
public static function getCompositeElements(array $element) {
  // Initialize an array to store elements.
  $elements = [];

  // Phone number wrapper.
  $elements["phone"] = [
    "#type" => "html_tag",
    "#tag" => "div",
  ];

  // Wrapper for phone number and country code.
  $elements["phone"]["wrapper"] = [
    "#type" => "html_tag",
    "#tag" => "div",
    "#attributes" => [
      'id' => "otp-contact-number-wrapper"
    ],
  ];

  // Country code.
  $elements["phone"]["wrapper"]["country-code"] = [
    "#type" => "html_tag",
    "#tag" => "span",
    "#value" => "+962",
  ];

  // Textfield for contact number.
  $elements["phone"]["wrapper"]["contact-number"] = [
    "#type" => "textfield",
    "#suffix" => '<div class="validation-error contact-number-error"></div>',
    "#attributes" => [
      "placeholder" => t("7X XXX XXXX"),
      "maxlength" => "9",
    ],
    "#ajax" => [
      "callback" => [get_called_class(), 'sendMessage'],
      "disable-refocus" => TRUE,
      "event" => "change",
    ],
  ];

  // OTP main wrapper.
  $elements["otp-main"] = [
    "#type" => "html_tag",
    "#tag" => "div",
    "#attributes" => [
      "id" => "otp-main-wrapper",
      "class" => "d-none"
    ],
  ];

  // Description for OTP verification.
  $elements["otp-main"]["description"] = [
    "#type" => "html_tag",
    "#tag" => "div",
    "#value" => '<div class="otp-first-message">' . t("A verification code has been sent to your mobile number above") . '</div>
          <div class="otp-second-message">' . t('Enter code to verify your identity') . '</div>',
  ];

  // Container for OTP inputs.
  $elements["otp-main"]["otp-container"] = [
    "#type" => "html_tag",
    "#tag" => "div",
    "#suffix" => '<div class="d-none validation-error otp-container-error"></div>',
    "#attributes" => [
      "class" => ["otp-container"],
      "name" => "otp-container",
    ],
  ];

  // Individual OTP inputs.
  // The first three inputs.
  $otpInputCount = 4;
  for ($i = 1; $i <= $otpInputCount; $i++) {
    $elements["otp-main"]["otp-container"]["otp-$i"] = [
      "#type" => "tel",
      "#required" => false,
      "#attributes" => [
        "placeholder" => "-",
        "maxlength" => "1",
        "class" => "otp-$i"
      ],
    ];
  }

  // Ajax callback for last OTP input.
  $elements["otp-main"]["otp-container"]["otp-four"]["#ajax"] = [
    "callback" => [get_called_class(), 'checkCode'],
    "disable-refocus" => TRUE,
    "event" => "change",
  ];

  // Span for OTP validation.
  $elements["otp-main"]["otp-container"]["otp-validate"] = [
    "#type" => "html_tag",
    "#tag" => "span",
    "#attributes" => [
      "class" => ["otp-validate"],
    ],
  ];

  // Validation message for the first OTP input.
  $elements["otp-main"]["otp-one_validation"] = [
    "#type" => "html_tag",
    "#tag" => "div",
    "#attributes" => [
      "id" => "otp-one_validation",
      "class" => ["text-danger"],
    ],
  ];

  // Wrapper for resend code.
  $elements["otp-main"]["second-wrapper"]["resend-label"] = [
    "#type" => "html_tag",
    "#tag" => "div",
    "#value" => t("Didn't get the verification code,"),
    "#attributes" => [
      "class" => "resend-label",
    ],
  ];

  // Button to resend code.
  $elements["otp-main"]["second-wrapper"]["resend-code"] = [
    "#type" => "button",
    "#value" => t("Resend Code"),
    "#attributes" => [
      "class" => ["resend-code"],
    ],
    "#ajax" => [
      "callback" => [get_called_class(), 'sendMessage'],
      "disable-refocus" => TRUE,
    ],
  ];

  // Return the composite elements.
  return $elements;
}

/**
 * Callback function to check the entered OTP code.
 *
 * @param array &$form
 *   The form array.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *   The current form state.
 *
 * @return \Drupal\Core\Ajax\AjaxResponse
 *   The Ajax response.
 */
public static function checkCode(array &$form, FormStateInterface $form_state) {
  // Initialize an Ajax response.
  $response = new AjaxResponse();

  // Get the session and the OTP code stored in the session.
  $session = \Drupal::request()->getSession();
  $sessionCode = $session->get('otp');

  // Get the current timestamp and the entered OTP digits.
  $timestamp = $_SERVER["REQUEST_TIME"];
  $firstDigit = $form_state->getValue("otp")['otp-one'];
  $secondDigit = $form_state->getValue("otp")['otp-two'];
  $thirdDigit = $form_state->getValue("otp")['otp-three'];
  $fourthDigit = $form_state->getValue("otp")['otp-four'];
  
  // Concatenate the entered OTP digits.
  $usercode = $firstDigit . $secondDigit . $thirdDigit . $fourthDigit;

  // Check if the entered OTP code matches the session code.
  if ($sessionCode != $usercode) {
    // If not matched, set the argument to 0 (indicating failure).
    $arguments = ['0'];
  } else {
    // If matched, set the argument to 1 (indicating success) and clear the error message.
    $arguments = ['1'];
    $response->addCommand(
      new HtmlCommand(".otp-container-error", "")
    );
  }

  // Add an invoke command to call the JavaScript function for further processing.
  $response->addCommand(
    new InvokeCommand(null, "checkCodeWebform", $arguments)
  );

  // Return the Ajax response.
  return $response;
}


/**
 * Callback function to send OTP via SMS and validate contact number.
 *
 * @param array &$form
 *   The form array.
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *   The current form state.
 *
 * @return \Drupal\Core\Ajax\AjaxResponse
 *   The Ajax response.
 */
public static function sendMessage(array &$form, FormStateInterface $form_state) {
  // Initialize an Ajax response.
  $response = new AjaxResponse();
  
  // Check if contact number is provided.
  if (!$form_state->getValue("otp")['contact-number'] ||
    empty($form_state->getValue("otp")['contact-number'])) {
      // Handle case where contact number is not provided.
  } else {
      // Get the contact number and validate it.
      $contact = $form_state->getValue("otp")['contact-number'];
      $contactPhoneValidate = preg_match('/^7[0-9]{8}$/', $contact);
      $arguments = ["#edit-contact-number"];
      
      // If contact number is valid, remove error class and clear error message.
      if ($contactPhoneValidate) {
          $response->addCommand(
            new InvokeCommand(null, 'removeErrorClassWebform', $arguments)
          );
          $response->addCommand(
            new HtmlCommand(".contact-number-error", "")
          );
      } else {
          // If contact number is invalid, add error class and display error message.
          $response->addCommand(
            new InvokeCommand(null, 'addErrorClassWebform', $arguments)
          );
          $response->addCommand(
            new HtmlCommand(".contact-number-error", t("Invalid contact number"))
          );
      }
  }

  // If contact number is valid, proceed to send OTP via SMS.
  if ($contactPhoneValidate) {
      // Construct recipient phone number.
      $recipient = '962' . $contact;
      
      // Generate a random OTP code.
      $code = mt_rand(1111, 9999);
      
      // Get the current timestamp.
      $timestamp = $_SERVER["REQUEST_TIME"];
      
      // Store the OTP code and timestamp in session.
      $session = \Drupal::request()->getSession();
      $session->set("otp", $code);
      $session->set("otpTime", $timestamp);
      
      // Send OTP via SMS.
      \Drupal::service("ojo_extras.sms")->send($code, $recipient, 'OrangeMoney');
      
      // Show the OTP code block and reset the counter.
      $arguments = ['show'];
      $response->addCommand(new InvokeCommand(null, "showOtpCodeBlockWebform", $arguments));
      $response->addCommand(new InvokeCommand(null, "resetCounterWebform", [$session->get('otp')]));
  }
  
  // Return the Ajax response.
  return $response;
}

}

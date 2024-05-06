<?php

namespace Drupal\webform_otp_field\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElement\TextBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'webform_otp_field' element.
 *
 * @WebformElement(
 *   id = "webform_otp_field",
 *   label = @Translation("Webform OTP Field"),
 *   description = @Translation("Provides a Webform OTP field."),
 *   category = @Translation("Advanced elements"),
 * )
 */
class WebformOtpField extends TextBase {

  /**
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    // Set default properties for the Webform OTP field.
    return parent::getDefaultProperties() + [
      'multiple' => '',
      'size' => '',
      'minlength' => '',
      'maxlength' => '',
      'placeholder' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    // Prepare the Webform OTP field element.
    parent::prepare($element, $webform_submission);
    // Additional preparation if needed can be done here.
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    // Build the form element for the Webform OTP field.
    $form = parent::form($form, $form_state);
    // Additional form elements or alterations can be added here if needed.

    return $form;
  }

}

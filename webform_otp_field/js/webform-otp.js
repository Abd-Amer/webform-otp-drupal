(function ($, Drupal, drupalSettings) {
  $.fn.consolelog = function (form) {

    console.log(form);
  };
    $.fn.checkCodeWebform = function (argument) {
      let otpValidate = $(
        ".otp-container .otp-validate"
      );
      //bills form
      if (argument == 1 && otpValidate.length) {
        otpValidate.addClass("valid");
        otpValidate.removeClass("error");

      } else {
        otpValidate.removeClass("valid");
        otpValidate.addClass("error");
      }
    };

    $.fn.showOtpCodeBlockWebform = function (argument) {
        if (argument) {
          /** visa card orange money form */
          let contactNumValue = $("#otp-contact-number-wrapper input").val();
          if (contactNumValue.length) {
            $('#otp-main-wrapper').removeClass("d-none");
          }
        }
      };
    $.fn.resetCounterWebform = function (argument) {
    /** reset otp input functionality */    
      $('.otp-container input').val(''); 
    };

    $.fn.removeErrorClassWebform = function (argument) {
        let fieldIds = [
          "#otp-contact-number-wrapper input",
        ];
    
        let targetField = argument;
        let found = false;
    
        $.each(fieldIds, function (index, fieldId) {
          if (fieldId == targetField) {
            found = true;
            return false;
          }
        });
    
        if (found) {
          $(targetField).removeClass("error");
        }
      };
    $.fn.addErrorClassWebform = function (argument) {
    let fieldIds = [
        "#edit-contact-number",
    ];

    let targetField = argument;
    let found = false;

    $.each(fieldIds, function (index, fieldId) {
        if (fieldId == targetField) {
        found = true;
        return false;
        }
    });

    if (found) {
        $(targetField).addClass("error");
    }
    if (found) {
        $('html, body').animate({
        scrollTop: ($('form .error').offset().top - 160)
    }, 0);
    }
    
    
    };
  })(jQuery, Drupal, drupalSettings);
  
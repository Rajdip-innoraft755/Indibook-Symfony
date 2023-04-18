/**
 * Jquery works after the document loaded fully.
 */
$(document).ready(function () {
  // It is the regEx to check whether the input contains only alphabet or not.
  const $isAlpha = /^[a-zA-Z ]*$/;

  // It is the regEx whether the email is in valid format or not.
  const $isValidEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

  // It is the regEx to Checks whether the password follows the
  // following checkpoints or not more than 8 characters atleast
  // one uppercase and one lowercase and one digit
  // and one special characters(@, $, #, !, %, *, ?, &).
  // If the password does not match these conditions then store the error.
  const $isValidPassword =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$#!%*?&])[A-Za-z\d@#$!%*?&]{8,}$/;

  /**
   * disableBtn is the method to disable submit button if any error
   * presents in the form data.
   *
   *   @return void
   *     This function returns nothing.
   */
  function disableBtn() {
    if ($(".error").text() != "") {
      $("#submit-btn").attr("disabled", true);
    }
  }

  /**
   * enableBtn is the method to enable submit button if no error
   * presents in the form data.
   *
   *   @return void
   *     This function returns nothing.
   */
  function enableBtn() {
    if (
      $.trim($(".error").text()) == "" ||
      $.trim($(".error").text()) == "Strong Password"
    ) {
      $("#submit-btn").attr("disabled", false);
    }
  }

  /**
   * It is to checkes whether the first name contains only alphabet or not,
   * show error and disable/enable submit button accordingly.
   */
  $("#fName>input").blur(function () {
    if (!$isAlpha.test($(this).val())) {
      $("#fName>.error").html("* only alphapets are allowed.");
      disableBtn();
    } else {
      $("#fName>.error").html("");
      enableBtn();
    }
  });

  /**
   * It is to checkes whether the last name contains only alphabet or not,
   * show error and disable/enable submit button accordingly.
   */
  $("#lName>input").blur(function () {
    if (!$isAlpha.test($(this).val())) {
      $("#lName>.error").html("* only alphapets are allowed.");
      disableBtn();
    } else {
      $("#lName>.error").html("");
      enableBtn();
    }
  });

  /**
   * It is to checkes whether the email is in proper format or not,
   * show error and disable/enable submit button accordingly.
   */
  $("#emailId>input").blur(function () {
    if ($(this).val() != "" && !$isValidEmail.test($(this).val())) {
      $("#emailId>.error").html("* not a valid Email Id.");
      disableBtn();
    } else {
      $("#emailId>.error").html("");
      enableBtn();
    }
  });

  /**
   * It is to checkes whether the password is in specified format or not,
   * show error and disable/enable submit button accordingly.
   */
  $("#pass").keyup(function () {
    if (!$isValidPassword.test($(this).val())) {
      $("#password>.error").css("color", "red");
      $("#password>.error").html("Weak Password");
      disableBtn();
    } else {
      $("#password>.error").css("color", "green");
      $("#password>.error").html("Strong Password");
      enableBtn();
    }
  });

  /**
   * It is to checkes whether the user id already exists or not using ajax,
   * show error and disable/enable submit button accordingly.
   */
  $("#userId>input").keyup(
    $.debounce(300, function () {
      var userid = $(this).val();
      $.ajax({
        url: "/validuserid",
        method: "POST",
        data: { userId: userid },
        datatype: "JSON",
        success: function (data) {
          $("#userId>.error").html(jQuery.parseJSON(data)["isAvialableUserId"]);
          if (jQuery.parseJSON(data)["isAvialableUserId"] == "") {
            enableBtn();
          } else {
            disableBtn();
          }
        },
      });
    })
  );
});

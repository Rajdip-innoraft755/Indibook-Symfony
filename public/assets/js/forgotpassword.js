/**
 * Jquery works after the document loaded fully.
 */
$(document).ready(function () {
  // At the beginning it disable the send otp button.
  $("#send-otp").attr("disabled", true);

  /**
   *   @var userId
   *     This is to store the userId input by user.
   */
  var userId;

  /**
   * It is to check whether the user id is valid or not using ajax based
   * on the result it activate the send otp button.
   */
  $("#userId-input").keyup(function () {
    userId = $(this).val();
    if (userId.length) {
      $.ajax({
        url: "/forgotpassword/checkuserid",
        method: "POST",
        data: { userId: userId },
        datatype: "text",
        success: function (data) {
          var isValidUser = jQuery.parseJSON(data)["isValidUser"];
          $("#userId>.error").html(isValidUser);
          if (isValidUser == "* Valid user ID.") {
            $("#send-otp").attr("disabled", false);
          }
        },
      });
    }
  });

  /**
   * It is the send otp to the user using ajax and show the sending
   * messages to the user and based on the status of sending messages ,
   * it activate the otp input field, verify otp button.
   */
  $("#send-otp").click(function () {
    $.ajax({
      url: "/forgotpassword/sendotp",
      method: "POST",
      data: { userId: userId },
      datatype: "text",
      beforeSend: function () {
        $(".loader").show();
      },
      success: function (data) {
        var otpSendMessage = jQuery.parseJSON(data)["otpSendMessage"];
        $("#userId>.error").html(otpSendMessage);
        $(".loader").hide();
        if (
          otpSendMessage == "* OTP sent in your registered mail Id successfully"
        ) {
          $("#otp-input").attr("disabled", false);
          $("#verify-otp").attr("disabled", false);
        }
      },
    });
  });

  /**
   * It is the verify the whether the input otp is correct or not if the otp is
   * correct then it activate the password input field and
   * the reset password button.
   */
  $("#verify-otp").click(function () {
    if ($("#otp-input").val().length != 0) {
      $.ajax({
        url: "/forgotpassword/verifyotp",
        method: "POST",
        data: { otp: $("#otp-input").val() },
        datatype: "text",
        beforeSend: function () {
          $(".loader").show();
        },
        success: function (data) {
          $(".loader").hide();
          var verifyOtp = jQuery.parseJSON(data)["verifyOtp"];
          $("#otp>.error").html(verifyOtp);
          if (verifyOtp == "* correct otp") {
            $("#pass").attr("disabled", false);
            $("#reset-password").attr("disabled", false);
          }
        },
      });
    }
  });

  /**
   * It is to reset the password and display the success message to the user.
   */
  $("#reset-password").click(function () {
    if ($("#pass").val().length != 0) {
      $.ajax({
        url: "/forgotpassword/reset",
        method: "POST",
        data: { userid: userId, password: $("#pass").val() },
        datatype: "text",
        beforeSend: function () {
          $(".loader").show();
        },
        success: function (data) {
          var resetPassword = jQuery.parseJSON(data)["resetPassword"];
          $(".loader").hide();
          $("#password>.error").html(resetPassword);
        },
      });
    }
  });
});

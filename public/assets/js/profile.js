/**
 * Jquery works after the document loaded fully.
 */
$(document).ready(function () {
  /**
   * It is to show the menu when user clicked on the toggle bar of the page.
   */
  $("#slide-menu").click(function () {
    $(".profile-menu").slideToggle();
  });

  // $("#pass").blur(function () {
  //   var isError = true;
  //   if ($.trim($(this).val()) != "") {
  //     $.ajax({
  //       url: "/landing/validPassword",
  //       method: "POST",
  //       data: { password: $(this).val() },
  //       datatype: "text",
  //       success: function (data) {
  //         if (data) {
  //           $("#password>.error").html(data);
  //           isError = false;
  //         }
  //       },
  //     });
  //   }
  //   if (isError == true) {
  //     $("#submit-btn").prop("disabled", false);
  //   } else {
  //     $("#submit-btn").prop("disabled", true);
  //   }
  // });
});

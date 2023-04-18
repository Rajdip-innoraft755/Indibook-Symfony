/**
 * Jquery works after the document loaded fully.
 */
$(document).ready(function () {
  /**
   * It is show the cookie-banner if the cookie is not accepted.
   */
  if ($.cookie("cookie-policy") != "accept") {
    $(".cookie-policy").fadeIn(500);
  }
  /**
   * Based on the user choice the cookie data is stored in the browser
   * cookie storgaefor 15 mins.
   */
  $(".cookie-button").click(function () {
    if ($(this).attr("id") == "accept-cokkie") {
      $.cookie("cookie-policy", "accept", { path: "/", expires: 15 / 1440 });
    } else {
      $.cookie("cookie-policy", "decline", { path: "/", expires: 15 / 1440 });
    }
    $(".cookie-policy").fadeOut();
  });
});

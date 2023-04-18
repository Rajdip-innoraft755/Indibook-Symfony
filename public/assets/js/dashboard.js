/**
 * Jquery works after the document loaded fully.
 */
$(document).ready(function () {
  /**
   * It is to show the loader for first 2000ms when the dashboard
   * page is loading.
   */
  $(".loader").fadeOut(2000);

  /**
   * It is to change the react icon style when it is clicked.
   */
  $(".react").click(function () {
    $(this).toggleClass("fa-regular fa-solid");
  });

  /**
   * It is to change the react icon style when it is clicked.
   */
  $(".comment-btn").click(function () {
    $(this).toggleClass("fa-regular fa-solid");
    $(".comment-section").toggleClass("show hide");
  });

  /**
   * It is to show the menu when user clicked on the toggle bar of the page.
   */
  $("#slide-menu").click(function () {
    $(".profile-menu").slideToggle();
  });

  /**
   * It is to restrict the user to make blank post.
   */
  $("#postContent").blur(function () {
    if ($("#postContent").val().length == 0) {
      $("#postBtn").prop("disabled", true);
    } else {
      $("#postBtn").prop("disabled", false);
    }
  });


  // var x = 2;
  // $(".show-btn").click(function () {
  //   $(".post-section:nth-child(" + x + ")").css("display", "block");
  //   var stop = $(this).attr("id");
  //   console.log(stop);
  //   $("#" + stop).css("display", "none");
  //   x++;
  // });
});

/**
 * Jquery works after the document loaded fully.
 */
$(document).ready(function () {
  // Checks whether the what theme is set previously change
  // the theme of the new page as per that theme.
  if (localStorage.getItem("theme") == "dark") {
    $(document.body).addClass("dark-theme");
    $("#theme-btn").addClass("fa-rotate-180");
  }

  /**
   * If the theme icon is clicked then change the current theme
   * to the opposite theme and change the icon for better understanding
   * of the user , what theme is selected right now.
   */
  $("#theme").click(function () {
    $(document.body).toggleClass("dark-theme");
    if ($(document.body).attr("class") == "dark-theme") {
      localStorage.setItem("theme", "dark");
    } else {
      localStorage.setItem("theme", "light");
    }
    $("#theme-btn").toggleClass("fa-rotate-180");
  });

  /**
   * If the eye icon of the password field is clicked by the user then the
   * password either visible or hide.
   */
  $("#eye").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var type = $(this).hasClass("fa-eye-slash") ? "text" : "password";
    $("#pass").attr("type", type);
  });

  /**
   * When user wants to enter the new password it shows user the instruction
   * about how to make a storng password.
   */
  $("#pass").focus(function () {
    $(".pass-instruction").css("display", "block");
  });

  /**
   * This is to hide the instructions when the password field is not focused.
   */
  $("#pass").blur(function () {
    $(".pass-instruction").css("display", "none");
  });

  /**
   * As we custmize the file input button using an icon so, when
   * user clicked on that icon it works same as the file input button.
   */
  $("#file-upload-btn").click(function () {
    $("#file-upload-input").click();
  });

  /**
   * When user uploads an image then he/she will see a preview of the
   * uploaded image.
   */
  $("#file-upload-input").change(function () {
    var file = $(this).get(0).files[0];
    if (file) {
      var reader = new FileReader();
      reader.onload = function (event) {
        $("#preview").css("display", "block");
        $("#preview").attr("src", event.target.result);
      };
      reader.readAsDataURL(file);
    }
  });
});

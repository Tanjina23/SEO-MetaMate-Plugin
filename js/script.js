jQuery(document).ready(function ($) {
  $("#theme-toggle-btn").click(function () {
    $("#seometa-theme").toggleClass("dark");
    let isDark = $("#seometa-theme").hasClass("dark");
    $(this).text(isDark ? "Light" : "Dark");
  });
});

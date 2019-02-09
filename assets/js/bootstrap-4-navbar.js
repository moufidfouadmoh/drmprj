$(document).ready(function () {
    $(".dropdown-toggle").on("mouseenter", function () {
        if (!$(this).parent().hasClass("show")) {
            $(this).click();
        }
    });

    $(".dropdown").on("mouseleave", function () {
        // make sure it is shown:
        if ($(this).hasClass("show")){
            $(this).children('.dropdown-toggle').first().click();
        }
    });
});
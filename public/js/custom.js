$(document).ready(function () {
    positionFooter();

    $(window).resize(function () {
        positionFooter();
    });

    function positionFooter() {
        var windowHeight = $(window).height();
        var footerHeight = $("#footer").outerHeight();
        var bodyHeight = $("body").height();

        if (bodyHeight + footerHeight < windowHeight) {
            $("#footer").css({
                position: "fixed",
                bottom: 0,
                width: "100%"
            });
        } else {
            $("#footer").css({
                position: "relative",
                bottom: "auto"
            });
        }
    }
});
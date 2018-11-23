
$(document).on("tabsbeforeactivate", "#tabs", function (e, ui) {
    var animation_class = "pop";
    $(ui.newPanel)
    .addClass("in " + animation_class)
    .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
        $(this).removeClass("in " + animation_class);
    });
});

$(document).ready(function() {
	var circle = $(".circle");
	circle.ready(function() {
		circle.height(circle.width());
		circle.css("margin-bottom", -(circle.next().height() + 15));
	});
	$("#tabs > div[data-role='navbar'] > ul > li").click(function() {
		popupLink = $(this).index();
	});
});

var popupLink = null;

$(document).on("popupbeforeposition", "div[data-role='popup']", function(e,ui) {
    $("#tabs > div[data-role='navbar'] > ul > li:eq(" + popupLink + ") a").addClass("ui-btn-active");
});

$(document).on("popupafteropen", "div[data-role='popup']", function(e,ui) {
});
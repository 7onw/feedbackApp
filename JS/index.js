
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

var fileToUpload;

function submitTask(e) {
	var form_data = new FormData();                  
	form_data.append('file', fileToUpload);    
	form_data.append('name', $("#upload-name").val());   
	form_data.append('description', $("#upload-description").val());     
    $.ajax({
        url: 'upload.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(php_script_response){
			console.log("Server response: " + php_script_response);
			resetTabOne();
        }
     });
};

function uploadImage(e) {
	$('#hidden-upload-button').on('change', function() {
		$('#upload-button').addClass("button-disabled");
		$('#submit-button').removeClass("button-disabled").focus();
		
		if (this.files) {
			fileToUpload = this.files[0];
			var fr = new FileReader;
			fr.onloadend = changeimg;
			fr.readAsDataURL(fileToUpload)
		} else {
			fileToUpload = this.value;
			changeimg(this.value);
		}
	}).click();
};

function changeimg(str) {
    if(typeof str === "object") {
        str = str.target.result;
    }
    
	$("#uploaded-image").css({"background-image": "url(" + str + ")"});

	$("#image-div").removeClass("collapsed");
	$("#circles-div").addClass("collapsed");
}

function resetTabOne() {
	$("#circles-div").removeClass("collapsed");
	$("#image-div").addClass("collapsed");
	$('#upload-button').removeClass("button-disabled").focus();
	$('#submit-button').addClass("button-disabled");
	$("#uploaded-image").css({"background-image": ""});
	document.getElementById("hidden-upload-form").reset();
	$("#upload-name").val("");   
	$("#upload-description").val("");   
	fileToUpload = null;
}
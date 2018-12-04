// Circle Animation
$(document).ready(function () {
	var circle = $(".circle");
	circle.ready(function () {
		circle.height(circle.width());
		circle.css("margin-bottom", -(circle.next().height() + 15));
	});
	$("#tabs > div[data-role='navbar'] > ul > li").click(function () {
		popupLink = $(this).index();
	});
});

// Pop-Ups
///// Pop-Up Animation
$(document).on("tabsbeforeactivate", "#tabs", function (e, ui) {
	var animation_class = "pop";
	$(ui.newPanel)
		.addClass("in " + animation_class)
		.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
			$(this).removeClass("in " + animation_class);
		});
});

///// Pop-Up nach dem Öffnen auf Standart zurücksetzen
var popupLink = null, last_popup;

$(document).on("popupbeforeposition", "div[data-role='popup']", function (e, ui) {
	$("#tabs > div[data-role='navbar'] > ul > li:eq(" + popupLink + ") a").addClass("ui-btn-active");
});

$(document).on("popupafteropen", "div[data-role='popup']", function (e, ui) {
	last_popup = $(".ui-popup-active");
	resetPopup(last_popup.find(".submitAudioButton"));
});

$(document).on("popupafterclose", "div[data-role='popup']", function (e, ui) {
	recorder && recorder.stop();
	if (audio_stream != null && audio_stream != "undefined")
		audio_stream.getAudioTracks()[0].stop();
	resetPopup(last_popup.find(".submitAudioButton"));
});

///// File-Upload
var fileToUpload;
//////// Bild, Titel & Beschreibung mit upload.php an DB senden
function submitTask(e) {
	var uploadName = $("#upload-name").val();
	var uploadDesc = $("#upload-description").val();

	if (uploadName == null || uploadName.length <= 0) {
		$("#upload-name").css("border", "2px solid red");
		return;
	}
	else if (uploadDesc == null || uploadDesc.length <= 0) {
		$("#upload-name").css("border", "0px");
		$("#upload-description").css("border", "2px solid red");
		return;
	}
	$("#upload-name").css("border", "0px");
	$("#upload-description").css("border", "0px");

	var form_data = new FormData();
	form_data.append('file', fileToUpload);
	form_data.append('name', uploadName);
	form_data.append('description', uploadDesc);
	$.ajax({
		url: 'upload.php',
		dataType: 'text',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,
		type: 'post',
		success: function (php_script_response) {
			console.log("Server response: " + php_script_response);
			resetTabOne();
		}
	});
};

//////// Bild hochladen
function uploadImage(e) {
	$('#hidden-upload-button').on('change', function () {
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
	if (typeof str === "object") {
		str = str.target.result;
	}

	$("#uploaded-image").css({ "background-image": "url(" + str + ")" });

	$("#image-div").removeClass("collapsed");
	$("#circles-div").addClass("collapsed");
}

// Reset Upload-Tab
function resetTabOne() {
	$("#circles-div").removeClass("collapsed");
	$("#image-div").addClass("collapsed");
	$('#upload-button').removeClass("button-disabled").focus();
	$('#submit-button').addClass("button-disabled");
	$("#uploaded-image").css({ "background-image": "" });
	document.getElementById("hidden-upload-form").reset();
	$("#upload-name").val("");
	$("#upload-description").val("");
	fileToUpload = null;
}

// Audio-Aufnahme
window.onload = function () {
	InitializeRecording();
	InitializeAudioPlayer();
};

function InitializeAudioPlayer() {
	// Utility method that will give audio formatted time
	getAudioTimeByDec = function (cTime, duration) {
		var duration = parseInt(duration),
			currentTime = parseInt(cTime),
			left = duration - currentTime, second, minute;
		second = (left % 60);
		minute = Math.floor(left / 60) % 60;
		second = second < 10 ? "0" + second : second;
		minute = minute < 10 ? "0" + minute : minute;
		return minute + ":" + second;
	};

	$(".audioControl").click(function (e) {
		var ID = $(this).attr("id");
		var progressArea = $("#audioProgress" + ID);
		var audioTimer = $("#audioTime" + ID);
		var audio = $("#audio" + ID);
		var audioCtrl = $(this);
		e.preventDefault();
		var R = $(this).attr('rel');
		if (R == 'play') {
			$(this).removeClass('audioPlay').addClass('audioPause').attr("rel", "pause");
			audio.trigger('play');
		}
		else {
			$(this).removeClass('audioPause').addClass('audioPlay').attr("rel", "play");
			audio.trigger('pause');
		}

		audio.bind("timeupdate", function (e) {
			var audioDOM = audio.get(0);
			audioTimer.text(getAudioTimeByDec(audioDOM.currentTime, audioDOM.duration));
			var audioPos = (audioDOM.currentTime / audioDOM.duration) * 100;
			progressArea.css('width', audioPos + "%");
			if (audioPos == "100") {
				$("#" + ID).removeClass('audioPause').addClass('audioPlay').attr("rel", "play");
				audio.trigger('pause');
			}
		});
	});
}

// Audio-Recorder
var audio_context;
var recorder;
var audio_stream;
var start_time, stop_time;
var audioBLOB;

function InitializeRecording() {
	try {
		// Monkeypatch for AudioContext, getUserMedia and URL
		window.AudioContext = window.AudioContext || window.webkitAudioContext;
		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia;
		window.URL = window.URL || window.webkitURL;

		audio_context = new AudioContext;
		console.log('Audio context is ready !');
		console.log('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));
	} catch (e) {
		alert('No web audio support in this browser!');
	}
}

function setTime(recordAudioTime, current) {
	var ms = current % 1000;
	var s = ((current - ms) / 1000) % 60;
	var m = ((current - s * 1000 - ms) / 60) % 60;
	var h = ((current - m * 60 - s * 1000 - ms) / 60) % 24;
	recordAudioTime.html(pad2(h) + ":" + pad2(m) + ":" + pad2(s) + "." + parseInt(ms / 100));
}

function startRecording(recordAudioButton, recordAudioTime) {
	stop_time = null;
	start_time = $.now();

	// Access the Microphone using the navigator.getUserMedia method to obtain a stream
	navigator.getUserMedia({ audio: true }, function (stream) {
		audio_stream = stream;
		var input = audio_context.createMediaStreamSource(stream);
		recorder = new Recorder(input);
		recorder && recorder.record();

		recordAudioButton.html("Aufnahme beenden");
	}, function (e) {
		alert('No live audio input: ' + e);
	});

	var interval = setInterval(function () {
		if (start_time == null)
			recordAudioTime.html("00:00:00.0");
		else if (stop_time == null)
			setTime(recordAudioTime, new Date($.now()) - new Date(start_time));
		else {
			setTime(recordAudioTime, new Date(stop_time) - new Date(start_time));
			clearInterval(interval);
			interval = null;
		}
	}, 100);
}

function pad2(number) {
	return (number < 10 ? '0' : '') + parseInt(number);
}

// Audio-Aufnahme beenden
function stopRecording(callback, AudioFormat, recordAudioButton, submitAudioButton, popupInfoAufnahme, popupInfoSenden) {
	stop_time = $.now();
	recorder && recorder.stop();
	audio_stream.getAudioTracks()[0].stop();

	if (typeof (callback) == "function") {
		recorder && recorder.exportWAV(function (blob) {
			callback(blob);

			recordAudioButton.css("display", "none");
			recordAudioButton.html("Aufnahme starten");
			submitAudioButton.css("display", "");
			popupInfoAufnahme.css("display", "none");
			popupInfoSenden.css("display", "");

			recorder.clear();
		}, (AudioFormat || "audio/wav"));
	}
}

// Recording-Buttons
function toggleRecording(e) {
	var recordAudioButton = $(e).parent().children(".recordAudioButton");
	var submitAudioButton = $(e).parent().children(".submitAudioButton");
	var recordAudioTime = $(e).parent().children(".recordAudioTime");
	var popupInfoAufnahme = $(e).parent().parent().parent().find(".popup-info-aufnahme");
	var popupInfoSenden = $(e).parent().parent().parent().find(".popup-info-senden");

	if (recordAudioButton.html() == "Aufnahme starten") {
		startRecording(recordAudioButton, recordAudioTime);
	}
	else {
		// var _AudioFormat = "audio/wav";
		// You can use mp3 to using the correct mimetype
		var _AudioFormat = "audio/mpeg";

		stopRecording(function (_audioBLOB) {
			audioBLOB = _audioBLOB;

			var url = URL.createObjectURL(_audioBLOB);
			recordAudioButton.css("display", "none");
			submitAudioButton.css("display", "");
			popupInfoAufnahme.css("display", "none");
			popupInfoSenden.css("display", "");
		}, _AudioFormat, recordAudioButton, submitAudioButton, popupInfoAufnahme, popupInfoSenden);
	}
}

// Audio-Aufnahme mit upload.php in DB laden
function submitRecording(e, aufgabeID, creatorID) {
	var form_data = new FormData();
	form_data.append('ID_aufgabe', aufgabeID);
	form_data.append('ID_creator', creatorID);
	form_data.append('data', audioBLOB);
	$.ajax({
		url: 'upload.php',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,
		type: 'post',
		success: function (php_script_response) {
			console.log("Server response: " + php_script_response);
			resetPopup(e);
		}
	});
}

// Pop-Up Aufgaben auf Standart zurücksetzen
function resetPopup(e) {
	var recordAudioButton = $(e).parent().find(".recordAudioButton");
	var submitAudioButton = $(e).parent().find(".submitAudioButton");
	var recordAudioTime = $(e).parent().find(".recordAudioTime");
	var popupInfoAufnahme = $(e).parent().parent().parent().find(".popup-info-aufnahme");
	var popupInfoSenden = $(e).parent().parent().parent().find(".popup-info-senden");

	if (recorder != null) {
		recorder && recorder.stop();
		audio_stream.getAudioTracks()[0].stop();
	}

	submitAudioButton.css("display", "none");
	recordAudioButton.css("display", "");
	recordAudioButton.html("Aufnahme starten");
	recordAudioTime.html("00:00:00.0");
	popupInfoAufnahme.css("display", "");
	popupInfoSenden.css("display", "none");

	start_time = stop_time = null;
}
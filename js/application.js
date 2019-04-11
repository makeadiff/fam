$=jQuery.noConflict();
//Framework Specific
function showMessage(data) {
	var type = 'error';
	if(data.success) var type = 'success';

	$("#"+type+"-message").html(stripSlashes(data[type]));
	$("#"+type+"-message").fadeIn(500);

	window.setTimeout(function() {
		$("#"+type+"-message").fadeOut(500);
	}, 3000); // Amount of time message should be shown.

	return type;
}
function stripSlashes(text) {
	if(!text) return "";
	return text.replace(/\\([\'\"])/,"$1");
}

function ajaxError() {
	alert("Error communicating with server. Please try again");
}
function loading() {
	$("#loading").show();
}
function loaded() {
	$("#loading").hide();
}

function handleSubmit(e) {
	e.stopPropagation();
	e.preventDefault();

	var form = $(this);
	var url = form.attr("action");
	var action = form.find("[name='action']");
	action.val("Saving...");
	action.prop("disabled", true);
	// alert(url+form.serialize());
	$.ajax({
		"url": url,
		"data": form.serialize() + "&action=Save&ajaxify=1",
		"success": function(message) {
			action.val("Saved");
			action.prop("disabled", false);
			// console.log(message);
			// $("#main-content").growl({title: "Data Saved", text: "Your data has been saved to the database successfully.", growlClass: "success"});
			$.notify({title: '<strong>Data Saved</strong>', message: 'Your data has been saved to the database successfully.',icon: 'glyphicon glyphicon-ok'}, {type: "success", delay: 3000});
			window.setTimeout(function () {
				action.val("Save");
			}, 2000);
		}
	});
	return false;
}

function siteInit() {
	$("a.confirm").click(function(e) { //If a link has a confirm class, confrm the action
		var action = (this.title) ? this.title : "do this";
		action = action.substr(0,1).toLowerCase() + action.substr(1); //Lowercase the first char.
		var user_action = confirm("Are you sure you want to " + action + "?");

		if(!user_action) {
			e.stopPropagation();
			e.preventDefault();
			return false;
		}
	});

	$("form.ajaxify").submit(handleSubmit);
	// $( document ).tooltip();

	if(window.init && typeof window.init == "function") init(); //If there is a function called init(), call it on load
}
jQuery(window).load(siteInit);



$('.delete_task').click(function(e){
	var action = (this.title) ? this.title : "do this";
	action = action.substr(0,1).toLowerCase() + action.substr(1); //Lowercase the first char.
	var user_action = confirm("Are you sure you want to " + action + "?");

	if(!user_action) {
		e.stopPropagation();
		e.preventDefault();
		return false;
	}
	e.preventDefault();
	var url = this.href;
	var id = this.id;
	$.ajax(url).done(function(){
		$('#task'+id).hide();
		$.notify({title: '<strong>Data Saved</strong>', message: 'The selected task has been deleted.',icon: 'glyphicon glyphicon-ok'}, {type: "success", delay: 3000});
	}).fail(function(){
		$.notify({title: '<strong>Error</strong>', message: 'Server not reachable.',icon: 'glyphicon glyphicon-remove'}, {type: "warning", delay: 3000});
	});
});

$('.reject_applicant').click(function(e){
	var action = (this.title) ? this.title : "do this";
	action = action.substr(0,1).toLowerCase() + action.substr(1); //Lowercase the first char.
	var user_action = confirm("Are you sure you want to " + action + "?");

	if(!user_action) {
		e.stopPropagation();
		e.preventDefault();
		return false;
	}
	e.preventDefault();
	var url = this.href;
	$.ajax(url).done(function(){
		$.notify({title: '<strong>Data Saved</strong>', message: 'The selected applicant has been marked rejected and can be apply to be a mentor once volunteer continuation form is rolled out.',icon: 'glyphicon glyphicon-ok'}, {type: "success", delay: 3000});
		$('.alert.rejected').show();
		$('.row.pending').hide();
		$('.row.pending_applicant').hide();
		$('.row.rejected').show();
		$('.row.rejected_applicant').show();
	}).fail(function(){
		$.notify({title: '<strong>Error</strong>', message: 'Server not reachable.',icon: 'glyphicon glyphicon-remove'}, {type: "warning", delay: 3000});
	});
});

$('.revoke_applicant').click(function(e){
	var action = (this.title) ? this.title : "do this";
	action = action.substr(0,1).toLowerCase() + action.substr(1); //Lowercase the first char.
	var user_action = confirm("Are you sure you want to " + action + "?");

	if(!user_action) {
		e.stopPropagation();
		e.preventDefault();
		return false;
	}
	e.preventDefault();
	var url = this.href;
	$.ajax(url).done(function(){
		$.notify({title: '<strong>Data Saved</strong>', message: 'The selected applicant\'s status has been revoked from <strong>Rejected</strong> to <strong>Pending</strong>.',icon: 'glyphicon glyphicon-ok'}, {type: "success", delay: 3000});
		$('.alert.rejected').hide();
		$('.row.rejected').hide();
		$('.row.rejected_applicant').hide();
		$('.row.pending').show();
		$('.row.pending_applicant').show();
	}).fail(function(){
		$.notify({title: '<strong>Error</strong>', message: 'Server not reachable.',icon: 'glyphicon glyphicon-remove'}, {type: "warning", delay: 3000});
	});
});

$('input[type="file"]').change(function(e){
	var id = this.id;
	var count = id.substring(5,6);
	var fileName = '';
	for(var i=0;i < e.target.files.length; i++){
		if(i>0){
			fileName = fileName + ', ';
		}
    fileName = fileName + e.target.files[i].name;
	}
	if(id!='common_task_files'){
    document.getElementById('file_name_label_' + count).innerHTML = fileName;
		$('#file_name_label_' + count).show();
	}
	else{
		document.getElementById('file_name_label_common').innerHTML = fileName;
		$('#file_name_label_common').show();
	}
});

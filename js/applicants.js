function init() {
	$("#group_id").change(showPreference);
	showPreference.apply($("#group_id")[0]);
}

function showPreference() {
	if(this.value != "0") $("#preference-area").show();
	else $("#preference-area").hide();
}
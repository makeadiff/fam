function init() {
	$("#group_id").change(function() {
		if(this.value != "0") $("#preference-area").show();
		else $("#preference-area").hide();
	});
}

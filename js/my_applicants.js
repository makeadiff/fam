function init() {
	$("#status").on("change", setStatus);
} 


function setStatus(e) {
	var user_id = $(this).attr("data_user_id");
	var group_id = $(this).attr("data_group_id");
	var status = this.value;

	$.ajax("api/set_status.php?user_id="+user_id+"&group_id="+group_id+"&status="+status);
}
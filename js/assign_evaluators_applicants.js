function init() {
	$("#evaluator_id").change(handleChange);
}

function handleChange(e) {
	$("#filter").submit();
}
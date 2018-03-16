function init() {
	collateScores();
	window.setInterval(collateScores, 3000);
}

function collateScores() {
	var yes_count = 0;
	$(".input-yes").each(function () {
		if(this.checked) yes_count++;
	});

	var no_count = 0;
	$(".input-no").each(function () {
		if(this.checked) no_count++;
	});

	var na_count = 0;
	$(".input-na").each(function () {
		if(this.checked) na_count++;
	});

	$("#yes-count").text(yes_count);
	$("#no-count").text(no_count);
	$("#na-count").text(na_count);
}

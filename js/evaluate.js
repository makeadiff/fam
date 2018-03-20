function init() {
	collateScores();
	window.setInterval(collateScores, 3000);
}

function collateScores() {
	// Reset
	for(var i in counts) {
		counts[i]['yes'] = 0;
		counts[i]['no'] = 0;
		counts[i]['na'] = 0;
	}

	$(".yes-no-na").each(countInput);

	for(var i in counts) {
		$("#" + i+"-yes-count") .text(counts[i]['yes']);
		$("#" + i+"-no-count").text(counts[i]['no']);
		$("#" + i+"-na-count").text(counts[i]['na']);
	}
}

function countInput() {
	var classes = $(this).attr("class").split(" ");

	var parameter = false;
	var yes_no_na = false;

	for(var i in classes) {
		var cls = classes[i];
		if(!cls) continue;
		if(cls == "yes-no-na") continue;

		var matches = cls.match(/input\-(.+)/);
		if(matches) {
			yes_no_na = matches[1];
		} else {
			parameter = cls;
		}
	}
	if(!parameter) return;

	if(this.checked) counts[parameter][yes_no_na]++;
}
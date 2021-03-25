window.addEventListener("load", projectionCheck, false);

var dataReg = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/u;
var oraReg = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/u;

var errors = [false,
			false];

var fields = ["una data (GG-MM-AAAA)",
			"un ora (HH:MM)"];




function projectionCheck () {
  var projectionForm = document.getElementById("edit_projection");
  var dataInput = projectionForm["data"];
	var oraInput = projectionForm["ora"];
  var submitButton = projectionForm["submit"];


  function checkData () {	return dataReg.test(dataInput.value); };
  function checkOra () {	return oraReg.test(oraInput.value); };

  dataInput.onchange = function () { errors[0] = !checkData(); };
	oraInput.onchange = function () { errors[1] = !checkOra(); };

  projectionForm.addEventListener("input", function () {
    submitButton.disabled = !(checkData() && checkOra());
  });



  projectionForm.addEventListener("change", function () {
		var errstr = "";
		var errno = 0;

		for (i = fields.length; i >= 0; i--) {
			if (errors[i]) {
				if (errno == 0)
					errstr = fields[i];
				else if (errno == 1)
					errstr = fields[i] + " e " + errstr;
				else
					errstr = fields[i] + ", " + errstr;
				errno++;
			}
		}

		if (errstr.length > 0) {
			if (errno > 1)
				errstr = "Inserisci " + errstr + " validi.";
			else
				errstr = "Inserisci " + errstr + " valido.";
		}


		document.getElementById("proj_error").innerHTML = errstr;
	});
}

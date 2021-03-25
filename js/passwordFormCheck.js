window.addEventListener("load", passwordFormCheck, false);

var pswErr = ["Inserisci la tua <span xml:lang=\"en\">password</span> attuale.",
			"Inserisci una <span xml:lang=\"en\">password</span> di almeno 8 caratteri.",
			"Le <span xml:lang=\"en\">password</span> non corrispondono."];
var pswCheck = [true, true, true];

function passwordFormCheck () {
	var passwordForm = document.getElementById("change_password");
	var oldPassword = passwordForm["old_password"];
	var newPassword = passwordForm["new_password"];
	var pswRepeat = passwordForm["psw-repeat"];
	var submitButton = passwordForm["submit"];

	submitButton.disabled = true;

	function checkOldPassword () { return oldPassword.value.length > 0; };
	function checkNewPassword () { return newPassword.value.length >= 8; };
	function checkPswRepeat () { return newPassword.value === pswRepeat.value; };

	oldPassword.onchange = function () { pswCheck[0] = checkOldPassword(); };
	newPassword.onchange = function () { pswCheck[1] = checkNewPassword(); pswCheck[2] = checkPswRepeat(); };
	pswRepeat.onchange = function () { pswCheck[2] = checkPswRepeat(); };

	passwordForm.addEventListener("input", function () {
		submitButton.disabled = !(checkOldPassword() &&
								checkNewPassword() &&
								checkPswRepeat());
	});

	passwordForm.addEventListener("change", function () {
		var errstr = "";
		var i = 0;

		do {
			if (!pswCheck[i])
				errstr = pswErr[i];
		} while (pswCheck[i++] && i < pswErr.length);
		
		document.getElementById("error").innerHTML = errstr;
	});
}

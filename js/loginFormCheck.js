window.addEventListener("load", loginFormCheck, false);

var usrErr = "Inserisci un <span xml:lang\"en\">username</span> o una <span xml:lang\"en\">email</span>.";
var pswErr = "Inserisci la <span xml:lang=\"en\">password</span>.";
var usrCheck = true;
var pswCheck = true;

function loginFormCheck () {
	var loginForm = document.getElementById("login_form");
	var usernameInput = loginForm["username_input"];
	var passwordInput = loginForm["password_input"];
	var submitButton = loginForm["submit"];

	submitButton.disabled = true;

	function checkUsername () { return usernameInput.value.length > 0; };
	function checkPassword () { return passwordInput.value.length > 0; };
	
	usernameInput.onchange = function () { usrCheck = checkUsername(); };
	passwordInput.onchange = function () { pswCheck = checkPassword(); };

	loginForm.addEventListener("input", function () {
		submitButton.disabled = !(checkUsername() && checkPassword());
	});

	loginForm.addEventListener("change", function () {
		if (!usrCheck) { document.getElementById("error").innerHTML = usrErr; }
		if (!pswCheck) { document.getElementById("error").innerHTML = pswErr; }
	});
}

window.addEventListener("load", signupFormCheck, false);

var emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var nameRegExp = /^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u;
var specialCharRegExp = /^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/

var errors = [false,
			false,
			false,
			false];
var fields = ["un nome",
			"un cognome",
			"un <span xml:lang=\"en\">username</span>",
			"un indirizzo <span xml:lang=\"en\">email</span>"];
var pswErr1 = "Inserisci una <span xml:lang=\"en\">password</span> di almeno 8 caratteri.";
var pswErr2 = "Le <span xml:lang=\"en\">password</span> non corrispondono.";
var pswErrCode = 0;

function signupFormCheck () {
	var registerForm = document.getElementById("signup_form");
	var emailInput = registerForm["email"];
	var nameInput = registerForm["nome"];
	var surnameInput = registerForm["cognome"];
	var usernameInput = registerForm["username"];
	var passwordInput = registerForm["password"];
	var psrepeatInput = registerForm["psw-repeat"];
	var submitButton = registerForm["submit"];

	submitButton.disabled = true;

	function checkName () {	return nameRegExp.test(nameInput.value); };
	function checkSurname () { return nameRegExp.test(surnameInput.value);	};
	function checkEmail () { return emailRegExp.test(emailInput.value);	};
	function checkUsername () { return specialCharRegExp.test(usernameInput.value); };
	function checkPassword () { return (passwordInput.value.length < 8 ? 2 : 0) + (passwordInput.value === psrepeatInput.value ? 0 : 1); };
	// Valore di ritorno checkPassword():
	// 0 corretta
	// 1 non corrisponde
	// 2 meno di 8 caratteri
	// 3 meno di 8 caratteri e non corrisponde

	nameInput.onchange = function () { errors[0] = !checkName(); };
	surnameInput.onchange = function () { errors[1] = !checkSurname(); };
	usernameInput.onchange = function () { errors[2] = !checkUsername(); };
	emailInput.onchange = function () { errors[3] = !checkEmail(); };
	passwordInput.onchange = function () { pswErrCode = checkPassword() };
	psrepeatInput.onchange = function () { pswErrCode = checkPassword() };

	registerForm.addEventListener("input", function () {
		submitButton.disabled = !(checkName() &&
								checkSurname() &&
								checkEmail() &&
								checkUsername() &&
								checkPassword() == 0);
	});

	registerForm.addEventListener("change", function () {
		var errstr = "";
		var errno = 0;

		for (i = fields.length; i >= 0; i--) {
			if (errors[i]) {
				if (errno == 0)			{ errstr = fields[i]; }
				else if (errno == 1)	{ errstr = fields[i] + " e " + errstr; }
				else					{ errstr = fields[i] + ", " + errstr; }
				errno++;
			}
		}

		if (errstr.length > 0) {
			if (errno > 1)	{ errstr = "Inserisci " + errstr + " validi."; }
			else			{ errstr = "Inserisci " + errstr + " valido."; }
		}
		else {
			if (pswErrCode > 1)		{ errstr = pswErr1; }
			if (pswErrCode === 1)	{ errstr = pswErr2; }
		}

		document.getElementById("error").innerHTML = errstr;
	});
}

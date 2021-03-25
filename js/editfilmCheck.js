window.addEventListener("load", editfilmCheck, false);
var nameRegExp = /^[a-zA-Z0-9-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð :,.'"/<>=-]+$/u;
var regNumero = /^\d+$/;
var regAnno = /^[0-9]{4}$/;
var regTrama = /^[\d/a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð :;,.'"--()/<>=]+$/u;

var regImg = /^$|((\.jpg|\.jpeg|\.bmp|\.gif|\.png)$)/i;
var bool = true;


var errors = [false,
			false,
			false,
			false,
      false,
      false,
      false,
			false];

var fields = ["un titolo",
			"una durata",
      "un anno",
      "una regia",
      "un genere",
      "un <span xml:lang=\"en\">cast</span>",
      "una trama",
			"un file"];


function editfilmCheck () {
  var editForm = document.getElementById("edit_film");
	var titoloInput = editForm["titolo"];
	var durataInput = editForm["durata"];
	var annoInput = editForm["anno"];
	var regiaInput = editForm["regia"];
	var castInput = editForm["cast"];
	var tramaInput = editForm["trama"];
  var genereInput = editForm["genere"];
	var imgInput= editForm["fileToUpload"]
	var submitButton = editForm["submit"];

  function checkTitle () {	return nameRegExp.test(titoloInput.value); };
  function checkDurata () {	return regNumero.test(durataInput.value); };
  function checkAnno () {	return regAnno.test(annoInput.value); };
  function checkRegia () {	return nameRegExp.test(regiaInput.value); };
  function checkGenere () {	return nameRegExp.test(genereInput.value); };
  function checkCast () {	return nameRegExp.test(castInput.value); };
  function checkTrama () {	return regTrama.test(tramaInput.value); };
	function isFileImage() { if(!regImg.exec(imgInput.value)) return false ;
														else return true;};


  titoloInput.onchange = function () { errors[0] = !checkTitle(); };
	durataInput.onchange = function () { errors[1] = !checkDurata(); };
	annoInput.onchange = function () { errors[2] = !checkAnno(); };
	regiaInput.onchange = function () { errors[3] = !checkRegia(); };
  genereInput.onchange = function () { errors[4] = !checkGenere(); };
	castInput.onchange = function () { errors [5] = !checkCast() };
	tramaInput.onchange = function () { errors [6] = !checkTrama() };
	imgInput.onchange = function () { errors [7] = !isFileImage() };

  editForm.addEventListener("input", function () {
    submitButton.disabled = !(checkTitle() && checkAnno() && checkDurata() && checkRegia() &&checkGenere() && checkCast() && checkTrama() && isFileImage());
  });

  editForm.addEventListener("change", function () {
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


		document.getElementById("edit_error").innerHTML = errstr;
	});


}

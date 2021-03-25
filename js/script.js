window.onload = function () {
	var menu = document.getElementById('menu');
	document.getElementById('menu_button').onclick = function () { menu.classList.toggle('menuopen'); };
	document.getElementById('close_button').onclick = function () { menu.classList.remove('menuopen'); };
	document.getElementById('mobile_background').onclick = function () { menu.classList.remove('menuopen'); };
	menu.classList.add('js_on');
};

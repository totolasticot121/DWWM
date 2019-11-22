/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');


// Change navbar properties on scroll position
window.addEventListener('load', () => scrollFunction());
window.addEventListener('scroll', () => scrollFunction());


function scrollFunction() {

	if(document.querySelector('.trigger') !== null)
	{
		// Get the element position
		var elm = document.querySelector('.trigger').offsetTop;
		elm -= 70;
		
		if (document.body.scrollTop >= elm || document.documentElement.scrollTop >= elm)
		{
			lightNav();
		}else{
			blackNav();
		}
	}else{
		lightNav();
	}
}

function blackNav(){
	document.getElementById("nav").classList.remove("two", "shadow");
	document.getElementById("nav").classList.add("one");
	document.getElementById("siteIcon").src="/img/logo_DWWM.png";
	document.querySelectorAll(".toggle-btn").forEach((el) => {
		el.style.backgroundColor = "white";
	})
}

function lightNav(){
	document.getElementById("nav").classList.remove("one");
	document.getElementById("nav").classList.add("two", "shadow");
	document.getElementById("siteIcon").src="/img/blackLogo_DWWM.png";
	document.querySelectorAll(".toggle-btn").forEach((el) => {
		el.style.backgroundColor = "black";
	})	
}

// Blur toggle background on mobile nav
let $collapse = document.querySelector('.tg');
$collapse.addEventListener("click", () => {  document.querySelector('.homeHead').classList.toggle("onToggle"); });
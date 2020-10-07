var navbar_burger = document.getElementById('navbar-burger');
var navbar_menu = document.getElementById('navbar-menu');
if (navbar_burger != undefined && navbar_menu != undefined) {
    navbar_burger.addEventListener('click', function (event) {
        event.preventDefault();
        navbar_burger.classList.toggle('is-active');
        navbar_menu.classList.toggle('is-active');
    }, false);
}

var delete_buttons = document.querySelectorAll('.notification .delete');
for (var i = 0; i < delete_buttons.length; i++) {
    delete_buttons[i].addEventListener('click', function (event) {
        event.preventDefault();
        this.parentNode.parentNode.removeChild(this.parentNode);
    }, false);
}

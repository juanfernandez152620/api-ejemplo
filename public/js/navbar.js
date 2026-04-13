// JavaScript para evitar que el menú se cierre al hacer clic en un enlace de un submenú
document.querySelectorAll('.dropdown-submenu .dropdown-toggle').forEach(function (element) {
  element.addEventListener('click', function (e) {
    var dropdownMenu = this.nextElementSibling;
    if (dropdownMenu.classList.contains('show')) {
      dropdownMenu.classList.remove('show');
    } else {
      dropdownMenu.classList.add('show');
    }
  });
});
document.addEventListener("DOMContentLoaded", function () {
    const dropdownBtn = document.querySelector(".dropdown-btn");
    const dropdownMenu = document.getElementById("userDropdown");

    dropdownBtn.addEventListener("click", function (event) {
        event.stopPropagation(); // Evita que el evento se propague
        dropdownMenu.classList.toggle("show");
    });

    // Cierra el dropdown si se hace clic fuera
    document.addEventListener("click", function (event) {
        if (!dropdownMenu.contains(event.target) && !dropdownBtn.contains(event.target)) {
            dropdownMenu.classList.remove("show");
        }
    });
});



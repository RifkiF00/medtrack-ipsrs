document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.querySelector('.dashboard-wrapper');
    const btnRight = document.getElementById('toggleRight');

    // Fitur Toggle Sidebar Kanan Saja
    if (btnRight && wrapper) {
        btnRight.addEventListener('click', function () {
            wrapper.classList.toggle('hide-right');
        });
    }
});
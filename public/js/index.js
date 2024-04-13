function checkScreenWidth() {
    if (window.innerWidth < 768) {
        document.getElementById('vertical-menu').style.display = 'none';
    } else {
        document.getElementById('vertical-menu').style.display = 'block';
    }
}

checkScreenWidth();

window.addEventListener('resize', checkScreenWidth);
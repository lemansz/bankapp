document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.querySelector('.mobile-menu-overlay');
    const closeBtn = document.querySelector('.close-mobile-menu');
    const mobileLinks = document.querySelectorAll('.mobile-nav a');

    function openMenu() {
        mobileMenu.classList.add('open');
        hamburger.classList.add('hide');
        document.body.style.overflow = 'hidden';
        hamburger.setAttribute('aria-expanded', 'true');
    }
    function closeMenu() {
        mobileMenu.classList.remove('open');
        hamburger.classList.remove('hide');
        document.body.style.overflow = '';
        hamburger.setAttribute('aria-expanded', 'false');
    }

    hamburger.addEventListener('click', function() {
        if (mobileMenu.classList.contains('open')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    closeBtn.addEventListener('click', closeMenu);

    // Optional: close menu when a link is clicked
    mobileLinks.forEach(link => {
        link.addEventListener('click', closeMenu);
    });
});

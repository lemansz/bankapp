// Simple intersection observer to trigger fade-in animations
window.addEventListener('DOMContentLoaded', function() {
    const animatedSections = document.querySelectorAll('.section-animate, .about-us.enhanced, .contact-us, .why-choose-us');
    const heroEls = document.querySelectorAll('.hero h1, .hero p, .hero .cta-btn');
    const serviceEls = document.querySelectorAll('.service');

    // Helper to add .animate class
    function animateOnView(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                observer.unobserve(entry.target);
            }
        });
    }

    const observer = new window.IntersectionObserver(animateOnView, {
        threshold: 0.15
    });

    animatedSections.forEach(section => observer.observe(section));
    heroEls.forEach(el => observer.observe(el));
    serviceEls.forEach(el => observer.observe(el));
});

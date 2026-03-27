// Scroll Animations for Vikasana Institute Website
document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Add scroll-animate class to elements
    const animatedElements = document.querySelectorAll('.hero-vision-mission, .hero-features-3d, .hero-stats-3d, .hero-actions-3d, .quick-stats, .blog-card');
    animatedElements.forEach(el => {
        el.classList.add('scroll-animate');
        observer.observe(el);
    });

    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Parallax effect for hero background
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.hero-background');
        if (parallax) {
            parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
        }

        // Parallax for orbs
        const orbs = document.querySelectorAll('.orb');
        orbs.forEach((orb, index) => {
            const speed = 0.5 + (index * 0.1);
            orb.style.transform = `translateY(${scrolled * speed}px)`;
        });

        // Parallax for particles
        const particles = document.querySelectorAll('.particle');
        particles.forEach((particle, index) => {
            const speed = 0.3 + (index * 0.05);
            particle.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });

    // Counter animation for stats
    const animateCounter = (element, target) => {
        const duration = 2000;
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            // Get the original text to determine the format
            const originalText = element.textContent;
            
            // Format based on the original text pattern or target value
            if (originalText.includes('K+')) {
                element.textContent = (current / 1000).toFixed(0) + 'K+';
            } else if (originalText.includes('%')) {
                element.textContent = Math.floor(current) + '%';
            } else if (originalText.includes('+')) {
                if (target >= 1000) {
                    element.textContent = Math.floor(current).toLocaleString() + '+';
                } else {
                    element.textContent = Math.floor(current) + '+';
                }
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
        }, 16);
    };

    // Trigger counter animation when stats are visible
    const statNumbers = document.querySelectorAll('.stat-number-3d, .impact-number, .banner-stat-number, .stat-number');
    const counterObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                const target = parseInt(entry.target.dataset.target);
                animateCounter(entry.target, target);
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(stat => {
        counterObserver.observe(stat);
    });

    // Enhanced mouse move effect for hero
    const hero = document.querySelector('.hero');
    if (hero) {
        hero.addEventListener('mousemove', (e) => {
            const { clientX, clientY } = e;
            const { innerWidth, innerHeight } = window;
            const x = (clientX / innerWidth - 0.5) * 2;
            const y = (clientY / innerHeight - 0.5) * 2;

            const heroContent = document.querySelector('.hero-content');
            if (heroContent) {
                heroContent.style.transform = `perspective(1000px) rotateY(${x * 2}deg) rotateX(${-y * 2}deg)`;
            }
        });

        hero.addEventListener('mouseleave', () => {
            const heroContent = document.querySelector('.hero-content');
            if (heroContent) {
                heroContent.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
            }
        });
    }

    // Stagger animation for feature cards
    const featureCards = document.querySelectorAll('.feature-card-3d');
    featureCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // Stagger animation for stat cards
    const statCards = document.querySelectorAll('.stat-item-3d');
    statCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});

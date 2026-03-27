// Scroll to top on page refresh
window.addEventListener("beforeunload", () => {
    window.scrollTo(0, 0);
});

// Also scroll to top on page load
window.addEventListener("load", () => {
    window.scrollTo(0, 0);
    document.documentElement.scrollTop = 0;
    document.body.scrollTop = 0;
});

// Immediate mobile header fix - runs before DOMContentLoaded

// DOM Elements
const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");
const navLinks = document.querySelectorAll(".nav-link");
const header = document.querySelector(".header");
const statNumbers = document.querySelectorAll(".stat-number[data-target], .impact-number[data-target]");
const contactForm = document.getElementById("contactForm");
const amountButtons = document.querySelectorAll(".amount-btn");
const customAmountInput = document.getElementById("customAmount");

(function() {
// Navigation fix for both desktop and mobile
function fixNavigation() {
    const navMenu = document.querySelector(".nav-menu");
    const hamburger = document.querySelector(".hamburger");
    
    if (window.innerWidth <= 768) {
        // Mobile: Setup menu styles (only once)
        if (navMenu && !navMenu.hasAttribute('data-mobile-setup')) {
            navMenu.setAttribute('data-mobile-setup', 'true');
            // Don't override CSS, just ensure initial state
            navMenu.classList.remove('active');
        }
        
        if (hamburger && !hamburger.hasAttribute('data-mobile-setup')) {
            hamburger.setAttribute('data-mobile-setup', 'true');
            hamburger.style.display = "flex";
            hamburger.style.visibility = "visible";
            hamburger.style.opacity = "1";
        }
    } else {
        // Desktop: Reset to horizontal menu
        if (navMenu) {
            navMenu.classList.remove('active');
            navMenu.removeAttribute('data-mobile-setup');
        }
        
        if (hamburger) {
            hamburger.classList.remove('active');
            hamburger.removeAttribute('data-mobile-setup');
        }
    }
}

fixNavigation();

// Fix navigation on resize
window.addEventListener("resize", fixNavigation);

// SIMPLE OVERLAY SOLUTION
console.log('=== SIMPLE OVERLAY SOLUTION ===');

setTimeout(() => {
    const hamburger = document.querySelector(".hamburger");
    
    if (!hamburger) {
        console.error('Hamburger not found');
        return;
    }
    
    // Create overlay dynamically
    const overlay = document.createElement('div');
    overlay.id = 'mobileMenuOverlay';
    overlay.innerHTML = `
        <div class="overlay-content">
            <button class="overlay-close">&times;</button>
            <div class="overlay-menu">
                <a href="#home" class="overlay-item">Home</a>
                <a href="#about" class="overlay-item">About</a>
                <a href="#activities" class="overlay-item">Activities</a>
                <a href="#leadership" class="overlay-item">Leadership</a>
                <a href="#blog" class="overlay-item">Blog</a>
                <a href="#contact" class="overlay-item">Contact</a>
                <a href="#donate" class="overlay-item donate">Donate Now</a>
            </div>
        </div>
    `;
    
    // Add overlay styles
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: linear-gradient(135deg, rgba(255, 31, 1, 0.98), rgba(255, 107, 53, 0.98));
        z-index: 999999999;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    
    // Add to body
    document.body.appendChild(overlay);
    
    // Add CSS for overlay content
    const style = document.createElement('style');
    style.textContent = `
        #mobileMenuOverlay {
            font-family: Arial, sans-serif;
        }
        .overlay-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 2rem;
        }
        .overlay-close {
            position: absolute;
            top: 2rem;
            right: 2rem;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .overlay-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
        .overlay-menu {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            align-items: center;
        }
        .overlay-item {
            display: block;
            padding: 1.5rem 3rem;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            min-width: 250px;
            text-align: center;
        }
        .overlay-item:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .overlay-item.donate {
            background: linear-gradient(135deg, #ff1f01, #ff6b35);
            border: none;
            box-shadow: 0 5px 20px rgba(255, 31, 1, 0.3);
        }
        .overlay-item.donate:hover {
            background: linear-gradient(135deg, #ff6b35, #ff1f01);
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 10px 30px rgba(255, 31, 1, 0.4);
        }
        @media (max-width: 768px) {
            .overlay-close {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            .overlay-menu {
                gap: 1rem;
            }
            .overlay-item {
                padding: 1rem 2rem;
                font-size: 1rem;
                min-width: 200px;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Hamburger click
    hamburger.onclick = function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('=== SIMPLE OVERLAY CLICKED ===');
        
        const overlay = document.getElementById('mobileMenuOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
            overlay.style.opacity = '1';
            hamburger.classList.add('active');
            console.log('Simple overlay opened');
        }
    };
    
    // Close handlers
    document.addEventListener('click', function(e) {
        const overlay = document.getElementById('mobileMenuOverlay');
        if (overlay && overlay.style.display === 'flex') {
            if (e.target.id === 'mobileMenuOverlay' || 
                e.target.classList.contains('overlay-close') ||
                e.target.classList.contains('overlay-item')) {
                
                overlay.style.display = 'none';
                overlay.style.opacity = '0';
                hamburger.classList.remove('active');
                console.log('Simple overlay closed');
                
                // Handle navigation
                if (e.target.classList.contains('overlay-item')) {
                    const targetId = e.target.getAttribute('href');
                    if (targetId) {
                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            targetElement.scrollIntoView({ behavior: 'smooth' });
                        }
                    }
                }
            }
        }
    });
    
    // ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const overlay = document.getElementById('mobileMenuOverlay');
            if (overlay && overlay.style.display === 'flex') {
                overlay.style.display = 'none';
                overlay.style.opacity = '0';
                hamburger.classList.remove('active');
                console.log('Overlay closed via ESC');
            }
        }
    });
    
    console.log('Simple overlay setup complete!');
    
}, 500);

// Header scroll effect
window.addEventListener('scroll', () => {
    if (header) {
        if (window.scrollY > 100) {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.backdropFilter = 'blur(10px)';
        } else {
            header.style.background = '#ffffff';
            header.style.backdropFilter = 'none';
        }
    }
});

// Smooth scrolling for navigation links
navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = link.getAttribute('href');
        if (targetId.startsWith('#')) {
            const targetSection = document.querySelector(targetId);
            if (targetSection) {
                const offsetTop = targetSection.offsetTop - 80;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        }
    });
});

// Active navigation link on scroll
window.addEventListener('scroll', () => {
    let current = '';
    const sections = document.querySelectorAll('section');
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (scrollY >= (sectionTop - 200)) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href').slice(1) === current) {
            link.classList.add('active');
        }
    });
});

// Animate numbers on scroll
// Animate numbers on scroll
const animateNumbers = () => {
    statNumbers.forEach(stat => {
        const target = parseInt(stat.getAttribute("data-target"));
        
        // Skip if target is not a valid number (extra safety check)
        if (isNaN(target) || target === null) {
            return;
        }
        
        const increment = target / 100;
        let current = 0;
        
        const updateNumber = () => {
            if (current < target) {
                current += increment;
                stat.textContent = Math.ceil(current).toLocaleString() + (stat.textContent.includes("+") ? "+" : "");
                requestAnimationFrame(updateNumber);
            } else {
                stat.textContent = target.toLocaleString() + (stat.textContent.includes("+") ? "+" : "");
            }
        };
        
        updateNumber();
    });
};

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
            
            // Trigger number animation for stats
            if (entry.target.classList.contains('impact-stats') || entry.target.classList.contains('hero-stats')) {
                animateNumbers();
                observer.unobserve(entry.target);
            }
        }
    });
}, observerOptions);

// Observe elements for animation
// Observe elements for animation (exclude donation section)
document.addEventListener("DOMContentLoaded", () => {
    const animatedElements = document.querySelectorAll(".service-card, .timeline-item, .impact-card, .story-card, .founder-content, .contact-item");
    animatedElements.forEach(el => {
        // Skip elements inside donation section
        if (!el.closest(".donate")) {
            observer.observe(el);
        }
    });
    
    // Observe stats sections (exclude donation section)
    const statsSections = document.querySelectorAll(".impact-stats, .hero-stats");
    statsSections.forEach(el => {
        // Skip elements inside donation section
        if (!el.closest(".donate")) {
            observer.observe(el);
        }
    });
});

// Contact form submission
contactForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const formData = new FormData(contactForm);
    const data = Object.fromEntries(formData);
    
    // Show success message (in real implementation, this would send to a server)
    showNotification('Thank you for your message! We will get back to you soon.', 'success');
    contactForm.reset();
});

// Donation amount selection
if (amountButtons.length > 0) {
    amountButtons.forEach(button => {
        button.addEventListener('click', () => {
            amountButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            const amount = button.getAttribute('data-amount');
            if (amount === 'custom' && customAmountInput) {
                customAmountInput.style.display = 'block';
                customAmountInput.focus();
            } else if (customAmountInput) {
                customAmountInput.style.display = 'none';
                customAmountInput.value = amount;
            }
        });
    });
}

// Custom amount input
if (customAmountInput) {
    customAmountInput.addEventListener('input', (e) => {
        if (amountButtons.length > 0) {
            amountButtons.forEach(btn => btn.classList.remove('active'));
            const customButton = document.querySelector('[data-amount="custom"]');
            if (customButton) customButton.classList.add('active');
        }
    });
}

// Donate button functionality
const donateButton = document.querySelector('.btn-donate-large');
if (donateButton && customAmountInput) {
    donateButton.addEventListener('click', () => {
        const amount = customAmountInput.value;
        if (amount && amount >= 100) {
            showNotification(`Thank you for your generous donation of ₹${amount}!`, 'success');
            // In real implementation, this would redirect to payment gateway
        } else {
            showNotification('Please enter a valid donation amount (minimum ₹100)', 'error');
        }
    });
}

// Contact form submission
if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const formData = new FormData(contactForm);
        const data = Object.fromEntries(formData);
        
        // Show success message (in real implementation, this would send to a server)
        showNotification('Thank you for your message! We will get back to you soon.', 'success');
        contactForm.reset();
    });
}

// Add smooth reveal animation for sections
// Add smooth reveal animation for sections (exclude donate section)
const sections = document.querySelectorAll("section:not(.donate)");
const sectionObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add("revealed");
        }
    });
}, { threshold: 0.1 });

sections.forEach(section => {
    section.style.opacity = "0";
    section.style.transform = "translateY(30px)";
    section.style.transition = "all 0.8s ease";
    sectionObserver.observe(section);
});

// Mobile-specific fix: Ensure about section is immediately visible on mobile
// Navigation fix for both desktop and mobile
function fixNavigation() {
    if (window.innerWidth <= 768) {
        // Mobile: Show hamburger, hide menu
        const navMenu = document.querySelector(".nav-menu");
        const hamburger = document.querySelector(".hamburger");
        
        if (navMenu) {
            navMenu.style.display = "none";
            navMenu.style.position = "fixed";
            navMenu.style.top = "80px";
            navMenu.style.left = "0";
            navMenu.style.width = "100%";
            navMenu.style.background = "var(--white)";
            navMenu.style.boxShadow = "var(--shadow-lg)";
            navMenu.style.padding = "2rem";
            navMenu.style.zIndex = "999";
            navMenu.style.visibility = "hidden";
            navMenu.style.opacity = "0";
            navMenu.style.flexDirection = "column";
        }
        
        if (hamburger) {
            hamburger.style.display = "flex";
            hamburger.style.visibility = "visible";
            hamburger.style.opacity = "1";
        }
    } else {
        // Desktop: Show horizontal menu, hide hamburger
        const navMenu = document.querySelector(".nav-menu");
        const hamburger = document.querySelector(".hamburger");
        
        if (navMenu) {
            navMenu.style.display = "flex";
            navMenu.style.position = "static";
            navMenu.style.top = "auto";
            navMenu.style.left = "auto";
            navMenu.style.width = "auto";
            navMenu.style.background = "none";
            navMenu.style.boxShadow = "none";
            navMenu.style.padding = "0";
            navMenu.style.zIndex = "auto";
            navMenu.style.visibility = "visible";
            navMenu.style.opacity = "1";
            navMenu.style.flexDirection = "row";
        }
        
        if (hamburger) {
            hamburger.style.display = "none";
            hamburger.style.visibility = "hidden";
            hamburger.style.opacity = "0";
        }
    }
}

fixNavigation();

// Fix navigation on resize
window.addEventListener("resize", fixNavigation);



// Add CSS for revealed sections
const revealStyle = document.createElement('style');
revealStyle.textContent = `
    section.revealed {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
`;
document.head.appendChild(revealStyle);

// Initialize tooltips (if needed)
document.querySelectorAll('[data-tooltip]').forEach(element => {
    element.addEventListener('mouseenter', (e) => {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = e.target.getAttribute('data-tooltip');
        tooltip.style.cssText = `
            position: absolute;
            background: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            z-index: 1000;
            pointer-events: none;
        `;
        document.body.appendChild(tooltip);
        
        const rect = e.target.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
        
        e.target.tooltip = tooltip;
    });
    
    element.addEventListener('mouseleave', (e) => {
        if (e.target.tooltip) {
            e.target.tooltip.remove();
            delete e.target.tooltip;
        }
    });
});

// Banner carousel auto-scroll functionality
const bannerCarousel = document.querySelector('.hero-banner-carousel');
const bannerSlides = document.querySelectorAll('.banner-slide');
const bannerDots = document.querySelectorAll('.banner-dot');

let currentSlide = 0;
let autoScrollInterval;

const showSlide = (index) => {
    console.log(`Showing slide ${index}`);
    // Remove active class from all dots
    bannerDots.forEach(dot => dot.classList.remove('active'));
    
    // Add active class to current dot
    bannerDots[index].classList.add('active');
    
    // Move the carousel container to show the current slide
    const container = document.querySelector('.banner-carousel-container');
    if (container) {
        container.style.transform = `translateX(-${index * 100}%)`;
        console.log(`Carousel transformed to: translateX(-${index * 100}%)`);
    }
    
    currentSlide = index;
};

const nextSlide = () => {
    const nextIndex = (currentSlide + 1) % bannerSlides.length;
    console.log(`Moving to next slide: ${nextIndex}`);
    showSlide(nextIndex);
};

const startAutoScroll = () => {
    console.log('Starting auto-scroll');
    if (autoScrollInterval) {
        clearInterval(autoScrollInterval);
    }
    autoScrollInterval = setInterval(() => {
        console.log('Auto-scroll interval triggered');
        nextSlide();
    }, 5000); // 5 seconds
};

const stopAutoScroll = () => {
    console.log('Stopping auto-scroll');
    if (autoScrollInterval) {
        clearInterval(autoScrollInterval);
        autoScrollInterval = null;
    }
};

// Initialize carousel with proper timing
if (bannerCarousel && bannerSlides.length > 0) {
    console.log(`Found ${bannerSlides.length} banner slides`);
    // Show first slide initially
    showSlide(0);
    
    // Start auto-scrolling after loader is hidden (3.5 seconds to be safe)
    setTimeout(() => {
        console.log('Starting carousel auto-scroll after delay');
        startAutoScroll();
    }, 3500); // 3.5 seconds delay to ensure loader is hidden first
    
    // Add click event to dots
    bannerDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            console.log(`Dot ${index} clicked`);
            showSlide(index);
            startAutoScroll(); // Reset timer on manual click
        });
    });
    
    // Pause on hover
    bannerCarousel.addEventListener('mouseenter', () => {
        console.log('Mouse entered carousel - pausing');
        stopAutoScroll();
    });
    
    bannerCarousel.addEventListener('mouseleave', () => {
        console.log('Mouse left carousel - resuming');
        startAutoScroll();
    });
} else {
    console.log('Banner carousel or slides not found');
}

// Loader functionality - Multiple approaches to ensure it works
const hideLoader = () => {
    console.log('Attempting to hide loader...');
    document.body.classList.add('loaded');
    console.log('Loader hidden - loaded class added');
};

// Method 1: Window load event
window.addEventListener('load', () => {
    console.log('Window load event fired');
    // Hide loader after 2.5 seconds minimum
    setTimeout(hideLoader, 2500);
});

// Method 2: DOM ready as backup
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM content loaded');
    // Give a short delay then hide loader
    setTimeout(hideLoader, 3000);
});

// Method 3: Failsafe timeout
setTimeout(() => {
    if (!document.body.classList.contains('loaded')) {
        console.log('Failsafe triggered - hiding loader');
        hideLoader();
    }
}, 6000); // Force hide after 6 seconds even if other events don't fire

// Method 4: Handle image loading errors
document.addEventListener('DOMContentLoaded', () => {
    const images = document.querySelectorAll('img');
    let loadedImages = 0;
    const totalImages = images.length;
    
    console.log(`Found ${totalImages} images to load`);
    
    if (totalImages === 0) {
        // No images to load, hide loader after 2.5 seconds
        setTimeout(hideLoader, 2500);
        return;
    }
    
    images.forEach(img => {
        // Handle already loaded images
        if (img.complete) {
            loadedImages++;
            console.log(`Image already loaded: ${img.src}`);
        } else {
            // Handle loading images
            img.addEventListener('load', () => {
                loadedImages++;
                console.log(`Image loaded: ${img.src} (${loadedImages}/${totalImages})`);
                if (loadedImages === totalImages) {
                    console.log('All images loaded');
                    setTimeout(hideLoader, 2500);
                }
            });
            
            // Handle image loading errors
            img.addEventListener('error', () => {
                loadedImages++;
                console.log('Image failed to load:', img.src);
                if (loadedImages === totalImages) {
                    console.log('All images processed (some with errors)');
                    setTimeout(hideLoader, 2500);
                }
            });
        }
    });
    
    // If all images were already loaded
    if (loadedImages === totalImages) {
        console.log('All images were already loaded');
        setTimeout(hideLoader, 2500);
    }
});

// Manual loader hide for testing (press 'h' key)
document.addEventListener('keydown', (e) => {
    if (e.key === 'h' || e.key === 'H') {
        console.log('Manual loader hide triggered');
        hideLoader();
    }
});

// Console log for debugging
console.log('Vikasana NGO Website - All scripts loaded successfully!');

// Achievements Auto-scroll
document.addEventListener('DOMContentLoaded', function() {
    const achievementsTrack = document.querySelector('.achievements-track');
    if (achievementsTrack) {
        // Clone the items for seamless scrolling
        const achievementsItems = achievementsTrack.innerHTML;
        achievementsTrack.innerHTML += achievementsItems;
        
        // Pause on hover
        achievementsTrack.addEventListener('mouseenter', () => {
            achievementsTrack.style.animationPlayState = 'paused';
        });
        
        achievementsTrack.addEventListener('mouseleave', () => {
            achievementsTrack.style.animationPlayState = 'running';
        });
    }
});
const blogCarouselTrack = document.querySelector('.blog-carousel-track');
const blogCards = document.querySelectorAll('.blog-card');
const blogPrevBtn = document.querySelector('.blog-carousel-prev');
const blogNextBtn = document.querySelector('.blog-carousel-next');
const blogIndicators = document.querySelectorAll('.blog-indicator');

// Read Aloud functionality
const readAloudBtns = document.querySelectorAll('.read-aloud-btn');
let currentSpeech = null;

readAloudBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const blogCard = btn.closest('.blog-card');
        const title = blogCard.querySelector('h3').textContent;
        const description = blogCard.querySelector('p').textContent;
        const textToRead = `${title}. ${description}`;
        
        // Cancel any ongoing speech
        if (currentSpeech) {
            window.speechSynthesis.cancel();
            currentSpeech = null;
            btn.classList.remove('reading');
            btn.innerHTML = '<i class="fas fa-volume-up"></i>';
            return;
        }
        
        // Start new speech
        if ('speechSynthesis' in window) {
            currentSpeech = new SpeechSynthesisUtterance(textToRead);
            currentSpeech.lang = 'en-US';
            currentSpeech.rate = 0.9;
            currentSpeech.pitch = 1;
            currentSpeech.volume = 1;
            
            currentSpeech.onstart = () => {
                btn.classList.add('reading');
                btn.innerHTML = '<i class="fas fa-stop"></i>';
            };
            
            currentSpeech.onend = () => {
                btn.classList.remove('reading');
                btn.innerHTML = '<i class="fas fa-volume-up"></i>';
                currentSpeech = null;
            };
            
            currentSpeech.onerror = () => {
                btn.classList.remove('reading');
                btn.innerHTML = '<i class="fas fa-volume-up"></i>';
                currentSpeech = null;
                console.error('Speech synthesis error');
            };
            
            window.speechSynthesis.speak(currentSpeech);
        } else {
            console.log('Speech synthesis not supported');
        }
    });
});

if (blogCarouselTrack && blogCards.length > 0) {
    let currentBlogSlide = 0;
    const cardsPerSlide = 3;
    const totalSlides = Math.ceil(blogCards.length / cardsPerSlide);
    let autoScrollInterval;

    const updateCarousel = () => {
        // Calculate the exact position for 3 cards with gaps
        const cardWidth = (100 - 3) / 3; // Subtract gaps from total width
        const gapWidth = 1.5; // 1.5rem gap
        const slideOffset = currentBlogSlide * (cardWidth * 3 + gapWidth * 2);
        blogCarouselTrack.style.transform = `translateX(-${slideOffset}%)`;
        
        // Update indicators
        blogIndicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentBlogSlide);
        });
        
        // Update button states
        blogPrevBtn.disabled = currentBlogSlide === 0;
        blogNextBtn.disabled = currentBlogSlide >= totalSlides - 1;
        
        console.log(`Blog carousel: Showing slide ${currentBlogSlide + 1} of ${totalSlides}, offset: ${slideOffset}%`);
    };

    const nextBlogSlide = () => {
        if (currentBlogSlide < totalSlides - 1) {
            currentBlogSlide++;
            updateCarousel();
        }
    };

    const prevBlogSlide = () => {
        if (currentBlogSlide > 0) {
            currentBlogSlide--;
            updateCarousel();
        }
    };

    const goToBlogSlide = (slideIndex) => {
        if (slideIndex >= 0 && slideIndex < totalSlides) {
            currentBlogSlide = slideIndex;
            updateCarousel();
        }
    };

    const startBlogAutoScroll = () => {
        if (autoScrollInterval) {
            clearInterval(autoScrollInterval);
        }
        autoScrollInterval = setInterval(() => {
            if (currentBlogSlide >= totalSlides - 1) {
                currentBlogSlide = 0; // Loop back to start
            } else {
                currentBlogSlide++;
            }
            updateCarousel();
        }, 4000); // Auto-scroll every 4 seconds
    };

    const stopBlogAutoScroll = () => {
        if (autoScrollInterval) {
            clearInterval(autoScrollInterval);
        }
    };

    // Event listeners
    blogNextBtn.addEventListener('click', () => {
        nextBlogSlide();
        startBlogAutoScroll(); // Reset timer on manual interaction
    });

    blogPrevBtn.addEventListener('click', () => {
        prevBlogSlide();
        startBlogAutoScroll(); // Reset timer on manual interaction
    });

    blogIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            goToBlogSlide(index);
            startBlogAutoScroll(); // Reset timer on manual interaction
        });
    });

    // Pause auto-scroll on hover
    blogCarouselTrack.addEventListener('mouseenter', stopBlogAutoScroll);
    blogCarouselTrack.addEventListener('mouseleave', startBlogAutoScroll);

    // Initialize carousel
    updateCarousel();
    
    // Start auto-scroll after 3 seconds
    setTimeout(startBlogAutoScroll, 3000);

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.target.closest('.blog-carousel-container')) {
            if (e.key === 'ArrowLeft') {
                prevBlogSlide();
                startBlogAutoScroll();
            } else if (e.key === 'ArrowRight') {
                nextBlogSlide();
                startBlogAutoScroll();
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', () => {
        updateCarousel();
    });
}
})();

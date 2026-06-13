/**
 * FlexiCore - Premium HTML Template Custom Scripts
 * @author SmartSoftFirm 
 * @version 1.0.0
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // Smooth Scrolling Behavior for internal anchors
    const initSmoothScroll = () => {
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    };

    // Example Interactive Button Action
    const initInteractiveButton = () => {
        const actionBtn = document.getElementById('actionBtn');
        if (actionBtn) {
            actionBtn.addEventListener('click', () => {
                const featuresSection = document.getElementById('features');
                if (featuresSection) {
                    featuresSection.scrollIntoView({ behavior: 'smooth' });
                }
            });
        }
    };

    // Run Initializations
    initSmoothScroll();
    initInteractiveButton();
});
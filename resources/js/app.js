import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Global Progress Bar Logic
document.addEventListener('DOMContentLoaded', () => {
    const bar = document.getElementById('top-progress-bar');
    
    // Check if we are completing a navigation
    if (bar && sessionStorage.getItem('navigating') === 'true') {
        // Prepare finishing state - start from 80% to avoid a big jump
        bar.style.transition = 'none';
        bar.style.width = '80%';
        bar.style.opacity = '1';
        void bar.offsetWidth; // Force reflow
        
        // Let CSS handle the smooth slide to 100%
        bar.style.transition = '';
        bar.classList.add('finishing');
        sessionStorage.removeItem('navigating');
        
        // Fade out after completion
        setTimeout(() => {
            bar.style.opacity = '0';
            setTimeout(() => {
                bar.classList.remove('finishing', 'loading');
                bar.style.width = '0%';
            }, 400);
        }, 500);
    }

    const startLoading = () => {
        if (!bar) return;
        
        // Flag that we are navigating
        sessionStorage.setItem('navigating', 'true');
        
        // Reset instantly
        bar.style.transition = 'none';
        bar.style.width = '0%';
        bar.style.opacity = '1';
        bar.classList.remove('finishing', 'loading');
        void bar.offsetWidth; 
        
        // Phase 1: Quick Jump to 30%
        bar.style.transition = 'width 0.4s ease-out';
        bar.style.width = '30%';
        
        // Phase 2: Start the slow crawl
        setTimeout(() => {
            if (sessionStorage.getItem('navigating') === 'true') {
                bar.classList.add('loading');
            }
        }, 400);
    };

    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (link && 
            link.href && 
            link.href.startsWith(window.location.origin) && 
            !link.hasAttribute('download') && 
            link.target !== '_blank' &&
            !link.href.includes('#') &&
            !e.metaKey && !e.ctrlKey) {
            
            // Exclude paths that shouldn't have the loading bar
            const excludedPaths = ['/logout-success'];
            const isHome = link.pathname === '/' || link.href === window.location.origin + '/';
            const isExcluded = excludedPaths.some(path => link.pathname.startsWith(path)) || isHome;

            if (bar && !isExcluded) {
                // For a faster feel, we use a much shorter delay (150ms)
                // just enough to let the animation start before the browser takes over
                startLoading();
                
                e.preventDefault();
                setTimeout(() => {
                    window.location.href = link.href;
                }, 150);
            }
        }
    });

    // Show progress bar on form submissions (like uploads)
    document.addEventListener('submit', (e) => {
        if (bar && !e.defaultPrevented) {
            startLoading();
            
            // Add a small delay for forms that don't have preventDefault, to let the animation start
            const form = e.target;
            if (!form.hasAttribute('data-no-delay')) {
                e.preventDefault();
                setTimeout(() => {
                    HTMLFormElement.prototype.submit.call(form);
                }, 150);
            }
        }
    });
});

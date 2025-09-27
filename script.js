// --- START OF FILE script.js (COMPLETE AND CORRECT VERSION) ---
document.addEventListener("DOMContentLoaded", function() {
    // This part ensures the 'loaded' class is added to body for CSS animations
    document.body.classList.add("loaded");

    // Initialize page visibility based on URL hash or default to 'home'
    // This line is crucial for setting the initial active page after login
    // If the URL has a hash (e.g., #about), it tries to show that page. Otherwise, defaults to 'home'.
    const initialPage = window.location.hash ? window.location.hash.substring(1) : 'home';
    showPage(initialPage);
});

// Function to switch pages (used by navbar links and logo click)
function showPage(pageId) {
    // Hide all pages by removing the 'active' class
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });

    // Show the selected page by adding the 'active' class
    const selectedPage = document.getElementById(pageId);
    if (selectedPage) {
        selectedPage.classList.add('active');
        // Update URL hash without causing a full page reload (optional but good practice)
        // This makes the URL reflect the current page (e.g., #home, #about)
        if (history.pushState) {
            history.pushState(null, null, `#${pageId}`);
        } else {
            window.location.hash = pageId;
        }
    } else {
        // Log a warning if the target page ID does not exist in the HTML
        console.warn(`Page with ID '${pageId}' not found in the DOM.`);
    }
}

// Placeholder for setuplayzloading function.
// This function was referenced in your previous console errors,
// so defining it here prevents "ReferenceError: setuplayzloading is not defined".
// In modern browsers, native lazy loading (loading="lazy" in img tags)
// combined with CSS opacity transitions is often preferred.
function setuplayzloading() {
    console.log("setuplayzloading function called (placeholder).");
    // If you had specific custom lazy loading JavaScript logic previously,
    // you would place it here. For this project, CSS handles the fade-in.
    // However, if any <img> tags use loading="lazy", we should ensure they become 'loaded'.
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    lazyImages.forEach(img => {
        // Simple check to add 'loaded' class if image is already in viewport or loaded
        if (img.complete || img.naturalHeight !== 0) { // Check if image is loaded
            img.classList.add('loaded');
        } else {
            img.addEventListener('load', () => img.classList.add('loaded'));
        }
    });
}

// Automatically call setuplayzloading when DOM is fully loaded,
// similar to how it might have been called previously if it existed.
document.addEventListener("DOMContentLoaded", setuplayzloading);

// --- END OF FILE script.js (COMPLETE AND CORRECT VERSION) ---
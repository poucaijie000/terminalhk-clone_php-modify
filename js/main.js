/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Home-page carousel and shared client-side interactions.
 */

document.addEventListener("DOMContentLoaded", function() {
    const slides = document.querySelectorAll("#bannerSlides img");
    const dots = document.querySelectorAll("#sliderDots span");
    let currentSlide = 0;
    const totalSlides = slides.length;

    // Switch to a specific image
    function goToSlide(index) {
        // Remove the active class from all items
        slides.forEach(slide => slide.classList.remove("active"));
        dots.forEach(dot => dot.classList.remove("active"));

        // Add the active class to the current image and dot
        slides[index].classList.add("active");
        dots[index].classList.add("active");
        currentSlide = index;
    }

    // Auto-advance every 4 seconds
    let slideInterval = setInterval(() => {
        let nextSlide = (currentSlide + 1) % totalSlides;
        goToSlide(nextSlide);
    }, 4000);

    // Switch images when a dot is clicked
    dots.forEach((dot, index) => {
        dot.addEventListener("click", () => {
            clearInterval(slideInterval); // Reset the timer after a manual click
            goToSlide(index);
            // Restart the auto-advance timer
            slideInterval = setInterval(() => {
                let nextSlide = (currentSlide + 1) % totalSlides;
                goToSlide(nextSlide);
            }, 4000);
        });
    });
});
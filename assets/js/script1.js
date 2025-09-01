let currentIndex = 0;
const images = document.querySelectorAll('.carousel-image');

if (images.length > 0) {  // ✅ Only run if images exist
    function changeHeroImage() {
        images[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % images.length;
        images[currentIndex].classList.add('active');
    }
    setInterval(changeHeroImage, 5000); // Changes every 5 seconds
} else {
    console.warn("⚠ No images found with class 'carousel-image'.");
}

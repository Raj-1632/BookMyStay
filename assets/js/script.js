let currentIndex = 0;

function showSlide(index) {
  const slides = document.querySelectorAll('.slide');
  const totalSlides = slides.length;
  
  // Wrap around when going out of bounds
  if (index >= totalSlides) {
    currentIndex = 0;
  } else if (index < 0) {
    currentIndex = totalSlides - 1;
  } else {
    currentIndex = index;
  }
  
  const offset = -currentIndex * 100;
  document.querySelector('.slider').style.transform = `translateX(${offset}%)`;
}

function changeSlide(step) {
  showSlide(currentIndex + step);
}

// Auto slide every 3 seconds
setInterval(() => changeSlide(1), 3000);

// Initialize the first slide
showSlide(currentIndex);

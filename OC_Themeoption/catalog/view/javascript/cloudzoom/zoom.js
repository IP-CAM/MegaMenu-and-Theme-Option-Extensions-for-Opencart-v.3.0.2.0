$(document).ready(function () {
   $('.product-zoom-image').on('click', function () {
      alert('a');
   });

   initAdditionalImagesOwl();
});

function initAdditionalImagesOwl() {
   $('.additional-images').owlCarousel({
      loop: false,
      margin: 30,
      nav: false,
      dots: false,
      responsive:{
         0: {
            items: 1
         },
         480: {
            items: 2
         },
         768: {
            items: 3
         },
         992: {
            items: 3
         },
         1200: {
            items: 4
         }
      }
   });
}

$(document).ready(function () {
    $('.product-zoom-image').on('click', function () {
        var pos = $('#light-box-position').val();

        oczoom.openLightBox(pos);
    });

    $('.sub-image').on('click', function () {
        var pos = $(this).data('pos');
        $('#light-box-position').val(pos);
    });

    oczoom.initAdditionalImagesOwl();
});

var oczoom = {
    'initAdditionalImagesOwl'  : function () {
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
    },

    'openLightBox' : function (position) {
        var product_id = $('#product-identify').val();
        var flag = false;

        $.ajax({
            url : 'index.php?route=product/oczoom/openLightbox&product_id=' + product_id,
            type: 'get',
            success : function (json) {
                $('.lightbox-container').html(json['html']).show(500);
                oczoom.showSlides(position);
                flag = true;
            },
            complete: function () {
                if(!flag) {
                    oczoom.closeLightBox();
                }
            }
        });
    },

    'showSlides' : function (position) {
        var i;
        var slides = $(".mySlides");

        if (position > slides.length) {position = 1}
        if (position < 1) {position = slides.length}

        $('#light-box-position').val(position);

        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        slides[position-1].style.display = "block";
    },

    'plusSlides' : function (n) {
        var position = parseInt($('#light-box-position').val());

        oczoom.showSlides(position += n);
    },

    'closeLightBox': function () {
        $('.lightbox-container').hide().html('');
    }
}
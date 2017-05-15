var xhrPool = [];

// Create repeat method if it is not exists
if (!String.repeat) {
    String.prototype.repeat = function(l) {
        return new Array(l + 1).join(this);
    }
}

(function ($) {

    var deTube = {
        init: function(){

            deTube.lessMore();

            /*= Responsive Navigation Menu */
            $('#main-nav .menu').deSelectMenu({});

            // Change event on select element
            $('.orderby-select').change(function() {
                location.href = this.options[this.selectedIndex].value;
            });
        },

        /* "More/less" Toggle */
        lessMore: function(){
            var lessHeight = $('#info').data('less-height');
            trueHeight = $('#info').outerHeight(false);

            if(trueHeight > lessHeight) {
                $('.info-toggle-arrow').css('display', 'inline-block');
                $('.info-toggle').show();
                $('#info').height(lessHeight);
            }

            $('.info-toggle-button, .info-toggle-arrow').click(function() {
                $('#info').toggleClass('info-more');
                $('.info-toggle-button').toggleClass('info-more-button');
                $('.info-toggle-arrow').toggleClass('info-more-arrow');

                return false;
            })
        }
    }

    $(document).ready(function(){

        deTube.init();
    });

    $(window).on('load resize', function(){
        $('.fcarousel-5').deCarousel();
        $('.fcarousel-6').deCarousel();
    });

    /*== Ajax Video, List Large View */
    $(function() {
        if($('.loop-content').data('ajaxload')) {
            $('.item-video .thumb a').on('click', function(e){
                if($(this).parents('.list-large').length) {
                    e.preventDefault();

                    // Stop other videos
                    $('.list-large .video').remove();
                    $('.list-large .thumb').show().removeClass('loading');

                    deTube.ajaxVideo(this);

                    return false;
                }
            });
        }
    });

// Home Featured, Full Width
    $(function() {
        var stage = $('.home-featured-full .stage'),
            carouselStage = stage.find('.carousel');
        deTube.stageSetup(stage);
        if(jQuery().jcarousel) {
            carouselStage.jcarousel({wrap: 'circular'});
            deTube.autoScroll(carouselStage);
            deTube.targetedStage(carouselStage);
            var carouselNav = $('.fcarousel-6').deCarousel();
            deTube.connectedControl(carouselStage, carouselNav);
            deTube.clickAjax('.home-featured-full .stage .item-video .thumb a', stage, carouselStage);
        }
    });

// Home Featured, Standard Layout
    $(function() {
        var stage = $('.home-featured .stage');
        var carouselStage = stage.find('.carousel');
        deTube.stageSetup(stage);
        if(jQuery().jcarousel) {
            carouselStage.jcarousel({wrap: 'circular'});
            deTube.autoScroll(carouselStage);
            deTube.targetedStage(carouselStage);

            // Setup the navigation carousel
            var carouselNav = $('.home-featured .nav .carousel-clip')
                .jcarousel({
                    vertical: true,
                    wrap: 'circular'
                });

            // Setup controls for the navigation carousel
            $('.home-featured .carousel-prev').jcarouselControl({target: '-=4'});
            $('.home-featured .carousel-next').jcarouselControl({target: '+=4'});

            deTube.connectedControl(carouselStage, carouselNav);
        }
        deTube.clickAjax('.home-featured .stage .item-video .thumb a', stage, carouselStage);
    });

}(jQuery));
/*
 * slick-carousel for capsules
 * build 17.06.2020 | anthonysalamin.ch
 */
(function carousel() {
    $("#slick-slider").slick({
        // options
        variableWidth: true,
        initialSlide: 0,
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 8000,
        arrows: false,
        cssEase: "linear",
        infinite: true,
        pauseOnHover: false,
        pauseOnFocus: false
    });
})();
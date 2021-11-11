
for (let i = 0; i < swiper_configs.length; i++) {
    let swiper_config = swiper_configs[i];
    new Swiper('#' + swiper_config.id, {
        autoHeight: swiper_config.auto_height,
        slidesPerView: 1,
        spaceBetween: 20,
        breakpoints: {
            720: {
                slidesPerView: swiper_config.slides_per_view,
                spaceBetween: 30
            }
        },
        loop: true,
        pagination: {
            el: '.swiper-pagination',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 5000,
        },
    });
}
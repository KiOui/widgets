for (let i = 0; i < swiper_configs.length; i++) {
    let swiper_config = swiper_configs[i];
    new Swiper('#' + swiper_config.id, {
        slidesPerView: 1,
        spaceBetween: 20,
        breakpoints: {
            720: {
                slidesPerView: swiper_config.slides_per_view,
                spaceBetween: 30
            }
        },
        loop: true,
        autoplay: {
            delay: 2000,
        },
    });
}

for (let i = 0; i < swiper_configs.length; i++) {
    let swiper_config = swiper_configs[i];
    let swiper = new Swiper('#' + swiper_config.id, {
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
        disableOnInteraction: true,
    });
}
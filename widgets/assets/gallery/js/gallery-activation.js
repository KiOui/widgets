
for (let i = 0; i < gallery_configs.length; i++) {
    let macy_config = gallery_configs[i];
    Macy({
        container: '#' + macy_config.id,
        columns: 3,
        waitForImages: false,
        margin: 10,
        breakAt: {
            1200: 3,
            940: 2,
            520: 2,
            400: 1
        }
    });
}
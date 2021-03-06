jQuery( function( $ ) {
    let gallery_images_container = $( '#gallery-images-container' );

    let image_gallery_frame;
    let $image_gallery_ids = $( '#gallery-images' );
    let $product_images    = gallery_images_container.find( 'ul.gallery-images' );

    $( '.add-gallery-images' ).on( 'click', 'a', function( event ) {
        let $el = $( this );

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( image_gallery_frame ) {
            image_gallery_frame.open();
            return;
        }

        // Create the media frame.
        image_gallery_frame = wp.media.frames.product_gallery = wp.media({
            // Set the title of the modal.
            title: $el.data( 'choose' ),
            button: {
                text: $el.data( 'update' )
            },
            states: [
                new wp.media.controller.Library({
                    title: $el.data( 'choose' ),
                    filterable: 'all',
                    multiple: true
                })
            ]
        });

        // When an image is selected, run a callback.
        image_gallery_frame.on( 'select', function() {
            let selection = image_gallery_frame.state().get( 'selection' );
            let attachment_ids = $image_gallery_ids.val();

            selection.map( function( attachment ) {
                attachment = attachment.toJSON();

                if ( attachment.id ) {
                    attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                    let attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                    $product_images.append(
                        '<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image +
                        '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' +
                        $el.data('text') + '</a></li></ul></li>'
                    );
                }
            });

            $image_gallery_ids.val( attachment_ids );
        });

        // Finally, open the modal.
        image_gallery_frame.open();
    });

    // For ordering images
    $product_images.sortable({
        items: 'li.image',
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        forceHelperSize: false,
        helper: 'clone',
        opacity: 0.65,
        placeholder: 'metabox-sortable-placeholder',
        start: function( event, ui ) {
            ui.item.css( 'background-color', '#f6f6f6' );
        },
        stop: function( event, ui ) {
            ui.item.removeAttr( 'style' );
        },
        update: function() {
            let attachment_ids = '';

            gallery_images_container.find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
                let attachment_id = $( this ).attr( 'data-attachment_id' );
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            $image_gallery_ids.val( attachment_ids );
        }
    });

    // For removing images
    gallery_images_container.on( 'click', 'a.delete', function() {
        $( this ).closest( 'li.image' ).remove();

        let attachment_ids = '';

        gallery_images_container.find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
            let attachment_id = $( this ).attr( 'data-attachment_id' );
            attachment_ids = attachment_ids + attachment_id + ',';
        });

        $image_gallery_ids.val( attachment_ids );

        // Remove any lingering tooltips.
        $( '#tiptip_holder' ).removeAttr( 'style' );
        $( '#tiptip_arrow' ).removeAttr( 'style' );

        return false;
    });
});

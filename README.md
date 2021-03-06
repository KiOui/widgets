# Widgets Collection

A collection of [Wordpress](https://wordpress.com/) widgets. All of the widgets included in this plugin are pluggable, 
meaning that they can be disabled in the settings of the plugin. The plugin will only load enabled widgets (so your site 
won't slow down due to widgets that you are not using).

The collection of widgets currently includes:

- Testimonial slider and page
- Image masonry gallery

## Testimonial slider

A customizable testimonial slider based on [swiperjs](https://swiperjs.com/). Enabling this widget will add a 
`Testimonials` post type within the Wordpress admin. This allows for easily editing testimonials by anyone with
admin access to your site (so your clients for example). The `Testimonials` post type also has a registered category 
such that you can display one category of testimonials in one slider and another in another slider.

To include the testimonial slider within your webpage, use the `widcol_testimonials_slider` shortcode. This shortcode has the 
following optional parameters:

- `id`: The id for the slider, the slider will get the CSS id of `swiper-container-$id`. Useful if you want to edit the 
  CSS of one single slider.
- `theme_color`: Primary theme color used for the slider (can also be changed with CSS).
- `secondary_theme_color`: Secondary theme color used for the slider (can also be changed with CSS).
- `arrow_enabled`: Whether to enable the arrows of the slider.
- `pagination_enabled`: Wheter to enable pagination of the slider.
- `slides_per_view`: Amount of slides to display per view. Will automatically default to 1 if the screen size gets too
small.
- `enable_star_rating`: Enable the star rating in the slider (can be set in the Testimonial post type).
- `category`: List of ints of the categories of testimonials to display in the slider (example: "1, 3").

Example slider:

```
[widcol_testimonials_slider theme_color="#2c4010" slides_per_view="3" secondary_theme_color="#7eb82e" category="2, 3"]
```

## Testimonial page

The same testimonials as previously discussed can also be displayed in a page format. Use the `widcol_testimonials_page`
shortcode. This shortcode has the following optional parameters:

- `id`: The id for the testimonial page, the page will get the CSS id of `testimonial-page-container-$id`. Useful if you want 
  to edit the CSS of one single testimonial page.
- `theme_color`: Primary theme color used for the page (can also be changed with CSS).
- `secondary_theme_color`: Secondary theme color used for the page (can also be changed with CSS).
- `text_color`: Text color used for the text in the page (can also be changed with CSS).
- `enable_star_rating`: Enable the star rating in the page (can be set in the Testimonial post type).
- `category`: List of ints of the categories of testimonials to display on the page (example: "1, 3").

Example page:

```
[widcol_testimonials_page enable_star_rating="false" secondary_theme_color="#BED6DC"]
```

## Image masonry gallery

A masonry image gallery shortcode is added to this plugin as well. The plugin uses 
[MacyJS](https://github.com/bigbite/macy.js) to create a masonry layout for images. The shortcode `[widcol_gallery]` can
be used to create an image gallery. Gallery images can be set in the administration dashboard of Wordpress. The meta
box used works in the same way as WooCommerce's product image gallery. Select and order your images and display your 
gallery with the shortcode. The shortcode has the following parameters:

- `id`: The id of the Gallery to use for the shortcode. This parameter is obligatory, without it the gallery will show a 
  warning message.

Example gallery:

```
[widcol_gallery_masonry id="44"]
```

## Image slider gallery

Alternatively, a slider is available to display your image galleries. The slider can be called wit the following
shortcode: `[widcol_gallery_slider]`. The shortcode has the following parameters:

- `id`: The id of the Gallery to use for the shortcode. This parameter is obligatory, without it the gallery will show a
  warning message.
- `theme_color`: Primary theme color used for the page (can also be changed with CSS).
- `arrow_enabled`: Whether to enable the arrows of the slider.
- `pagination_enabled`: Wheter to enable pagination of the slider.
- `slides_per_view`: Amount of slides to display per view. Will automatically default to 1 if the screen size gets too
  small.


## Badges

Badges can also be created (for example handy for product pages) with the `[widcol_badge`

- `id`: The id of the Gallery to use for the shortcode. This parameter is obligatory, without it the gallery will show a
  warning message.
- `badge_text`: Text to display on badge.
- `icon`: Fontawesome icon to display on badge (such as `fas fa-allergies`).
- `badge_color`: Background color of badge.
- `text_color`: Color of badge text.
- `popup_text`: Text to display on popup.
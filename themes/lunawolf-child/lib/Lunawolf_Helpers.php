<?php
class Lunawolf_Helpers {

  private static $instance;

  public function __construct() {
    add_filter( 'timber/context', [$this, 'add_to_context'] );
    add_action( 'admin_init', function () {

    });


    add_filter('timber/twig/functions', function ($functions) {
      $functions['get_share_box'] = [
        'callable' => [$this, 'get_share_box']
      ];
      $functions['get_permalink'] = [
        'callable' => [$this, 'get_permalink']
      ];
      $functions['get_your_post'] = [
        'callable' => [$this, 'get_your_post']
      ];
      $functions['get_your_posts'] = [
        'callable' => [$this, 'get_your_posts']
      ];

      return $functions;
    });

//    $this->add_image_sizes();
  }

  public static function instance() {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function get_share_box() {
    $url = esc_url(get_permalink());
    $title = urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8'));

    $social_networks = array(
//      'x' => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
//      'fb' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
//      'ln' => 'https://www.linkedin.com/shareArticle?url=' . $url . '&title=' . $title,
//      'ml' => 'mailto:?subject=' . $title . '&body=' . $url
    );

    $share_buttons = '<ul class="social">';

    $share_icons = [
//      'x' => get_stylesheet_directory_uri() . '/assets/public/img/social/x.png',
//      'fb' => get_stylesheet_directory_uri() . '/assets/public/img/social/facebook.png',
//      'ln' => get_stylesheet_directory_uri() . '/assets/public/img/social/linkedin.png',
//      'ml' => get_stylesheet_directory_uri() . '/assets/public/img/social/mail.png',
    ];

    foreach ($social_networks as $network => $share_url) {
      $img = '<img src="' . $share_icons[$network] . '" alt="' . $network . '" />';

      $share_buttons .= '<li><a href="' . $share_url . '" target="_blank" rel="noopener">' . $img . '</a></li>';
    }

    $share_buttons .= '</ul>';

    return $share_buttons;
  }

  public function add_to_context( $context ) {
    $header_settings             = get_field('header_settings', 'option');
    $footer_settings             = get_field('footer_settings', 'option');
    $social_media_settings       = get_field('social_media', 'option');

    $context['header_settings']  = $header_settings;
    $context['footer_settings']  = $footer_settings;
    $context['social_media']     = $social_media_settings;
    $context['menu']             = Timber::get_menu('Main Menu');
    $context['is_search']        = is_search();
    $context['homeUrl']          = home_url('/');

    return $context;
  }

  public function get_permalink($post = 0) {
    return get_permalink($post);
  }

  public function get_your_post($post_id) {
    $post = Timber::get_post($post_id);
    return $post;
  }

  public function get_your_posts($type) {
    $posts = Timber::get_posts([
      'post_type' => $type,
    ]);
  
    return $posts;
  }
}
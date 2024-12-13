<?php

require_once(LUNAWOLF_TEMPLATE_URI . '/lib/Lunawolf_Settings.php');
require_once(LUNAWOLF_TEMPLATE_URI . '/lib/Lunawolf_Helpers.php');

use Timber\Site;

/**
 * Class Lunawolf
 */
class Lunawolf extends Site {
  protected $settings;
  protected $helpers;
  protected $layout_count = 1;

  public function __construct() {
    $this->settings = Lunawolf_Settings::instance();
    $this->helpers = Lunawolf_Helpers::instance();

    if ( ! class_exists('ACF') ) :
      add_action( 'admin_notices', [$this, 'activate_acf_msg'] );
    endif;

    add_action( 'after_setup_theme', [$this, 'theme_supports'] );
    add_action( 'init', [$this, 'register_post_types'] );
    add_action( 'init', [$this, 'register_taxonomies'] );
//    add_action( 'init', [$this, 'register_acf_blocks'] );

    add_action( 'acf/input/admin_head', [$this, 'acf_input_admin_head'] );

    add_filter( 'timber/context', [$this, 'add_to_context'] );
    add_filter( 'timber/twig', [$this, 'add_to_twig'] );
    add_filter( 'timber/locations', [$this, 'timber_locations'] );
    add_filter( 'timber/twig/environment/options', [ $this, 'update_twig_environment_options' ] );
    add_filter( 'acfe/flexible/render/template', [$this, 'acfe_preview_override_timber'], 10, 4 );
    add_filter( 'acfe/flexible/enqueue', [$this, 'acfe_style_path_enqueue'], 10, 4 );

    parent::__construct();

    $this->load_components();
  }

  public function activate_acf_msg() { ?>
      <div class="notice notice-warning">
          <p><?= __( 'Activate ACF before proceeding', 'lunadwolf' ); ?></p>
      </div>
    <?php
  }

  /**
   * This is where you can register custom post types.
   */
  public function register_post_types() {

  }

  /**
   * This is where you can register custom taxonomies.
   */
  public function register_taxonomies() {

  }

  /**
   * This is where you can register custom taxonomies.
   */
  public function register_acf_blocks() {
    $dir = LUNAWOLF_TEMPLATE_URI . '/build/acf_blocks';
    $blocks = [];

    $dir_exists = file_exists($dir);

    if (!$dir_exists) return;

    $dir_items = scandir($dir);

    if (!$dir_items) return;

    foreach($dir_items as $item) {
      if (is_dir($dir . '/' . $item) && $item !== '.' && $item !== '..') {
        $blocks[] = $item;
      }
    }

    foreach ($blocks as $block) :
      $path = '/build/acf_blocks/' . $block;
      register_block_type( LUNAWOLF_TEMPLATE_URI . $path );
    endforeach;
  }

  /**
   * This is where you add some context
   *
   * @param string $context context['this'] Being the Twig's {{ this }}.
   */
  public function add_to_context( $context ) {
    $theme = new Timber\Theme('lunawolf-child');
    $context['site']         = $this;
    $context['menu']         = Timber::get_menu();
    $context['options']      = get_fields('options');
    $context['siteUrl']      = home_url('/');
    $context['postType']     = get_post_type();
    $context['loggedIn']     = is_user_logged_in();
    $context['isAdmin']      = current_user_can('edit_posts');
    $context['title']        = get_the_title();
    $context['permalink']    = get_permalink();
    $context['searchQuery']  = get_search_query();
    // {{ theme.link ~ '/... }} - retrieves child theme public url
    $context['theme']        = $theme;

    return $context;
  }

  public function theme_supports() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support(
      'html5',
      array(
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
      )
    );

    /*
     * Enable support for Post Formats.
     *
     * See: https://codex.wordpress.org/Post_Formats
     */
    add_theme_support(
      'post-formats',
      array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
      )
    );

    add_theme_support( 'menus' );

    add_theme_support('soil', [
      'clean-up',
//			'nav-walker', Not working with timber
//    'nice-search',
//    'disable-rest-api',
//    'disable-asset-versioning',
      'disable-trackbacks',
      'nice-search',
      'js-to-footer',
      'relative-urls',
    ]);

    remove_theme_support('block-templates');

    /**
     * Disable gutenberg style in header global-style
     */
    add_action( 'wp_enqueue_scripts', function () {
      wp_dequeue_style( 'global-styles' );
      wp_dequeue_style( 'wp-block-columns' );
      wp_dequeue_style( 'wp-block-column' );
      wp_dequeue_style( 'classic-theme-styles' );

      global $wp_query;
      wp_enqueue_style( 'lunawolf-style', get_stylesheet_uri() );

      $asset_config_file = LUNAWOLF_BUILD_PATH . '/theme/main.asset.php';
      $asset_config_version = '';

      if (file_exists($asset_config_file))
      {
        $asset_config = include $asset_config_file;

        $asset_config_version = $asset_config['version'];
        $asset_config_dependency = $asset_config['dependencies'];
      }
      $asset_config_dependency[] = 'jQuery';

      $jquery_url = LUNAWOLF_TEMPLATE_DIR_URI . '/assets/vendor/jquery.js';
      $style_url = LUNAWOLF_TEMPLATE_DIR_URI . '/build/theme/main.css';
      $script_url = LUNAWOLF_TEMPLATE_DIR_URI . '/build/theme/main.js';

      // Theme styles
      wp_enqueue_style('lunawolf-main-style', $style_url, [], $asset_config_version);
      // Theme JS
      wp_register_script( 'jQuery', $jquery_url, [], $asset_config_version, true);
      wp_register_script( 'lunawolf-script-min', $script_url, $asset_config_dependency, $asset_config_version, true);
      wp_localize_script( 'lunawolf-script-min', 'wp_params', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
        'posts' => json_encode( $wp_query->query_vars ),
        'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
        'max_page' => $wp_query->max_num_pages,
        'found_posts' => $wp_query->found_posts
      ) );

      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'lunawolf-script-min' );
    }, 100 );

    add_action( 'enqueue_block_editor_assets', function () {
      $asset_config_file = LUNAWOLF_BUILD_PATH . '/blocks/blocks.asset.php';
      $asset_config_version = '';

      if (file_exists($asset_config_file))
      {
        $asset_config = include $asset_config_file;

        $asset_config_version = $asset_config['version'];
      }

      $asset_config_dependency = [
        'wp-blocks',
        'wp-block-editor',
        // 'wp-element',
        'wp-server-side-render',
        'wp-components',
      ];

      $script_url = LUNAWOLF_TEMPLATE_DIR_URI . '/build/blocks/blocks.js';

      wp_enqueue_script( 'lunawolf-blocks-script-min', $script_url, $asset_config_dependency, $asset_config_version, true);
    }, 100 );
  }

  /**
   * his would return 'foo bar!'.
   *
   * @param string $text being 'foo', then returned 'foo bar!'.
   */
  public function myfoo( $text ) {
    $text .= ' bar!';
    return $text;
  }

  public function timber_locations($locs) {
    return $locs;
  }

  /**
   * This is where you can add your own functions to twig.
   *
   * @param Twig\Environment $twig get extension.
   */
  public function add_to_twig( $twig ) {
    /**
     * Required when you want to use Twig’s template_from_string.
     * @link https://twig.symfony.com/doc/3.x/functions/template_from_string.html
     */
    // $twig->addExtension( new Twig\Extension\StringLoaderExtension() );

    $twig->addFilter( new Twig\TwigFilter( 'myfoo', [ $this, 'myfoo' ] ) );
    $twig->addFilter( new Twig\TwigFilter( 'get_all_players', [ $this->helpers, 'get_all_players' ] ) );

    return $twig;
  }

  public function acf_input_admin_head() {
    $asset_config_file = LUNAWOLF_TEMPLATE_URI . '/source/build/acf/acf.asset.php';
    $asset_config_version = '';

    if (file_exists($asset_config_file))
    {
      $asset_config = include $asset_config_file;

      $asset_config_version = $asset_config['version'];
    }

    $style_url = LUNAWOLF_BUILD_URI . '/acf/acf.css';
    ?>
      <link rel="stylesheet" href="<?= $style_url ?>?v=<?= $asset_config_version ?>">

    <?php
  }

  /**
   * Updates Twig environment options.
   *
   * @link https://twig.symfony.com/doc/245.x/api.html#environment-options
   *
   * \@param array $options An array of environment options.
   *
   * @return array
   */
  function update_twig_environment_options( $options ) {
// $options['autoescape'] = true;

    return $options;
  }

  private function load_components() {
    $setup_files = [
      'acf/functions',
      'acf/options'
    ];

    foreach($setup_files as $file) {
      $file = get_stylesheet_directory() . "/lib/{$file}.php";

      require_once($file);
    }
  }

  public function acfe_style_path_enqueue() {
    if ( ! is_admin() ) return;

    $asset_config_file = LUNAWOLF_BUILD_PATH . '/blocks/blocks.asset.php';
    $asset_config_version = '';

    if (file_exists($asset_config_file))
    {
      $asset_config = include $asset_config_file;

      $asset_config_version = $asset_config['version'];
    }

    wp_enqueue_style('acfe-blocks-styles', LUNAWOLF_BUILD_URI . '/blocks/blocks.css', [], $asset_config_version);
  }

  public function acfe_preview_override_timber($file, $field, $layout, $is_preview) {
    $context = Timber::context();
    $name = $layout['name'] ?? '_empty';

    $row = get_row(true);
    $context['block'] = get_row(true);
    $context['is_preview'] = $is_preview;
    // Settings configuration
    $settings = $row['layout_settings'] ?? null;
    $context['settings'] = $this->settings->settings($settings, $this->layout_count);

    Timber::render(sprintf('views/_blocks/%s.twig', $name), $context);

    // Hackish way to calculate layout count
    $this->layout_count++;
  }
}
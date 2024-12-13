<?php
class Lunawolf_Settings {
  private static $instance;
  protected $layout_count = 1;

  public function __construct() {
    add_filter( 'acf/fields/wysiwyg/toolbars', [$this, '_custom_wysiwyg_toolbars'], 10, 4 );
    add_filter( 'acfe/flexible/render/template', [$this, 'acfe_preview_override_timber'], 10, 4 );
    add_filter( 'acfe/flexible/enqueue', [$this, 'acfe_style_path_enqueue'], 10, 4 );
  }

  public static function instance() {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function _custom_wysiwyg_toolbars($toolbars) {
    $toolbars['Only Links'] = array();
    $toolbars['Only Bold'] = array();
    $toolbars['Only Bold & Links'] = array();
    $toolbars['Simple Text'] = array();

    $toolbars['Only Links'][1] = array('link', 'unlink' );
    $toolbars['Only Bold'][1] = array('bold');
    $toolbars['Only Bold & Links'][1] = array('bold', 'link', 'unlink');
    $toolbars['Simple Text'][1] = array('formatselect', 'bold', 'italic', 'link', 'unlink', 'bullist', 'numlist' );

    return $toolbars;
  }

  public function acfe_preview_override_timber($file, $field, $layout, $is_preview) {
    $context = Timber::context();
    $name = $layout['name'] ?? '_empty';

    $row = get_row(true);
    $context['block'] = get_row(true);
    $context['is_preview'] = $is_preview;
    // Settings configuration
    $settings = $row['layout_settings'] ?? null;
    $context['settings'] = $this->settings($settings, $this->layout_count);

    Timber::render(sprintf('views/__flexible_blocks/%s.twig', $name), $context);

    // Hackish way to calculate layout count
    $this->layout_count++;
  }

  public function acfe_style_path_enqueue() {
    if ( ! is_admin() || ! class_exists('ACFE') ) return;

    $asset_config_file = LUNAWOLF_BUILD_PATH . '/blocks/blocks.asset.php';
    $asset_config_version = '';

    if (file_exists($asset_config_file))
    {
      $asset_config = include $asset_config_file;

      $asset_config_version = $asset_config['version'];
    }

    wp_enqueue_style('acfe-blocks-styles', LUNAWOLF_BUILD_URI . '/blocks/blocks.css', [], $asset_config_version);
  }

  private function _block_style_settings($settings): string
  {
    $property_names = [
      'spacing_top' => 'margin-top',
      'spacing_bot' => 'margin-bottom',
      'padding_top' => 'padding-top',
      'padding_bot' => 'padding-bottom',
    ];

    $styles = '';

    foreach ($property_names as $key => $value) {
      if (isset($settings[$key]) && is_numeric($settings[$key]))
        $styles .= '#' . $settings['block_id'] . '{' . $value . ':' . $settings[$key] . 'px;}';
    }

    return $styles;
  }

  /**
   * Settings for ACFE Flexible Content options field group.
   * @param $settings
   * @param $count
   * @return array|string[]
   */
  public function settings($settings, $count): array
  {
    if (!$settings) return [
      'block_id' => 'block-' . $count,
      'styles' => '',
      'dark_mode' => false,
      'full_width_content' => false,
    ];

    $block_id  = isset($settings['block_id']) && $settings['block_id'] ? $settings['block_id'] : 'block-' . $count;
    //$dark_mode = $settings['dark_mode'];
    //$full_width_content = $settings['full_width_content'];
    $settings['block_id'] = $block_id;

    $styles = $this->_block_style_settings($settings);

    return [
      'block_id' => $block_id,
      'styles' => $styles ? '<style>' . $styles . '</style>' : '',
      //'dark_mode' => $dark_mode,
      //'full_width_content' => $full_width_content,
    ];
  }
}
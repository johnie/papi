<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Page Type Builder - Property Image
 *
 * @package PageTypeBuilder
 * @version 1.0.0
 */

class PropertyImage extends PTB_Property {

  /**
   * Generate the HTML for the property.
   *
   * @since 1.0.0
   */

  public function html () {
    // Property options.
    $options = $this->get_options();

    // CSS classes.
    $css_classes = $this->css_classes();

    // Property settings.
    $settings = $this->get_settings(array(
      'gallery' => false
    ));

    $is_gallery = $settings->gallery;

    $images = array();

    // If it's a gallery, we need to load all images.
    if ($is_gallery) {
      $values = array();
      $slug = '';

      if (isset($options->value)) {
        if (is_array($options->value)) {
          $values = $options->value;
        } else {
          $values = array($options->value);
        }
      }

      // We need a html array!
      if (strpos($slug, '[]') === false) {
        $slug .= $options->slug . '[]';
      }

      foreach ($values as $value) {
        $image = (object)array(
          'id'        => 0,
          'value'     => '',
          'css_class' => '',
          'slug'      => $slug
        );

        if (is_numeric($value)) {
          $value = $this->convert($value);
        }

        if (is_object($value) && isset($value->url)) {
          $image->value = $value->url;
          $image->id = $value->id;
          $image->css_class = ' pr-image-item ';
          $images[] = $image;
        }
      }

      // Add one image if no exists.
      if (empty($images)) {
        $images = array(
          (object)array(
            'id'        => 0,
            'value'     => '',
            'css_class' => '',
            'slug'      => $options->slug
          )
        );
      }
    } else {
      // If it's not a gallery we load the single image.
      $image = (object)array(
        'id'        => '',
        'value'     => '',
        'css_class' => '',
        'slug'      => $options->slug
      );

      // If it's not converted convert it.
      if (isset($options->value) && is_numeric($options->value)) {
        $options->value = $this->convert($options->value);
      }

      // Set image if it exists.
      if (isset($options->value) && is_object($options->value)) {
        $image->value = $options->value->url;
        $image->id = $options->value->id;
        $image->css_class = ' pr-image-item ';
      }

      $images[] = $image;
    }

    if ($is_gallery) {
      $images = array_filter($images, function ($image) {
        return !!$image->id;
      });
    }

    $template_slug = $options->slug;

    if ($is_gallery) {
      $template_slug .= '[]';
    }

    $is_gallery_attr = ($is_gallery ? 'true' : 'false');

    $html = <<< EOF
      <div class="ptb-property-image">
        <div class="pr-images">
          <ul class="pr-template hidden">
            <li class="{$css_classes}" data-ptb-gallery="{$is_gallery_attr}">
              <img />
              <input type="hidden" name="{$template_slug}" id="{$template_slug}" />
              <p class="pr-remove-image hidden">
                <a href="#">&times;</a>
              </p>
            </li>
          </ul>
          <ul class="pr-image-items">
EOF;

      foreach ($images as $image) {
        $css = $css_classes . $image->css_class;
        $html .= "<li class=\"$css\" data-ptb-gallery=\"$is_gallery_attr\">";
        $html .= '<p class="pr-remove-image hidden">
            <a href="#">&times;</a>
          </p>';
        $html .= "
            <img src=\"$image->value\" />
            <input type=\"hidden\" value=\"$image->id\" name=\"$image->slug\" id=\"$image->slug\" />
          </li>
          ";
        }

    if ($is_gallery) {
      $html .= <<< EOF
        <li class="pr-add-new {$css_classes}">
          <p>
            <a href="#">Set image</a>
          </p>
        </li>
EOF;
    } else {
      $html .= '<li style="visibility:hidden"></li>';
    }

    $html .= <<< EOF
        </ul>
      </div>
      <div class="clear"></div>
    </div>
EOF;

  echo $html;
  }

  /**
   * Convert the value of the property before we output it to the application.
   *
   * @param mixed $value
   * @since 1.0.0
   *
   * @return object|string
   */

  public function convert ($value) {
    if (is_numeric($value)) {
      $meta = wp_get_attachment_metadata($value);
      if (isset($meta) && !empty($meta)) {
        $mine = array(
          'is_image' => true,
          'url'      => wp_get_attachment_url($value),
          'id'       => intval($value)
        );
        return (object)array_merge($meta, $mine);
      } else {
        return $value;
      }
    } else if (is_array($value)) {
      foreach ($value as $k => $v) {
         $value[$k] = $this->convert($v);
      }
      return $value;
    } else {
      return $value;
    }
  }

  /**
   * Render the final html that is displayed in the table.
   *
   * @since 1.0.0
   *
   * @return string
   */

  public function render () {
    if ($this->get_options()->table): ?>
      <tr>
        <td>
          <?php $this->label(); ?>
          <?php $this->html(); ?>
        </td>
      </tr>
    <?php
      $this->helptext(false);
    else:
      $this->label();
      $this->html();
      $this->helptext(false);
    endif;
  }

}
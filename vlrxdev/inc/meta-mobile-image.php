<?php
/**
 * Изображения для десктопа и мобильных (отдельные поля загрузки).
 */

defined( 'ABSPATH' ) || exit;

$vlrxdev_responsive_image_post_types = array( 'vlrxdev_service', 'vlrxdev_team_member', 'vlrxdev_project' );

add_action( 'add_meta_boxes', 'vlrxdev_responsive_image_meta_boxes' );

function vlrxdev_responsive_image_meta_boxes() {
  global $vlrxdev_responsive_image_post_types;
  foreach ( $vlrxdev_responsive_image_post_types as $post_type ) {
    add_meta_box(
      'vlrxdev_desktop_image',
      'Изображение для десктопа',
      'vlrxdev_desktop_image_callback',
      $post_type,
      'side',
      'default'
    );
    add_meta_box(
      'vlrxdev_mobile_image',
      'Изображение для мобильных',
      'vlrxdev_mobile_image_callback',
      $post_type,
      'side',
      'default'
    );
  }
}

function vlrxdev_desktop_image_callback( $post ) {
  wp_nonce_field( 'vlrxdev_save_desktop_image', 'vlrxdev_desktop_image_nonce' );
  $attachment_id = (int) get_post_meta( $post->ID, '_vlrxdev_desktop_image', true );
  $url           = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'thumbnail' ) : '';
  ?>
  <p class="description">Показывается на широких экранах. Если не задано — можно использовать миниатюру записи.</p>
  <input type="hidden" id="vlrxdev_desktop_image_id" name="vlrxdev_desktop_image_id" value="<?php echo $attachment_id ? (int) $attachment_id : ''; ?>">
  <p>
    <button type="button" class="button vlrxdev-upload-desktop-image"><?php echo $attachment_id ? 'Заменить изображение' : 'Выбрать изображение'; ?></button>
    <?php if ( $attachment_id ) : ?>
      <button type="button" class="button vlrxdev-remove-desktop-image">Удалить</button>
    <?php endif; ?>
  </p>
  <p id="vlrxdev_desktop_image_preview" style="<?php echo $url ? '' : 'display:none'; ?>">
    <img src="<?php echo $url ? esc_url( $url ) : ''; ?>" alt="" style="max-width: 100%; height: auto;">
  </p>
  <?php
}

function vlrxdev_mobile_image_callback( $post ) {
  wp_nonce_field( 'vlrxdev_save_mobile_image', 'vlrxdev_mobile_image_nonce' );
  $attachment_id = (int) get_post_meta( $post->ID, '_vlrxdev_mobile_image', true );
  $url           = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'thumbnail' ) : '';
  ?>
  <p class="description">Показывается на узких экранах. По желанию — отдельное изображение под мобильные.</p>
  <input type="hidden" id="vlrxdev_mobile_image_id" name="vlrxdev_mobile_image_id" value="<?php echo $attachment_id ? (int) $attachment_id : ''; ?>">
  <p>
    <button type="button" class="button vlrxdev-upload-mobile-image"><?php echo $attachment_id ? 'Заменить изображение' : 'Выбрать изображение'; ?></button>
    <?php if ( $attachment_id ) : ?>
      <button type="button" class="button vlrxdev-remove-mobile-image">Удалить</button>
    <?php endif; ?>
  </p>
  <p id="vlrxdev_mobile_image_preview" style="<?php echo $url ? '' : 'display:none'; ?>">
    <img src="<?php echo $url ? esc_url( $url ) : ''; ?>" alt="" style="max-width: 100%; height: auto;">
  </p>
  <?php
}

add_action( 'save_post', 'vlrxdev_save_responsive_images', 10, 2 );

function vlrxdev_save_responsive_images( $post_id, $post ) {
  global $vlrxdev_responsive_image_post_types;
  if ( ! in_array( $post->post_type, $vlrxdev_responsive_image_post_types, true ) ) {
    return;
  }
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }
  if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return;
  }

  if ( isset( $_POST['vlrxdev_desktop_image_nonce'] ) && wp_verify_nonce( $_POST['vlrxdev_desktop_image_nonce'], 'vlrxdev_save_desktop_image' ) ) {
    $id = isset( $_POST['vlrxdev_desktop_image_id'] ) ? (int) $_POST['vlrxdev_desktop_image_id'] : 0;
    update_post_meta( $post_id, '_vlrxdev_desktop_image', $id );
  }

  if ( isset( $_POST['vlrxdev_mobile_image_nonce'] ) && wp_verify_nonce( $_POST['vlrxdev_mobile_image_nonce'], 'vlrxdev_save_mobile_image' ) ) {
    $id = isset( $_POST['vlrxdev_mobile_image_id'] ) ? (int) $_POST['vlrxdev_mobile_image_id'] : 0;
    update_post_meta( $post_id, '_vlrxdev_mobile_image', $id );
  }
}

add_action( 'admin_enqueue_scripts', 'vlrxdev_responsive_image_admin_scripts' );

function vlrxdev_responsive_image_admin_scripts( $hook ) {
  global $vlrxdev_responsive_image_post_types;
  if ( $hook !== 'post.php' && $hook !== 'post-new.php' ) {
    return;
  }
  $screen = get_current_screen();
  if ( ! $screen || ! in_array( $screen->post_type, $vlrxdev_responsive_image_post_types, true ) ) {
    return;
  }
  wp_enqueue_media();
  wp_add_inline_script( 'jquery', "
    (function($) {
      function vlrxdevMediaUpload(buttonClass, inputId, previewId, uploadText, replaceText, removeClass) {
        var frame;
        $(document).on('click', buttonClass, function(e) {
          e.preventDefault();
          var input = $('#' + inputId);
          var preview = $('#' + previewId);
          if (frame) frame.open();
          else {
            frame = wp.media({ library: { type: 'image' }, multiple: false });
            frame.on('select', function() {
              var att = frame.state().get('selection').first().toJSON();
              input.val(att.id);
              preview.find('img').attr('src', att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url).end().show();
              $(buttonClass).text(replaceText);
              if (!$(removeClass).length) $('<button type=\"button\" class=\"button ' + removeClass.slice(1) + '\">Удалить</button>').insertAfter($(buttonClass));
            });
            frame.open();
          }
        });
        $(document).on('click', removeClass, function(e) {
          e.preventDefault();
          $('#' + inputId).val('');
          $('#' + previewId).hide().find('img').attr('src', '');
          $(buttonClass).text(uploadText);
          $(this).remove();
        });
      }
      $(function() {
        vlrxdevMediaUpload('.vlrxdev-upload-desktop-image', 'vlrxdev_desktop_image_id', 'vlrxdev_desktop_image_preview', 'Выбрать изображение', 'Заменить изображение', '.vlrxdev-remove-desktop-image');
        vlrxdevMediaUpload('.vlrxdev-upload-mobile-image', 'vlrxdev_mobile_image_id', 'vlrxdev_mobile_image_preview', 'Выбрать изображение', 'Заменить изображение', '.vlrxdev-remove-mobile-image');
      });
    })(jQuery);
  " );
}

<?php
/**
 * Мета для участников команды: роль, никнейм, соц. сети.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', 'vlrxdev_team_meta_boxes' );

function vlrxdev_team_meta_boxes() {
  add_meta_box(
    'vlrxdev_team_info',
    'Роль и никнейм',
    'vlrxdev_team_info_callback',
    'vlrxdev_team_member',
    'normal',
    'default'
  );
  add_meta_box(
    'vlrxdev_team_socials',
    'Соц. сети',
    'vlrxdev_team_socials_callback',
    'vlrxdev_team_member',
    'normal',
    'default'
  );
}

function vlrxdev_team_info_callback( $post ) {
  wp_nonce_field( 'vlrxdev_save_team_info', 'vlrxdev_team_info_nonce' );
  $role     = get_post_meta( $post->ID, '_vlrxdev_team_role', true );
  $nickname = get_post_meta( $post->ID, '_vlrxdev_team_nickname', true );
  ?>
  <p>
    <label for="vlrxdev_team_role"><strong>Роль</strong></label><br>
    <input type="text" id="vlrxdev_team_role" name="vlrxdev_team_role" value="<?php echo esc_attr( $role ); ?>" class="widefat" placeholder="например: Дизайнер">
  </p>
  <p>
    <label for="vlrxdev_team_nickname"><strong>Никнейм</strong></label><br>
    <input type="text" id="vlrxdev_team_nickname" name="vlrxdev_team_nickname" value="<?php echo esc_attr( $nickname ); ?>" class="widefat" placeholder="@nickname">
  </p>
  <p class="description">Контент записи — это биография/описание. Изображение — миниатюра записи.</p>
  <?php
}

function vlrxdev_team_socials_callback( $post ) {
  wp_nonce_field( 'vlrxdev_save_team_socials', 'vlrxdev_team_socials_nonce' );
  $socials = get_post_meta( $post->ID, '_vlrxdev_socials', true );
  if ( ! is_array( $socials ) ) {
    $socials = array();
  }
  ?>
  <p class="description">Добавьте ссылки на соц. сети. Иконка — класс (dashicons dashicons-vk) или URL картинки.</p>
  <div id="vlrxdev-socials-wrap">
    <?php
    foreach ( $socials as $i => $item ) {
      $url  = isset( $item['url'] ) ? $item['url'] : '';
      $icon = isset( $item['icon'] ) ? $item['icon'] : '';
      ?>
      <p class="vlrxdev-social-row">
        <input type="url" name="vlrxdev_socials[<?php echo (int) $i; ?>][url]" value="<?php echo esc_attr( $url ); ?>" class="widefat" placeholder="URL" style="margin-bottom: 4px;">
        <input type="text" name="vlrxdev_socials[<?php echo (int) $i; ?>][icon]" value="<?php echo esc_attr( $icon ); ?>" class="widefat" placeholder="Иконка (класс или URL)">
      </p>
      <?php
    }
    ?>
  </div>
  <p><button type="button" class="button" id="vlrxdev-add-social">Добавить соц. сеть</button></p>
  <script>
  (function() {
    var wrap = document.getElementById('vlrxdev-socials-wrap');
    var btn = document.getElementById('vlrxdev-add-social');
    if (!wrap || !btn) return;
    var index = wrap.querySelectorAll('.vlrxdev-social-row').length;
    btn.addEventListener('click', function() {
      var p = document.createElement('p');
      p.className = 'vlrxdev-social-row';
      p.innerHTML = '<input type="url" name="vlrxdev_socials[' + index + '][url]" value="" class="widefat" placeholder="URL" style="margin-bottom: 4px;">' +
        '<input type="text" name="vlrxdev_socials[' + index + '][icon]" value="" class="widefat" placeholder="Иконка (класс или URL)">';
      wrap.appendChild(p);
      index++;
    });
  })();
  </script>
  <?php
}

add_action( 'save_post_vlrxdev_team_member', 'vlrxdev_save_team_meta' );

function vlrxdev_save_team_meta( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }
  if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return;
  }

  if ( isset( $_POST['vlrxdev_team_info_nonce'] ) && wp_verify_nonce( $_POST['vlrxdev_team_info_nonce'], 'vlrxdev_save_team_info' ) ) {
    if ( isset( $_POST['vlrxdev_team_role'] ) ) {
      update_post_meta( $post_id, '_vlrxdev_team_role', sanitize_text_field( $_POST['vlrxdev_team_role'] ) );
    }
    if ( isset( $_POST['vlrxdev_team_nickname'] ) ) {
      update_post_meta( $post_id, '_vlrxdev_team_nickname', sanitize_text_field( $_POST['vlrxdev_team_nickname'] ) );
    }
  }

  if ( isset( $_POST['vlrxdev_team_socials_nonce'] ) && wp_verify_nonce( $_POST['vlrxdev_team_socials_nonce'], 'vlrxdev_save_team_socials' ) && isset( $_POST['vlrxdev_socials'] ) && is_array( $_POST['vlrxdev_socials'] ) ) {
    $socials = array();
    foreach ( $_POST['vlrxdev_socials'] as $row ) {
      $url  = isset( $row['url'] ) ? esc_url_raw( $row['url'] ) : '';
      $icon = isset( $row['icon'] ) ? sanitize_text_field( $row['icon'] ) : '';
      if ( $url !== '' || $icon !== '' ) {
        $socials[] = array( 'url' => $url, 'icon' => $icon );
      }
    }
    update_post_meta( $post_id, '_vlrxdev_socials', $socials );
  }
}

<?php
/**
 * Настройки футера: контакты (телефон, почта, адрес) и второй блок (соц. сети, полезные ссылки).
 * У каждого поля опциональная иконка (класс или URL картинки).
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'vlrxdev_footer_options_menu' );

function vlrxdev_footer_options_menu() {
  add_theme_page(
    'Футер',
    'Футер',
    'edit_theme_options',
    'vlrxdev-footer',
    'vlrxdev_footer_options_page'
  );
}

function vlrxdev_footer_defaults() {
  return array(
    'phone'       => '',
    'phone_icon'  => '',
    'email'       => '',
    'email_icon'  => '',
    'address'     => '',
    'address_icon' => '',
    'socials'     => array(),
    'links'       => array(),
  );
}

function vlrxdev_footer_options_page() {
  if ( ! current_user_can( 'edit_theme_options' ) ) {
    return;
  }
  $opt = get_option( 'vlrxdev_footer', vlrxdev_footer_defaults() );
  if ( ! is_array( $opt ) ) {
    $opt = vlrxdev_footer_defaults();
  }
  $opt = array_merge( vlrxdev_footer_defaults(), $opt );
  if ( isset( $_POST['vlrxdev_footer_nonce'] ) && wp_verify_nonce( $_POST['vlrxdev_footer_nonce'], 'vlrxdev_save_footer' ) ) {
    $opt['phone']        = isset( $_POST['vlrxdev_footer_phone'] ) ? sanitize_text_field( $_POST['vlrxdev_footer_phone'] ) : '';
    $opt['phone_icon']   = isset( $_POST['vlrxdev_footer_phone_icon'] ) ? sanitize_text_field( $_POST['vlrxdev_footer_phone_icon'] ) : '';
    $opt['email']        = isset( $_POST['vlrxdev_footer_email'] ) ? sanitize_email( $_POST['vlrxdev_footer_email'] ) : '';
    $opt['email_icon']  = isset( $_POST['vlrxdev_footer_email_icon'] ) ? sanitize_text_field( $_POST['vlrxdev_footer_email_icon'] ) : '';
    $opt['address']     = isset( $_POST['vlrxdev_footer_address'] ) ? sanitize_textarea_field( $_POST['vlrxdev_footer_address'] ) : '';
    $opt['address_icon'] = isset( $_POST['vlrxdev_footer_address_icon'] ) ? sanitize_text_field( $_POST['vlrxdev_footer_address_icon'] ) : '';
    $opt['socials']     = array();
    if ( ! empty( $_POST['vlrxdev_footer_socials'] ) && is_array( $_POST['vlrxdev_footer_socials'] ) ) {
      foreach ( $_POST['vlrxdev_footer_socials'] as $row ) {
        $url  = isset( $row['url'] ) ? esc_url_raw( $row['url'] ) : '';
        $icon = isset( $row['icon'] ) ? sanitize_text_field( $row['icon'] ) : '';
        if ( $url !== '' || $icon !== '' ) {
          $opt['socials'][] = array( 'url' => $url, 'icon' => $icon );
        }
      }
    }
    $opt['links'] = array();
    if ( ! empty( $_POST['vlrxdev_footer_links'] ) && is_array( $_POST['vlrxdev_footer_links'] ) ) {
      foreach ( $_POST['vlrxdev_footer_links'] as $row ) {
        $url   = isset( $row['url'] ) ? esc_url_raw( $row['url'] ) : '';
        $label = isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '';
        $icon  = isset( $row['icon'] ) ? sanitize_text_field( $row['icon'] ) : '';
        if ( $url !== '' || $label !== '' || $icon !== '' ) {
          $opt['links'][] = array( 'url' => $url, 'label' => $label, 'icon' => $icon );
        }
      }
    }
    update_option( 'vlrxdev_footer', $opt );
    echo '<div class="notice notice-success"><p>Настройки сохранены.</p></div>';
  }
  ?>
  <div class="wrap">
    <h1>Настройки футера</h1>
    <form method="post" action="">
      <?php wp_nonce_field( 'vlrxdev_save_footer', 'vlrxdev_footer_nonce' ); ?>

      <h2>Блок 1: Контакты</h2>
      <p class="description">Все поля опциональны. Иконка — класс (например dashicons dashicons-phone) или URL картинки.</p>
      <table class="form-table">
        <tr>
          <th><label for="vlrxdev_footer_phone">Телефон</label></th>
          <td>
            <input type="text" id="vlrxdev_footer_phone" name="vlrxdev_footer_phone" value="<?php echo esc_attr( $opt['phone'] ); ?>" class="regular-text" placeholder="+7 900 123-45-67">
            <input type="text" name="vlrxdev_footer_phone_icon" value="<?php echo esc_attr( $opt['phone_icon'] ); ?>" class="regular-text" placeholder="Иконка (класс или URL)">
          </td>
        </tr>
        <tr>
          <th><label for="vlrxdev_footer_email">Почта</label></th>
          <td>
            <input type="email" id="vlrxdev_footer_email" name="vlrxdev_footer_email" value="<?php echo esc_attr( $opt['email'] ); ?>" class="regular-text" placeholder="hello@example.com">
            <input type="text" name="vlrxdev_footer_email_icon" value="<?php echo esc_attr( $opt['email_icon'] ); ?>" class="regular-text" placeholder="Иконка (класс или URL)">
          </td>
        </tr>
        <tr>
          <th><label for="vlrxdev_footer_address">Адрес</label></th>
          <td>
            <textarea id="vlrxdev_footer_address" name="vlrxdev_footer_address" rows="2" class="large-text" placeholder="г. Город, ул. Улица, 1"><?php echo esc_textarea( $opt['address'] ); ?></textarea>
            <input type="text" name="vlrxdev_footer_address_icon" value="<?php echo esc_attr( $opt['address_icon'] ); ?>" class="regular-text" placeholder="Иконка (класс или URL)" style="margin-top: 6px;">
          </td>
        </tr>
      </table>

      <h2>Блок 2: Соц. сети</h2>
      <div id="vlrxdev-footer-socials">
        <?php foreach ( $opt['socials'] as $i => $s ) : ?>
          <p class="vlrxdev-footer-row">
            <input type="url" name="vlrxdev_footer_socials[<?php echo (int) $i; ?>][url]" value="<?php echo esc_attr( isset( $s['url'] ) ? $s['url'] : '' ); ?>" class="regular-text" placeholder="URL">
            <input type="text" name="vlrxdev_footer_socials[<?php echo (int) $i; ?>][icon]" value="<?php echo esc_attr( isset( $s['icon'] ) ? $s['icon'] : '' ); ?>" class="regular-text" placeholder="Иконка">
          </p>
        <?php endforeach; ?>
      </div>
      <p><button type="button" class="button" id="vlrxdev-add-social">Добавить соц. сеть</button></p>

      <h2>Блок 2: Полезные ссылки</h2>
      <div id="vlrxdev-footer-links">
        <?php foreach ( $opt['links'] as $i => $l ) : ?>
          <p class="vlrxdev-footer-row">
            <input type="url" name="vlrxdev_footer_links[<?php echo (int) $i; ?>][url]" value="<?php echo esc_attr( isset( $l['url'] ) ? $l['url'] : '' ); ?>" class="regular-text" placeholder="URL">
            <input type="text" name="vlrxdev_footer_links[<?php echo (int) $i; ?>][label]" value="<?php echo esc_attr( isset( $l['label'] ) ? $l['label'] : '' ); ?>" class="regular-text" placeholder="Текст ссылки">
            <input type="text" name="vlrxdev_footer_links[<?php echo (int) $i; ?>][icon]" value="<?php echo esc_attr( isset( $l['icon'] ) ? $l['icon'] : '' ); ?>" class="regular-text" placeholder="Иконка">
          </p>
        <?php endforeach; ?>
      </div>
      <p><button type="button" class="button" id="vlrxdev-add-link">Добавить ссылку</button></p>

      <p class="submit">
        <input type="submit" class="button button-primary" value="Сохранить">
      </p>
    </form>
  </div>
  <script>
  (function() {
    var socialIndex = document.querySelectorAll('#vlrxdev-footer-socials .vlrxdev-footer-row').length;
    var linkIndex = document.querySelectorAll('#vlrxdev-footer-links .vlrxdev-footer-row').length;
    document.getElementById('vlrxdev-add-social').addEventListener('click', function() {
      var p = document.createElement('p');
      p.className = 'vlrxdev-footer-row';
      p.innerHTML = '<input type="url" name="vlrxdev_footer_socials[' + socialIndex + '][url]" value="" class="regular-text" placeholder="URL"> ' +
        '<input type="text" name="vlrxdev_footer_socials[' + socialIndex + '][icon]" value="" class="regular-text" placeholder="Иконка">';
      document.getElementById('vlrxdev-footer-socials').appendChild(p);
      socialIndex++;
    });
    document.getElementById('vlrxdev-add-link').addEventListener('click', function() {
      var p = document.createElement('p');
      p.className = 'vlrxdev-footer-row';
      p.innerHTML = '<input type="url" name="vlrxdev_footer_links[' + linkIndex + '][url]" value="" class="regular-text" placeholder="URL"> ' +
        '<input type="text" name="vlrxdev_footer_links[' + linkIndex + '][label]" value="" class="regular-text" placeholder="Текст ссылки"> ' +
        '<input type="text" name="vlrxdev_footer_links[' + linkIndex + '][icon]" value="" class="regular-text" placeholder="Иконка">';
      document.getElementById('vlrxdev-footer-links').appendChild(p);
      linkIndex++;
    });
  })();
  </script>
  <?php
}

/**
 * Вывод иконки из настроек (класс или URL).
 */
function vlrxdev_footer_icon( $icon ) {
  if ( empty( $icon ) ) {
    return '';
  }
  if ( strpos( $icon, 'http' ) === 0 || strpos( $icon, '//' ) === 0 ) {
    return '<img src="' . esc_url( $icon ) . '" alt="" class="site-footer__icon site-footer__icon--img" width="24" height="24">';
  }
  return '<span class="site-footer__icon ' . esc_attr( $icon ) . '" aria-hidden="true"></span>';
}

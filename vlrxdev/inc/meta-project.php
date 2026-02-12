<?php
/**
 * Мета-поле для проектов: ссылка (опционально).
 */

defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', 'vlrxdev_project_link_meta_box' );

function vlrxdev_project_link_meta_box() {
  add_meta_box(
    'vlrxdev_project_link',
    'Ссылка на проект',
    'vlrxdev_project_link_callback',
    'vlrxdev_project',
    'side',
    'default'
  );
}

function vlrxdev_project_link_callback( $post ) {
  wp_nonce_field( 'vlrxdev_save_project_link', 'vlrxdev_project_link_nonce' );
  $link = get_post_meta( $post->ID, '_vlrxdev_project_link', true );
  ?>
  <p>
    <label for="vlrxdev_project_link">URL</label><br>
    <input type="url" id="vlrxdev_project_link" name="vlrxdev_project_link" value="<?php echo esc_attr( $link ); ?>" class="widefat" placeholder="https://...">
  </p>
  <?php
}

add_action( 'save_post_vlrxdev_project', 'vlrxdev_save_project_link' );

function vlrxdev_save_project_link( $post_id ) {
  if ( ! isset( $_POST['vlrxdev_project_link_nonce'] ) ||
       ! wp_verify_nonce( $_POST['vlrxdev_project_link_nonce'], 'vlrxdev_save_project_link' ) ) {
    return;
  }
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }
  if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return;
  }
  if ( isset( $_POST['vlrxdev_project_link'] ) ) {
    update_post_meta( $post_id, '_vlrxdev_project_link', esc_url_raw( $_POST['vlrxdev_project_link'] ) );
  }
}

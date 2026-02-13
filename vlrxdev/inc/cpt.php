<?php
/**
 * Типы записей: Услуги, Проекты.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'vlrxdev_register_post_types' );

function vlrxdev_register_post_types() {
  register_post_type( 'vlrxdev_service', array(
    'labels'             => array(
      'name'               => 'Услуги',
      'singular_name'      => 'Услуга',
      'add_new'            => 'Добавить услугу',
      'add_new_item'       => 'Добавить новую услугу',
      'edit_item'          => 'Редактировать услугу',
      'new_item'           => 'Новая услуга',
      'view_item'          => 'Смотреть услугу',
      'search_items'       => 'Искать услуги',
      'not_found'          => 'Услуг не найдено',
      'not_found_in_trash' => 'В корзине услуг не найдено',
      'menu_name'          => 'Услуги',
    ),
    'public'             => true,
    'publicly_queryable'  => false,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'menu_icon'           => 'dashicons-cart',
    'capability_type'     => 'post',
    'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
    'has_archive'         => false,
  ) );

  register_post_type( 'vlrxdev_project', array(
    'labels'             => array(
      'name'               => 'Проекты',
      'singular_name'      => 'Проект',
      'add_new'            => 'Добавить проект',
      'add_new_item'       => 'Добавить новый проект',
      'edit_item'          => 'Редактировать проект',
      'new_item'           => 'Новый проект',
      'view_item'          => 'Смотреть проект',
      'search_items'       => 'Искать проекты',
      'not_found'          => 'Проектов не найдено',
      'not_found_in_trash' => 'В корзине проектов не найдено',
      'menu_name'          => 'Проекты',
    ),
    'public'             => true,
    'publicly_queryable'  => false,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'menu_icon'           => 'dashicons-portfolio',
    'capability_type'     => 'post',
    'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
    'has_archive'         => false,
  ) );

  register_post_type( 'vlrxdev_team_member', array(
    'labels'             => array(
      'name'               => 'Участники команды',
      'singular_name'      => 'Участник',
      'add_new'            => 'Добавить участника',
      'add_new_item'       => 'Добавить участника команды',
      'edit_item'          => 'Редактировать участника',
      'menu_name'          => 'Участники команды',
    ),
    'public'             => true,
    'publicly_queryable'  => false,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'menu_icon'           => 'dashicons-groups',
    'capability_type'     => 'post',
    'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
    'has_archive'         => false,
  ) );

  register_post_type( 'vlrxdev_condition', array(
    'labels'             => array(
      'name'               => 'Условия (аккордеон)',
      'singular_name'      => 'Пункт условия',
      'add_new'            => 'Добавить пункт',
      'add_new_item'       => 'Добавить пункт условия',
      'edit_item'          => 'Редактировать пункт',
      'menu_name'          => 'Условия',
    ),
    'public'             => true,
    'publicly_queryable'  => false,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'menu_icon'           => 'dashicons-list-view',
    'capability_type'     => 'post',
    'supports'            => array( 'title', 'editor', 'revisions', 'page-attributes' ),
    'has_archive'         => false,
  ) );
}

add_action( 'add_meta_boxes', 'vlrxdev_service_meta_boxes' );

function vlrxdev_service_meta_boxes() {
  add_meta_box(
    'vlrxdev_service_price',
    'Цена',
    'vlrxdev_service_price_callback',
    'vlrxdev_service',
    'side',
    'default'
  );
}

function vlrxdev_service_price_callback( $post ) {
  wp_nonce_field( 'vlrxdev_save_service_price', 'vlrxdev_service_price_nonce' );
  $price = get_post_meta( $post->ID, '_vlrxdev_price', true );
  ?>
  <p>
    <label for="vlrxdev_price">Цена (отображается на сайте)</label><br>
    <input type="text" id="vlrxdev_price" name="vlrxdev_price" value="<?php echo esc_attr( $price ); ?>" class="widefat" placeholder="например: от 5 000 ₽">
  </p>
  <?php
}

add_action( 'save_post_vlrxdev_service', 'vlrxdev_save_service_price' );

function vlrxdev_save_service_price( $post_id ) {
  if ( ! isset( $_POST['vlrxdev_service_price_nonce'] ) ||
       ! wp_verify_nonce( $_POST['vlrxdev_service_price_nonce'], 'vlrxdev_save_service_price' ) ) {
    return;
  }
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }
  if ( ! current_user_can( 'edit_post', $post_id ) ) {
    return;
  }
  if ( isset( $_POST['vlrxdev_price'] ) ) {
    update_post_meta( $post_id, '_vlrxdev_price', sanitize_text_field( $_POST['vlrxdev_price'] ) );
  }
}

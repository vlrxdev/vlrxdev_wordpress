<?php
/**
 * Вывод десктопного и мобильного изображения через <picture>.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Выводит адаптивное изображение: десктоп = мета _vlrxdev_desktop_image или миниатюра записи, мобильное = мета _vlrxdev_mobile_image.
 * Если мобильное не задано — на всех экранах показывается десктопное.
 *
 * @param int    $post_id   ID записи.
 * @param string $size_desktop Размер десктопного изображения.
 * @param string $size_mobile  Размер мобильного изображения.
 * @param string $link      URL обёртки (пустая строка = без ссылки).
 * @param string $breakpoint   Медиа-запрос для мобильного (max-width).
 */
function vlrxdev_responsive_image( $post_id, $size_desktop = 'medium_large', $size_mobile = 'medium', $link = '', $breakpoint = '767px' ) {
  $desktop_meta = (int) get_post_meta( $post_id, '_vlrxdev_desktop_image', true );
  $desktop_id   = $desktop_meta ? $desktop_meta : get_post_thumbnail_id( $post_id );
  $mobile_id    = (int) get_post_meta( $post_id, '_vlrxdev_mobile_image', true );

  if ( ! $desktop_id && ! $mobile_id ) {
    return;
  }

  $desktop_src = $desktop_id ? wp_get_attachment_image_src( $desktop_id, $size_desktop ) : null;
  $mobile_src  = $mobile_id ? wp_get_attachment_image_src( $mobile_id, $size_mobile ) : null;

  $alt = '';
  if ( $desktop_id ) {
    $alt = get_post_meta( $desktop_id, '_wp_attachment_image_alt', true );
    if ( $alt === '' ) {
      $alt = get_the_title( $desktop_id );
    }
  } elseif ( $mobile_id ) {
    $alt = get_post_meta( $mobile_id, '_wp_attachment_image_alt', true );
    if ( $alt === '' ) {
      $alt = get_the_title( $mobile_id );
    }
  }

  $open  = $link ? '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener">' : '';
  $close = $link ? '</a>' : '';

  if ( $mobile_src && (int) $mobile_id !== (int) $desktop_id ) {
    echo $open;
    echo '<picture>';
    echo '<source media="(max-width: ' . esc_attr( $breakpoint ) . ')" srcset="' . esc_url( $mobile_src[0] ) . '">';
    if ( $desktop_src ) {
      echo '<img src="' . esc_url( $desktop_src[0] ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
    } else {
      echo '<img src="' . esc_url( $mobile_src[0] ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
    }
    echo '</picture>';
    echo $close;
  } else {
    if ( $desktop_id ) {
      echo $open;
      echo wp_get_attachment_image( $desktop_id, $size_desktop, false, array( 'loading' => 'lazy', 'alt' => $alt ) );
      echo $close;
    }
  }
}

<?php
/**
 * Модуль вкладки: Услуги — вертикальный аккордеон.
 * Заголовок: название слева, «Цена» + цена справа. В раскрытии: описание, затем картинка при наличии.
 */
$services = new WP_Query( array(
  'post_type'      => 'vlrxdev_service',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order title',
  'order'          => 'ASC',
  'post_status'    => 'publish',
) );
?>

<div class="tab-services">
  <?php if ( $services->have_posts() ) : ?>
    <div class="accordion tab-services__accordion" data-accordion-single="true">
      <?php
      $index = 0;
      while ( $services->have_posts() ) :
        $services->the_post();
        $price = get_post_meta( get_the_ID(), '_vlrxdev_price', true );
        $index++;
        ?>
        <div class="accordion__item <?php echo $index === 1 ? 'is-open' : ''; ?>">
          <div class="accordion__head accordion__head--service">
            <span class="accordion__title"><?php the_title(); ?></span>
            <?php if ( $price !== '' ) : ?>
              <span class="accordion__right">Цена <?php echo esc_html( $price ); ?></span>
            <?php endif; ?>
          </div>
          <div class="accordion__body" <?php echo $index !== 1 ? 'hidden' : ''; ?>>
            <?php if ( get_the_content() ) : ?>
              <div class="accordion__content"><?php the_content(); ?></div>
            <?php endif; ?>
            <?php if ( has_post_thumbnail() || get_post_meta( get_the_ID(), '_vlrxdev_desktop_image', true ) || get_post_meta( get_the_ID(), '_vlrxdev_mobile_image', true ) ) : ?>
              <div class="accordion__image"><?php vlrxdev_responsive_image( get_the_ID() ); ?></div>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else : ?>
    <p class="tab-services__empty">Добавьте услуги в разделе «Услуги» в админ-панели.</p>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>
</div>

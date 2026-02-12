<?php
/**
 * Модуль вкладки: Услуги (из типа записей).
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
    <ul class="tab-services__list">
      <?php while ( $services->have_posts() ) : $services->the_post(); ?>
        <?php $price = get_post_meta( get_the_ID(), '_vlrxdev_price', true ); ?>
        <li class="tab-services__item service-card">
          <h3 class="service-card__title"><?php the_title(); ?></h3>
          <?php if ( get_the_content() ) : ?>
            <div class="service-card__content"><?php the_content(); ?></div>
          <?php endif; ?>
          <?php if ( $price ) : ?>
            <p class="service-card__price"><?php echo esc_html( $price ); ?></p>
          <?php endif; ?>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else : ?>
    <p class="tab-services__empty">Добавьте услуги в разделе «Услуги» в админ-панели.</p>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>
</div>

<?php
/**
 * Модуль вкладки: Условия — редактируемый аккордеон из типа записей.
 */
$conditions = new WP_Query( array(
  'post_type'      => 'vlrxdev_condition',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order title',
  'order'          => 'ASC',
  'post_status'    => 'publish',
) );
?>

<div class="tab-conditions">
  <?php if ( $conditions->have_posts() ) : ?>
    <div class="accordion tab-conditions__accordion" data-accordion-single="true">
      <?php
      $index = 0;
      while ( $conditions->have_posts() ) :
        $conditions->the_post();
        $index++;
        ?>
        <div class="accordion__item <?php echo $index === 1 ? 'is-open' : ''; ?>">
          <div class="accordion__head">
            <span class="accordion__title"><?php the_title(); ?></span>
          </div>
          <div class="accordion__body" <?php echo $index !== 1 ? 'hidden' : ''; ?>>
            <div class="accordion__content"><?php the_content(); ?></div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else : ?>
    <p class="tab-conditions__empty">Добавьте пункты в разделе «Условия» в админ-панели.</p>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>
</div>

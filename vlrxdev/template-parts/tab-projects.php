<?php
/**
 * Модуль вкладки: Проекты — вертикальный аккордеон (без цены).
 * Заголовок: название. В раскрытии: описание, затем картинка при наличии.
 */
$projects = new WP_Query( array(
  'post_type'      => 'vlrxdev_project',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order date',
  'order'          => 'DESC',
  'post_status'    => 'publish',
) );
?>

<div class="tab-projects">
  <?php if ( $projects->have_posts() ) : ?>
    <div class="accordion tab-projects__accordion" data-accordion-single="true">
      <?php
      $index = 0;
      while ( $projects->have_posts() ) :
        $projects->the_post();
        $link = get_post_meta( get_the_ID(), '_vlrxdev_project_link', true );
        $index++;
        ?>
        <div class="accordion__item <?php echo $index === 1 ? 'is-open' : ''; ?>">
          <div class="accordion__head accordion__head--project">
            <span class="accordion__title">
              <?php if ( $link ) : ?>
                <a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener" onclick="event.stopPropagation();"><?php the_title(); ?></a>
              <?php else : ?>
                <?php the_title(); ?>
              <?php endif; ?>
            </span>
          </div>
          <div class="accordion__body" <?php echo $index !== 1 ? 'hidden' : ''; ?>>
            <?php if ( get_the_content() ) : ?>
              <div class="accordion__content"><?php the_content(); ?></div>
            <?php endif; ?>
            <?php if ( has_post_thumbnail() || get_post_meta( get_the_ID(), '_vlrxdev_desktop_image', true ) || get_post_meta( get_the_ID(), '_vlrxdev_mobile_image', true ) ) : ?>
              <div class="accordion__image">
                <?php vlrxdev_responsive_image( get_the_ID(), 'medium_large', 'medium', $link ); ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else : ?>
    <p class="tab-projects__empty">Добавьте проекты в разделе «Проекты» в админ-панели.</p>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>
</div>

<?php
/**
 * Модуль вкладки: Проекты (из типа записей).
 */
$projects = new WP_Query( array(
  'post_type'      => 'vlrxdev_project',
  'posts_per_page' => -1,
  'orderby'        => 'date',
  'order'          => 'DESC',
  'post_status'    => 'publish',
) );
?>

<div class="tab-projects">
  <?php if ( $projects->have_posts() ) : ?>
    <ul class="tab-projects__list">
      <?php while ( $projects->have_posts() ) : $projects->the_post(); ?>
        <?php $link = get_post_meta( get_the_ID(), '_vlrxdev_project_link', true ); ?>
        <li class="tab-projects__item project-card">
          <?php if ( has_post_thumbnail() ) : ?>
            <div class="project-card__thumb">
              <?php if ( $link ) : ?>
                <a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener"><?php the_post_thumbnail( 'medium' ); ?></a>
              <?php else : ?>
                <?php the_post_thumbnail( 'medium' ); ?>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <div class="project-card__body">
            <h3 class="project-card__title">
              <?php if ( $link ) : ?>
                <a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener"><?php the_title(); ?></a>
              <?php else : ?>
                <?php the_title(); ?>
              <?php endif; ?>
            </h3>
            <?php if ( get_the_content() ) : ?>
              <div class="project-card__content"><?php the_content(); ?></div>
            <?php endif; ?>
          </div>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else : ?>
    <p class="tab-projects__empty">Добавьте проекты в разделе «Проекты» в админ-панели.</p>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>
</div>

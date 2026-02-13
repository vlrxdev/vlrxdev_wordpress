<?php
/**
 * Модуль вкладки: О нас — участники команды (аккордеон).
 * Заголовок: роль + никнейм. В раскрытии: биография, изображение при наличии, соц. сети.
 */
$members = new WP_Query( array(
  'post_type'      => 'vlrxdev_team_member',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order title',
  'order'          => 'ASC',
  'post_status'    => 'publish',
) );
?>

<div class="tab-about">
  <?php if ( $members->have_posts() ) : ?>
    <div class="accordion tab-about__accordion" data-accordion-single="true">
      <?php
      $index = 0;
      while ( $members->have_posts() ) :
        $members->the_post();
        $role     = get_post_meta( get_the_ID(), '_vlrxdev_team_role', true );
        $nickname = get_post_meta( get_the_ID(), '_vlrxdev_team_nickname', true );
        $socials  = get_post_meta( get_the_ID(), '_vlrxdev_socials', true );
        if ( ! is_array( $socials ) ) {
          $socials = array();
        }
        $index++;
        ?>
        <div class="accordion__item <?php echo $index === 1 ? 'is-open' : ''; ?>">
          <div class="accordion__head accordion__head--team">
            <span class="accordion__title">
              <?php if ( $role ) echo esc_html( $role ); ?>
              <?php if ( $role && $nickname ) echo ' · '; ?>
              <?php if ( $nickname ) echo esc_html( $nickname ); ?>
              <?php if ( ! $role && ! $nickname ) the_title(); ?>
            </span>
          </div>
          <div class="accordion__body" <?php echo $index !== 1 ? 'hidden' : ''; ?>>
            <?php if ( get_the_content() ) : ?>
              <div class="accordion__content"><?php the_content(); ?></div>
            <?php endif; ?>
            <?php if ( has_post_thumbnail() || get_post_meta( get_the_ID(), '_vlrxdev_desktop_image', true ) || get_post_meta( get_the_ID(), '_vlrxdev_mobile_image', true ) ) : ?>
              <div class="accordion__image"><?php vlrxdev_responsive_image( get_the_ID() ); ?></div>
            <?php endif; ?>
            <?php if ( ! empty( $socials ) ) : ?>
              <ul class="team-socials">
                <?php foreach ( $socials as $s ) : ?>
                  <?php if ( ! empty( $s['url'] ) ) : ?>
                    <li class="team-socials__item">
                      <a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener" class="team-socials__link">
                        <?php if ( ! empty( $s['icon'] ) ) : ?>
                          <?php
                          if ( strpos( $s['icon'], 'http' ) === 0 || strpos( $s['icon'], '//' ) === 0 ) {
                            echo '<img src="' . esc_url( $s['icon'] ) . '" alt="" class="team-socials__icon" width="24" height="24">';
                          } else {
                            echo '<span class="team-socials__icon ' . esc_attr( $s['icon'] ) . '" aria-hidden="true"></span>';
                          }
                          ?>
                        <?php else : ?>
                          <?php echo esc_html( $s['url'] ); ?>
                        <?php endif; ?>
                      </a>
                    </li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else : ?>
    <p class="tab-about__empty">Добавьте участников в разделе «Участники команды» в админ-панели.</p>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>
</div>

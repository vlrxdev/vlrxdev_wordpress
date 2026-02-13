<?php
/**
 * Контент футера из настроек темы (Внешний вид → Футер).
 */
$opt = get_option( 'vlrxdev_footer', array() );
if ( ! is_array( $opt ) ) {
  $opt = array();
}
$opt = array_merge( array(
  'phone'        => '',
  'phone_icon'   => '',
  'email'        => '',
  'email_icon'   => '',
  'address'      => '',
  'address_icon' => '',
  'socials'      => array(),
  'links'        => array(),
), $opt );

$has_contacts = ( $opt['phone'] !== '' || $opt['email'] !== '' || $opt['address'] !== '' );
$has_block2   = ( ! empty( $opt['socials'] ) || ! empty( $opt['links'] ) );

if ( ! $has_contacts && ! $has_block2 ) {
  return;
}
?>
<footer class="site-footer">
  <div class="site-footer__inner">
    <?php if ( $has_contacts ) : ?>
      <div class="site-footer__block site-footer__block--contacts">
        <h2 class="site-footer__title">Контакты</h2>
        <ul class="site-footer__contacts">
          <?php if ( $opt['phone'] !== '' ) : ?>
            <li class="site-footer__contact">
              <?php echo vlrxdev_footer_icon( $opt['phone_icon'] ); ?>
              <a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $opt['phone'] ) ); ?>"><?php echo esc_html( $opt['phone'] ); ?></a>
            </li>
          <?php endif; ?>
          <?php if ( $opt['email'] !== '' ) : ?>
            <li class="site-footer__contact">
              <?php echo vlrxdev_footer_icon( $opt['email_icon'] ); ?>
              <a href="<?php echo esc_url( 'mailto:' . $opt['email'] ); ?>"><?php echo esc_html( $opt['email'] ); ?></a>
            </li>
          <?php endif; ?>
          <?php if ( $opt['address'] !== '' ) : ?>
            <li class="site-footer__contact site-footer__contact--address">
              <?php echo vlrxdev_footer_icon( $opt['address_icon'] ); ?>
              <span><?php echo nl2br( esc_html( $opt['address'] ) ); ?></span>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ( $has_block2 ) : ?>
      <div class="site-footer__block site-footer__block--secondary">
        <?php if ( ! empty( $opt['socials'] ) ) : ?>
          <div class="site-footer__socials">
            <h3 class="site-footer__subtitle">Соц. сети</h3>
            <ul class="site-footer__list site-footer__list--socials">
              <?php foreach ( $opt['socials'] as $s ) : ?>
                <?php if ( ! empty( $s['url'] ) ) : ?>
                  <li>
                    <a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener" class="site-footer__link">
                      <?php echo vlrxdev_footer_icon( isset( $s['icon'] ) ? $s['icon'] : '' ); ?>
                      <?php if ( empty( $s['icon'] ) ) : ?>
                        <span><?php echo esc_html( $s['url'] ); ?></span>
                      <?php endif; ?>
                    </a>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        <?php if ( ! empty( $opt['links'] ) ) : ?>
          <div class="site-footer__links">
            <h3 class="site-footer__subtitle">Полезные ссылки</h3>
            <ul class="site-footer__list site-footer__list--links">
              <?php foreach ( $opt['links'] as $l ) : ?>
                <?php if ( ! empty( $l['url'] ) ) : ?>
                  <li>
                    <a href="<?php echo esc_url( $l['url'] ); ?>" target="_blank" rel="noopener" class="site-footer__link">
                      <?php echo vlrxdev_footer_icon( isset( $l['icon'] ) ? $l['icon'] : '' ); ?>
                      <span><?php echo esc_html( isset( $l['label'] ) && $l['label'] !== '' ? $l['label'] : $l['url'] ); ?></span>
                    </a>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</footer>

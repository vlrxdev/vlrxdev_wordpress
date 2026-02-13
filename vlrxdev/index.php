<?php
/**
 * Главный шаблон темы vlrxdev.
 * Блоки: интро, вкладки (модули), подвал с контактами.
 */
get_header();
?>

<main>
  <section class="intro">
    <h1 class="intro__title">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <?php bloginfo( 'name' ); ?>
      </a>
    </h1>
    <div class="intro_subtitle">
        Fullstack developer
    </div>
  </section>

  <section class="tabs" id="tabs">
    <div class="tabs__nav" role="tablist">
      <button type="button" class="tabs__tab is-active" role="tab" aria-selected="true" aria-controls="panel-1" id="tab-1" data-tab="1">Услуги</button>
      <button type="button" class="tabs__tab" role="tab" aria-selected="false" aria-controls="panel-2" id="tab-2" data-tab="2">О нас</button>
      <button type="button" class="tabs__tab" role="tab" aria-selected="false" aria-controls="panel-3" id="tab-3" data-tab="3">Проекты</button>
      <button type="button" class="tabs__tab" role="tab" aria-selected="false" aria-controls="panel-4" id="tab-4" data-tab="4">Условия</button>
      <!-- <button type="button" class="tabs__tab" role="tab" aria-selected="false" aria-controls="panel-5" id="tab-5" data-tab="5">Отзывы</button> -->
    </div>
    <div class="tabs__panels">
      <div class="tabs__panel is-active" id="panel-1" role="tabpanel" aria-labelledby="tab-1">
        <?php get_template_part( 'template-parts/tab', 'services' ); ?>
      </div>
      <div class="tabs__panel" id="panel-2" role="tabpanel" aria-labelledby="tab-2" hidden>
        <?php get_template_part( 'template-parts/tab', 'about' ); ?>
      </div>
      <div class="tabs__panel" id="panel-3" role="tabpanel" aria-labelledby="tab-3" hidden>
        <?php get_template_part( 'template-parts/tab', 'projects' ); ?>
      </div>
      <div class="tabs__panel" id="panel-4" role="tabpanel" aria-labelledby="tab-4" hidden>
        <?php get_template_part( 'template-parts/tab', 'conditions' ); ?>
      </div>
      <!-- <div class="tabs__panel" id="panel-5" role="tabpanel" aria-labelledby="tab-5" hidden>
        <?php get_template_part( 'template-parts/tab', 'reviews' ); ?>
      </div> -->
    </div>
  </section>

  <?php get_template_part( 'template-parts/footer', 'content' ); ?>
</main>

<?php
get_footer();

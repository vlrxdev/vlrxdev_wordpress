/**
 * Вертикальный аккордеон: клик по заголовку раскрывает/скрывает блок.
 * Один открытый элемент на контейнер (data-accordion-single="true") или несколько.
 */
(function () {
  function init() {
    var containers = document.querySelectorAll('.accordion');
    containers.forEach(function (container, containerIndex) {
      var single = container.getAttribute('data-accordion-single') !== 'false';
      var heads = container.querySelectorAll('.accordion__head');
      var items = container.querySelectorAll('.accordion__item');
      var prefix = 'accordion-' + containerIndex + '-';

      heads.forEach(function (head, index) {
        head.setAttribute('role', 'button');
        head.setAttribute('tabindex', '0');
        head.setAttribute('aria-expanded', container.querySelector('.accordion__item.is-open') === items[index] ? 'true' : 'false');
        head.setAttribute('aria-controls', prefix + 'body-' + index);
        if (!head.id) head.id = prefix + 'head-' + index;
        var body = items[index].querySelector('.accordion__body');
        if (body) {
          body.setAttribute('id', prefix + 'body-' + index);
          body.setAttribute('aria-labelledby', head.id);
        }
      });

      function openItem(item) {
        if (single) {
          items.forEach(function (i) {
            i.classList.remove('is-open');
            var h = i.querySelector('.accordion__head');
            var b = i.querySelector('.accordion__body');
            if (h) h.setAttribute('aria-expanded', 'false');
            if (b) b.hidden = true;
          });
        }
        item.classList.add('is-open');
        var head = item.querySelector('.accordion__head');
        var body = item.querySelector('.accordion__body');
        if (head) head.setAttribute('aria-expanded', 'true');
        if (body) body.hidden = false;
      }

      function closeItem(item) {
        item.classList.remove('is-open');
        var head = item.querySelector('.accordion__head');
        var body = item.querySelector('.accordion__body');
        if (head) head.setAttribute('aria-expanded', 'false');
        if (body) body.hidden = true;
      }

      function toggle(item) {
        var isOpen = item.classList.contains('is-open');
        if (isOpen) closeItem(item);
        else openItem(item);
      }

      heads.forEach(function (head, index) {
        var item = items[index];
        var body = item.querySelector('.accordion__body');
        if (body) body.hidden = !item.classList.contains('is-open');

        head.addEventListener('click', function (e) {
          e.preventDefault();
          toggle(item);
        });
        head.addEventListener('keydown', function (e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggle(item);
          }
        });
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

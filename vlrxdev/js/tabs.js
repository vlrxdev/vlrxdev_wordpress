/**
 * Переключение горизонтальных вкладок (без зависимостей).
 */
(function () {
  const container = document.getElementById('tabs');
  if (!container) return;

  const tabs = container.querySelectorAll('.tabs__tab');
  const panels = container.querySelectorAll('.tabs__panel');

  function switchTo(index) {
    tabs.forEach(function (tab, i) {
      const isActive = i === index;
      tab.classList.toggle('is-active', isActive);
      tab.setAttribute('aria-selected', isActive);
    });
    panels.forEach(function (panel, i) {
      const isActive = i === index;
      panel.classList.toggle('is-active', isActive);
      panel.hidden = !isActive;
    });
  }

  tabs.forEach(function (tab, index) {
    tab.addEventListener('click', function () {
      switchTo(index);
    });
    tab.addEventListener('keydown', function (e) {
      let next = index;
      if (e.key === 'ArrowRight' || e.key === 'ArrowDown') next = Math.min(index + 1, tabs.length - 1);
      if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') next = Math.max(index - 1, 0);
      if (next !== index) {
        switchTo(next);
        tabs[next].focus();
        e.preventDefault();
      }
    });
  });
})();

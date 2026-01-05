jQuery(function ($) {

  // Helpers
  function getNodeFromToggle($toggle) {
    return $toggle.closest('.mobile-menu-item, .mobile-menu-sub-item');
  }

  function getDirectSubmenu($node) {
    return $node.children('.mobile-menu-submenu');
  }

  function closeNode($node) {
    const $submenu = getDirectSubmenu($node);
    $node.removeClass('active');
    $node.find('> .mobile-menu-toggle').attr('aria-expanded', 'false');

    if ($submenu.length) {
      $submenu.stop(true, true).slideUp(250);
    }
  }

  function openNode($node) {
    const $submenu = getDirectSubmenu($node);
    $node.addClass('active');
    $node.find('> .mobile-menu-toggle').attr('aria-expanded', 'true');

    if ($submenu.length) {
      $submenu.stop(true, true).slideDown(250);
    }
  }

  // Acordeón por nivel: cierra solo hermanos del mismo nivel
  function closeSiblingsSameLevel($node) {
    const $siblings = $node.parent().children('.mobile-menu-item.active, .mobile-menu-sub-item.active').not($node);

    $siblings.each(function () {
      const $sib = $(this);
      // Cierra hermano y todo lo que cuelgue de él
      $sib.find('.mobile-menu-item.active, .mobile-menu-sub-item.active').each(function () {
        closeNode($(this));
      });
      closeNode($sib);
    });
  }

  // Click en el toggle (delegado, vale para cualquier nivel)
  $(document).on('click', '.mobile-menu-accordion .mobile-menu-toggle', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const $node = getNodeFromToggle($(this));
    const $submenu = getDirectSubmenu($node);
    if (!$submenu.length) return;

    if ($node.hasClass('active')) {
      closeNode($node);
    } else {
      closeSiblingsSameLevel($node);
      openNode($node);
    }
  });

  // Opcional: click en el enlace si tiene hijos y es "#"
  $(document).on('click', '.mobile-menu-accordion .has-children > a', function (e) {
    const href = ($(this).attr('href') || '').trim();
    if (href === '#' || href === '') {
      e.preventDefault();
      $(this).siblings('.mobile-menu-toggle').trigger('click');
    }
  });

  // Inicialización: abre la rama activa (todos los niveles)
  $('.mobile-menu-accordion .current-menu-ancestor, .mobile-menu-accordion .current-menu-parent').each(function () {
    const $node = $(this);
    const $submenu = getDirectSubmenu($node);
    if ($submenu.length) {
      $node.addClass('active');
      $node.find('> .mobile-menu-toggle').attr('aria-expanded', 'true');
      $submenu.show(); // sin animación al cargar
    }
  });

});

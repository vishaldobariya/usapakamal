(function ($) {
  'use strict';

  const $body = $('body');
  const versionSelect = $('#theme_version');
  const layoutSelect = $('#theme_layout');
  const sidebarStyleSelect = $('#sidebar_style');
  const sidebarPositionSelect = $('#sidebar_position');
  const headerPositionSelect = $('#header_position');
  const containerLayoutSelect = $('#container_layout');

  versionSelect.val($body.attr('data-theme-version'));
  layoutSelect.val($body.attr('data-layout'));
  sidebarStyleSelect.val($body.attr('data-sidebar-style'));
  sidebarPositionSelect.val($body.attr('data-sidebar-position'));
  headerPositionSelect.val($body.attr('data-header-position'));
  containerLayoutSelect.val($body.attr('data-container'));

  versionSelect.on('change', function () {
    $body.attr('data-theme-version', this.value);
    $.post('/admin/dashboard/change-theme', {
      key: 'version',
      value: this.value,
    });
  });

  sidebarPositionSelect.on('change', function () {
    $body.attr('data-sidebar-position', this.value);
    $.post('/admin/dashboard/change-theme', {
      key: 'sidebar_position',
      value: this.value,
    });
    if ($body.attr('data-sidebar-position') === 'fixed') {
      $('.nk-nav-scroll').slimscroll({
        position: 'right',
        size: '5px',
        height: '100%',
        color: 'transparent',
      });
    } else {
      $('.nk-nav-scroll').slimscroll({
        destroy: true,
      });
    }
  });

  headerPositionSelect.on('change', function () {
    $body.attr('data-header-position', this.value);
    $.post('/admin/dashboard/change-theme', {
      key: 'header_position',
      value: this.value,
    });
  });

  layoutSelect.on('change', function () {
    if ($body.attr('data-sidebar-style') === 'overlay') {
      $body.attr('data-sidebar-style', 'full');
      $.post('/admin/dashboard/change-theme', {
        key: 'sidebar_style',
        value: 'full',
      });
    }

    $.post('/admin/dashboard/change-theme', {
      key: 'layout',
      value: this.value,
    });
    $body.attr('data-layout', this.value);
  });

  containerLayoutSelect.on('change', function () {
    if (this.value === 'boxed') {
      if (
        $body.attr('data-layout') === 'vertical' &&
        $body.attr('data-sidebar-style') === 'full'
      ) {
        $body.attr('data-sidebar-style', 'overlay');
        $.post('/admin/dashboard/change-theme', {
          key: 'sidebar_style',
          value: 'overlay',
        });
      }
    }
    $.post('/admin/dashboard/change-theme', {
      key: 'container_layout',
      value: this.value,
    });
    $body.attr('data-container', this.value);
  });

  sidebarStyleSelect.on('change', function () {
    if ($body.attr('data-layout') === 'horizontal') {
      if (this.value === 'overlay') {
        alert('Sorry! Overlay is not possible in Horizontal layout.');
        return;
      }
    }

    if ($body.attr('data-layout') === 'vertical') {
      if ($body.attr('data-container') === 'boxed' && this.value === 'full') {
        alert('Sorry! Full menu is not available in Vertical Boxed layout.');
        return;
      }
    }
    $.post('/admin/dashboard/change-theme', {
      key: 'sidebar_style',
      value: this.value,
    });
    $body.attr('data-sidebar-style', this.value);
  });

  $('input[name="navigation_header"]').on('click', function () {
    $body.attr('data-nav-headerbg', this.value);
    $.post('/admin/dashboard/change-theme', {
      key: 'navheader_bg',
      value: this.value,
    });
  });

  $('input[name="header_bg"]').on('click', function () {
    $body.attr('data-headerbg', this.value);
    $.post('/admin/dashboard/change-theme', {
      key: 'header_bg',
      value: this.value,
    });
  });

  $('input[name="sidebar_bg"]').on('click', function () {
    $body.attr('data-sibebarbg', this.value);
    $.post('/admin/dashboard/change-theme', {
      key: 'sidebar_bg',
      value: this.value,
    });
  });
})(jQuery);

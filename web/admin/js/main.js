import '../scss/main.scss';

const btn = $('#to-top');
const $window = $(window);
const $document = $(document);

$window.scroll(() => {
  if ($window.scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

$document.on('click', '#to-top', (e) => {
  e.preventDefault();
  $('html, body').animate({ scrollTop: 0 }, '300');
});

$document.on('change', '.page-sizer', function () {
  const size = $(this).val();
  const url = $(this).data('url');
  $.get(`${url}?size=${size}`).done(() => {
    location.reload();
  });
});

$(window).on('scroll', () => {
  if ($(window).scrollTop() > 5) {
    $('.js-header ').addClass('is-fixed');
  } else {
    $('.js-header ').removeClass('is-fixed');
  }
});

$('.header-nav-toggle').click(() => {
  $('.header-nav').addClass('is-visible');
});
$('.header-nav-close').click(() => {
  $('.header-nav').removeClass('is-visible');
});

$(window).resize(() => {
  const w = $(window).outerWidth();
  if (w > 992) {
    $('.header-nav').removeClass('is-visible');
  }
});
$(document).on('click', '.js-show-search', (e) => {
  e.preventDefault();
  $('.header').removeClass('is-fixed');
  $('.js-search-input').focus();
});
$('.js-search-input').keyup(function () {
  if ($(this).val()) {
    // $('.search-submit img').attr('src', '/images/icon-close.svg');
    // $('.search-submit').addClass('js-search-clear');
    $('.js-clear-search').show();
  } else {
    $('.js-clear-search').hide();
    // $('.search-submit img').attr('src', '/images/icon-search.svg');
    // $('.search-submit').removeClass('js-search-clear');
  }
});
$(document).on('click', '.js-search-clear', () => {
  $('.js-search-input').val('');
  $('.js-clear-search').hide();
  // $('.search-submit img').attr('src', '/images/icon-search.svg');
  // $('.search-submit').removeClass('js-search-clear');
  return false;
});
$('.js-show-searchbar').on('click', () => {
  $('.header-search').toggleClass('is-show');
});

import '../scss/bootstrap.scss';
import '../scss/main.scss';
import 'slick-carousel';
import 'jquery-zoom';
import Noty from 'noty';

const Event = function () {
  this.attach = function (evtName, element, listener, capture) {
    let evt = '';
    const useCapture = capture === undefined ? true : capture;
    let handler = null;

    if (window.addEventListener === undefined) {
      evt = `on${evtName}`;
      handler = function (evt, listener) {
        element.attachEvent(evt, listener);
        return listener;
      };
    } else {
      evt = evtName;
      handler = function (evt, listener, useCapture) {
        element.addEventListener(evt, listener, useCapture);
        return listener;
      };
    }

    return handler.apply(element, [
      evt,
      function (ev) {
        const e = ev || event;
        const src = e.srcElement || e.target;

        listener(e, src);
      },
      useCapture,
    ]);
  };

  this.detach = function (evtName, element, listener, capture) {
    let evt = '';
    const useCapture = capture === undefined ? true : capture;

    if (window.removeEventListener === undefined) {
      evt = `on${evtName}`;
      element.detachEvent(evt, listener);
    } else {
      evt = evtName;
      element.removeEventListener(evt, listener, useCapture);
    }
  };

  this.stop = function (evt) {
    evt.cancelBubble = true;

    if (evt.stopPropagation) {
      evt.stopPropagation();
    }
  };

  this.prevent = function (evt) {
    if (evt.preventDefault) {
      evt.preventDefault();
    } else {
      evt.returnValue = false;
    }
  };
};
$(document)
  .ready(() => {
    if ($('.hero-slider').length) {
      let slider;
      const w = $(window)
        .outerWidth();
      if (w < 576) {
        slider = $('.hero-slider-sm');
      } else {
        slider = $('.hero-slider-lg');
      }

      const time = 6;
      const bar = $('.hero-progress');

      let tick;
      let percentTime = 0;

      slider.slick({
        fade: true,
        autoplay: true,

        autoplaySpeed: 6000,

        dots: true,
        infinite: true,
        speed: 500,
        // cssEase: 'linear',
        arrows: false,
      });

      function resetProgressbar() {
        bar.css({
          width: `${0}%`,
        });
        clearTimeout(tick);
      }

      function startProgressbar() {
        resetProgressbar();
        percentTime = 0;

        tick = setInterval(interval, 10);
      }

      function interval() {
        percentTime += 1 / (time + 0.1);
        bar.css({
          width: `${percentTime}%`,
        });
        if (percentTime >= 100) {
          slider.slick('slickNext');
          startProgressbar();
        }
      }

      startProgressbar();
    }
  });
if ($('.zoomimages').length > 0) {
  const w = $(window)
    .outerWidth();
  if (w > 767) {
    $('.zoomimages')
      .zoom();
  }
}
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
      .attr('content'),
  },
});

$('[data-toggle="tooltip"]')
  .tooltip();

// $(document).on('click', '.js-up-btn', (e) => {
//   e.preventDefault();
//   // alert('i');
//   $('html, body').animate({ scrollTop: 0 }, '300');
// });
// $(document).on('click', '.js-up-btn', (e) => {
//   e.stopPropagation();
//   $.scrollTo($('.trigger'), 600);
//   return false;
// });
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
$document.on('click touchstart', '#to-top', (e) => {
  $('html, body')
    .animate({ scrollTop: 0 }, '300');
});
$(window)
  .on('scroll', () => {
    // if ($(window).scrollTop() > 300) {
    //   $('.js-up-btn').addClass('is-show');
    // } else {
    //   $('.js-up-btn').removeClass('is-show');
    // }
    if ($(window)
      .scrollTop() > 5) {
      $('.header')
        .addClass('is-fixed');
    } else {
      $('.header')
        .removeClass('is-fixed');
    }
  });

$('.header-nav-toggle')
  .click(() => {
    $('.header-nav')
      .addClass('is-visible');
  });
$('.header-nav-close')
  .click(() => {
    $('.header-nav')
      .removeClass('is-visible');
  });

$(window)
  .resize(() => {
    const w = $(window)
      .outerWidth();
    if (w > 992) {
      $('.header-nav')
        .removeClass('is-visible');
    }
  });
$(document)
  .on('click', '.js-show-search', (e) => {
    e.preventDefault();
    $('.header')
      .removeClass('is-fixed');
    $('.js-search-input')
      .focus();
  });
$('.js-search-input')
  .keyup(function () {
    if ($(this)
      .val()) {
      $('.js-clear-search')
        .show();
    } else {
      $('.js-clear-search')
        .hide();
    }
  });
$(document)
  .on('click', '.js-search-clear', () => {
    $('.js-search-input')
      .val('');
    $('.js-clear-search')
      .hide();
    return false;
  });

function animateCart(btn) {
  // const cart = $('.header-nav .js-count-cart');
  let cart = $('.header-top .header-links-item-cart');
  const w = $(window)
    .outerWidth();

  if (w < 992) {
    cart = $('.header-panel .header-mobile .header-links-item');
  } else if ($('.header')
    .hasClass('is-fixed')) {
    cart = $('.header.is-fixed .nav-item-link--cart');
  } else {
    cart = $('.header-top .header-links-item-cart');
  }
  const imgToDrag = $(btn)
    .closest('.js-product-card')
    .find('.js-product-img');

  if (imgToDrag) {
    const imgclone = imgToDrag
      .clone()
      .offset({
        top: imgToDrag.offset().top,
        left: imgToDrag.offset().left,
      })
      .css({
        opacity: '.5',
        position: 'absolute',
        height: '150px',
        width: '150px',
        zIndex: '99999',
      })
      .appendTo($('body'))
      .animate(
        {
          top: cart.offset().top + 10,
          left: cart.offset().left + 10,
          width: 75,
          height: 75,
        },
        1000
        // 'easeInOutExpo'
      );

    imgclone.animate({
      width: 0,
      height: 0,
    });
  }
}

$('.js-add-to-cart')
  .click(function (e) {
    e.preventDefault();
    const href = $(this)
      .attr('href');
    const btn = $(this);
    const id = $(this)
      .data('id');
    const qty = $(this)
      .data('qty');
    $.ajax({
      url: '/shop/cart/add-to-cart',
      method: 'POST',
      data: {
        id,
        qty,
      },
      beforeSend() {
        btn.addClass('is-load');
      },
      success(res) {
        btn.removeClass('is-load');
        animateCart(btn);

        const noty = new Noty({
          text: ' Product Added to the Cart',
          layout: 'topRight',
          theme: 'bootstrap-v4',
          type: 'warning',
          closeWith: ['click', 'button'],
          timeout: '2000',
          progressBar: false,
        });

        $('.js-count-cart')
          .text(res.count);
        $('.js-cost-cart')
          .text(res.cost);
        setTimeout(() => {
          noty.show();
        }, 700);
        if (href && href !== '#') {
          window.location = href;
        }
      },
    });
  });

function addToCart(form, btn) {
  $.ajax({
    url: '/shop/cart/add-to-cart',
    data: form.serialize(),
    method: 'POST',
    beforeSend() {
      btn.addClass('is-load');
    },
    success(res) {
      animateCart(btn);
      const noty = new Noty({
        text: ' Product Added to the Cart',
        layout: 'topRight',
        theme: 'bootstrap-v4',
        type: 'warning',
        closeWith: ['click', 'button'],
        timeout: '3000',
        progressBar: false,
      });
      btn.removeClass('is-load');
      $('.js-count-cart')
        .text(res.count);
      $('.js-cost-cart')
        .text(res.cost);
      setTimeout(() => {
        noty.show();
      }, 400);
    },
  });
}

$(document).on('click', '.js-confirm-message',  function (e) {
    e.preventDefault();
    const form = $('#js-form-add-cart');
    const btn = $(this);
    $('#js-engraving')
      .modal('hide');
    addToCart(form, btn);
  });
$('.js-edit-message')
  .click((e) => {
    e.preventDefault();
    $('#js-engraving')
      .modal('hide');
  });
$('#js-button-add-to-cart')
  .click(function (e) {
    e.preventDefault();
    $('.js-confirm-message').css('display','block')
    $('.js-buy-now-confirm').css('display','none')
    const form = $('#js-form-add-cart');
    const btn = $(this);
    const showModal = false;
    if ($('#js-engrave-front')
      .prop('checked') === true) {
      if ($('#js-block-front-engrave input.is-valid').length) {
        $('#js-engraving')
          .modal('show');
      } else if ($('#js-block-front-engrave.is-valid').length) {
        $('#js-engraving')
          .modal('show');
      } else {
        // addToCart(form, btn);
        swal({
          title: 'Attention!',
          text:
            'You have not added engraving! Cancel engraving if you change your mind.',
          icon: 'error',
          button: true,
        });
      }
    } else {
      addToCart(form, btn);
    }
  });

$('.form-group-relative input')
  .on('change keyup keydown blur', function (e) {
    if ($(this)
      .val() !== '') {
      $(this)
        .closest('.form-group-relative')
        .addClass('is-focus');
    } else {
      $(this)
        .closest('.form-group-relative')
        .removeClass('is-focus');
    }
  });
if ($('.glider').length) {
  new Glider(document.querySelector('.glider'), {
    slidesToShow: 2,
    slidesToScroll: 1,
    draggable: true,
    autoheight: true,
    arrows: {
      prev: '.glider-prev',
      next: '.glider-next',
    },
    responsive: [
      {
        // If Screen Size More than 768px
        breakpoint: 1240,
        settings: {
          slidesToShow: 6,
          slidesToScroll: 1,
          duration: 0.5,
        },
      },
      {
        // If Screen Size More than 768px
        breakpoint: 900,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 1,
          duration: 0.5,
        },
      },
      {
        // If Screen Size More than 768px
        breakpoint: 768,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 1,
          duration: 0.5,
        },
      },
      {
        // If Screen Size More than 768px
        breakpoint: 576,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          duration: 0.5,
        },
      },
    ],
  });
}

(function () {
  if (window.localStorage) {
    const verify = localStorage.getItem('verify');
    const firstTime = localStorage.getItem('firsttime');
    if (verify === null) {
      $('body')
        .addClass('no-verify');
      $('.no-verify .greet')
        .css({ opacity: 1 });
    }
    if (verify === 'false') {
      if (firstTime === 'true') {
        $('body')
          .removeClass('no-verify');
        localStorage.setItem('firsttime', 'false');
      } else {
        $('body')
          .addClass('no-verify');
        $('.no-verify .greet')
          .css({ opacity: 1 });
      }
    }
    if (verify === 'true') {
      $('body')
        .removeClass('no-verify');
    }
  }
  $('.js-verify-yes')
    .on('click', () => {
      localStorage.setItem('verify', 'true');
      $('body')
        .removeClass('no-verify');
    });
  $('.js-verify-no')
    .on('click', () => {
      localStorage.setItem('verify', 'false');
      localStorage.setItem('firsttime', 'true');
      location.pathname = '/contact';
    });
})();
$('.js-show-searchbar')
  .on('click', () => {
    $('.header-search')
      .toggleClass('is-show');
  });
$('img')
  .on('mousedown', (e) => {
    e.preventDefault();
  });
$('body')
  .on('contextmenu', (e) => false);

$('#subscribe-email')
  .on('blur', function () {
    if ($(this)
      .val() == '') {
      $('#subscribe-email')
        .attr('placeholder', 'Email cannot be blank.');
      $('#js-subscribe-form')
        .find('.help-block')
        .hide();
    } else {
      $('#subscribe-email')
        .attr('placeholder', 'Email');
      $('#js-subscribe-form')
        .find('.help-block')
        .show();
    }
  });


  jQuery(document).ready(function ($) {
    $.LightBoxSimple({
      classImageThumbnail: 'lb-thumbnail'
    });

    var $galleryGroup = $('[id^=lb-gallery]');
    var $galleryOnlyImage = $('a.lb-only-image');

    if ($galleryGroup.length) {
      $galleryGroup.each(function () {
        $(this).lightGallery({
          mode: 'lg-slide',
          cssEasing: 'ease',
          speed: 600,
          height: '100%',
          width: '100%',
          addClass: '',
          startClass: 'lg-start-zoom',
          backdropDuration: 150,
          hideBarsDelay: 6000,
          useLeft: false,
          closable: true,
          loop: true,
          escKey: true,
          keyPress: true,
          controls: true,
          slideEndAnimatoin: true,
          hideControlOnEnd: false,
          download: true,
          counter: true,
          enableDrag: true,
          enableTouch: true,
          pager: false,
          thumbnail: true,
          showThumbByDefault: true,
          animateThumb: true,
          currentPagerPosition: 'middle',
          thumbWidth: 100,
          thumbContHeight: 100,
          thumbMargin: 5,
          autoplay: true,
          pause: 5000,
          progressBar: true,
          autoplayControls: true,
          fullScreen: true,
          zoom: true
        });
      });
    }
    if ($galleryOnlyImage.length) {
      $galleryOnlyImage.each(function () {
        $(this).lightGallery({
          selector: 'this',
          mode: 'lg-slide',
          cssEasing: 'ease',
          speed: 600,
          height: '100%',
          width: '100%',
          addClass: '',
          startClass: 'lg-start-zoom',
          backdropDuration: 150,
          hideBarsDelay: 6000,
          useLeft: false,
          closable: true,
          escKey: true,
          keyPress: true,
          download: true,
          counter: true,
          enableDrag: true,
          enableTouch: true,
          fullScreen: true,
          zoom: true
        });
      });
    }
  });
  
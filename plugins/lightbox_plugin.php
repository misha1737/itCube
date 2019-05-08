<?php
/*
Plugin Name: Lightbox Plugin
Description: Beautiful, flexible and responsive Lightbox plugin
Version: 1.6
Author: Alexey Polnikov
Author URI: http://lexsite.ru/
*/

$thisfile_lightbox = basename(__FILE__, ".php");
$lightbox_settings_file = GSDATAOTHERPATH .'lightbox_plugin.xml';
$plugin_path = $SITEURL . 'plugins/' . $thisfile_lightbox . '/';
$version = '1.6';

i18n_merge($thisfile_lightbox) || i18n_merge($thisfile_lightbox, 'en_US');

register_plugin(
	$thisfile_lightbox,
	'Lightbox Plugin',
	$version,
	'Alexey Polnikov',
	'http://lexsite.ru',
	i18n_r($thisfile_lightbox.'/LIGHTBOX_DESC'),
	'plugins',
	'lightbox_config'
);

add_filter('content','del_p_of_img');

add_action('plugins-sidebar','createSideMenu',array($thisfile_lightbox,i18n_r($thisfile_lightbox.'/LIGHTBOX_CONFIGURE')));

add_action('theme-header','lightbox_show_css');
add_action('theme-footer','lightbox_show_js');

//Default options
include(GSPLUGINPATH . $thisfile_lightbox . '/default_params.php');

$params = array();

# get XML data
if (file_exists($lightbox_settings_file)) {
  $lightbox_data = getXML($lightbox_settings_file);
  if ($lightbox_data) foreach ($lightbox_data->children() as $child) {
    if (!array_key_exists($child->getName(), $params)) $params[$child->getName()] = (string) $child;
  }
}

foreach ($lbp_def_params as $key => $value) {
  if (!array_key_exists($key, $params)) $params[$key] = $value;
}

if (!file_exists(GSPLUGINPATH . $thisfile_lightbox . '/js/lightbox_config.js')) {
  lightbox_creat_js();
}

function lightbox_creat_js() {
  global $thisfile_lightbox, $params;

  $fjs = fopen(GSPLUGINPATH . $thisfile_lightbox . '/js/lightbox_config.js', 'w+');
  $fjs_content = '
  jQuery(document).ready(function ($) {
    $.LightBoxSimple({
      classImageThumbnail: \''.$params['classImageThumbnail'].'\'
    });

    var $galleryGroup = $(\'[id^=lb-gallery]\');
    var $galleryOnlyImage = $(\'a.lb-only-image\');

    if ($galleryGroup.length) {
      $galleryGroup.each(function () {
        $(this).lightGallery({
          mode: \''.$params['mode'].'\',
          cssEasing: \''.$params['cssEasing'].'\',
          speed: '.$params['speed'].',
          height: \''.$params['height'].'\',
          width: \''.$params['width'].'\',
          addClass: \''.$params['addClass'].'\',
          startClass: \''.$params['startClass'].'\',
          backdropDuration: '.$params['backdropDuration'].',
          hideBarsDelay: '.$params['hideBarsDelay'].',
          useLeft: '.$params['useLeft'].',
          closable: '.$params['closable'].',
          loop: '.$params['loop'].',
          escKey: '.$params['escKey'].',
          keyPress: '.$params['keyPress'].',
          controls: '.$params['controls'].',
          slideEndAnimatoin: '.$params['slideEndAnimatoin'].',
          hideControlOnEnd: '.$params['hideControlOnEnd'].',
          download: '.$params['download'].',
          counter: '.$params['counter'].',
          enableDrag: '.$params['enableDrag'].',
          enableTouch: '.$params['enableTouch'].',
          pager: '.$params['pager'].',
          thumbnail: '.$params['thumbnail'].',
          showThumbByDefault: '.$params['showThumbByDefault'].',
          animateThumb: '.$params['animateThumb'].',
          currentPagerPosition: \''.$params['currentPagerPosition'].'\',
          thumbWidth: '.$params['thumbWidth'].',
          thumbContHeight: '.$params['thumbContHeight'].',
          thumbMargin: '.$params['thumbMargin'].',
          autoplay: '.$params['autoplay'].',
          pause: '.$params['pause'].',
          progressBar: '.$params['progressBar'].',
          autoplayControls: '.$params['autoplayControls'].',
          fullScreen: '.$params['fullScreen'].',
          zoom: '.$params['zoom'].'
        });
      });
    }
    if ($galleryOnlyImage.length) {
      $galleryOnlyImage.each(function () {
        $(this).lightGallery({
          selector: \'this\',
          mode: \''.$params['mode'].'\',
          cssEasing: \''.$params['cssEasing'].'\',
          speed: '.$params['speed'].',
          height: \''.$params['height'].'\',
          width: \''.$params['width'].'\',
          addClass: \''.$params['addClass'].'\',
          startClass: \''.$params['startClass'].'\',
          backdropDuration: '.$params['backdropDuration'].',
          hideBarsDelay: '.$params['hideBarsDelay'].',
          useLeft: '.$params['useLeft'].',
          closable: '.$params['closable'].',
          escKey: '.$params['escKey'].',
          keyPress: '.$params['keyPress'].',
          download: '.$params['download'].',
          counter: '.$params['counter'].',
          enableDrag: '.$params['enableDrag'].',
          enableTouch: '.$params['enableTouch'].',
          fullScreen: '.$params['fullScreen'].',
          zoom: '.$params['zoom'].'
        });
      });
    }
  });
  ';
  fwrite($fjs, $fjs_content);
  fclose($fjs);
}

function lightbox_config() {
  global $params, $lbp_def_params, $thisfile_lightbox, $lightbox_settings_file;
  $success=$error=null;

  // submitted form
  if (isset($_POST['save'])) {

    foreach (array('speed','backdropDuration','hideBarsDelay','thumbWidth','thumbContHeight','thumbMargin','pause') as $name) {
      if (isset($_POST[$name]) && is_numeric($_POST[$name])) $params[$name] = $_POST[$name];
    }
    foreach (array('classImageThumbnail','mode','cssEasing','height','width','addClass','startClass','currentPagerPosition') as $name) {
      if (isset($_POST[$name]) && is_string($_POST[$name])) $params[$name] = $_POST[$name];
    }
    foreach (array('useLeft','closable','loop','escKey','keyPress','controls','slideEndAnimatoin','hideControlOnEnd','download','counter','enableDrag','enableTouch','pager','thumbnail','showThumbByDefault','animateThumb','autoplay','progressBar','autoplayControls','fullScreen','zoom') as $name) {
      if (isset($_POST[$name])) $params[$name] = 'true'; else $params[$name] = 'false';
    }

    $data = @new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><settings></settings>');
    foreach($params as $name => $value){
      $data->addChild($name)->addCData((string) $value);
    }
    XMLsave($data, $lightbox_settings_file);

    lightbox_creat_js();

    if (!$data->asXML($lightbox_settings_file)) {
      $error = i18n_r('CHMOD_ERROR');
    } else {
      $success = i18n_r('SETTINGS_UPDATED');
    }
  } else if (isset($_POST['reset'])) {
    foreach ($lbp_def_params as $key => $value) {
      $params[$key] = $value;
    }

    $success = i18n_r($thisfile_lightbox.'/RESET_INFORMATION');
  }

  $view = @$_REQUEST['view'];
  if (!$view) $view = 'usage';
  $link = "load.php?id=".$thisfile_lightbox;
?>
  <h3 class="floated" style="float:left"><?php echo i18n_r($thisfile_lightbox.'/LIGHTBOX_TITLE'); ?></h3>
	<div class="edit-nav" >
      <p>
        <a href="<?php echo $link; ?>&view=usage" <?php echo $view=='usage' ? 'class="current"' : ''; ?> ><?php echo i18n_r($thisfile_lightbox.'/BUTTON_USAGE'); ?></a>
        <a href="<?php echo $link; ?>&view=settings" <?php echo $view=='settings' ? 'class="current"' : ''; ?> ><?php echo i18n_r($thisfile_lightbox.'/BUTTON_SETTINGS'); ?></a>
      </p>
      <div class="clear" ></div>
    </div>
  <?php
  if ($success) {
    echo '<p style="background:#fffbd0;border: 1px solid #E6DB55;border-radius:2px;padding:5px 10px;color:#43a047;"><b>'. $success .'</b></p>';
  }
  if ($error) {
    echo '<p style="background:#fffbd0;border: 1px solid #E6DB55;border-radius:2px;padding:5px 10px;color:#e53935;"><b>'. $error .'</b></p>';
  }
  if ($view == 'settings') { ?>
  <form method="post" action="<?php	echo $_SERVER ['REQUEST_URI']?>" style="clear:both">
    <table id="editsearch" class="edittable highlight">
      <tbody>
        <tr><td style="font-size:14px;vertical-align:middle;" colspan="2"><strong><?php i18n($thisfile_lightbox.'/TITLE_BLOCK1'); ?></strong></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/CLASSIMAGETHUMBNAIL'); ?></td><td><input type="text" name="classImageThumbnail" value="<?php echo htmlspecialchars(@$params['classImageThumbnail']); ?>" style="width:7em" class="text"/></td></tr>
        <tr>
          <td><?php i18n($thisfile_lightbox.'/MODE'); ?></td>
          <td>
            <select name="mode" style="width:8em" class="text">
              <option value="lg-slide" <?php if ($params['mode'] == 'lg-slide') echo 'selected="selected"'; ?> >lg-slide</option>
              <option value="lg-fade" <?php if ($params['mode'] == 'lg-fade') echo 'selected="selected"'; ?> >lg-fade</option>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php i18n($thisfile_lightbox.'/CSSEASING'); ?></td>
          <td>
            <select name="cssEasing" style="width:8em" class="text">
              <option value="ease" <?php if ($params['cssEasing'] == 'ease') echo 'selected="selected"'; ?> >ease</option>
              <option value="ease-in" <?php if ($params['cssEasing'] == 'ease-in') echo 'selected="selected"'; ?> >ease-in</option>
              <option value="ease-out" <?php if ($params['cssEasing'] == 'ease-out') echo 'selected="selected"'; ?> >ease-out</option>
              <option value="ease-in-out" <?php if ($params['cssEasing'] == 'ease-in-out') echo 'selected="selected"'; ?> >ease-in-out</option>
              <option value="linear" <?php if ($params['cssEasing'] == 'linear') echo 'selected="selected"'; ?> >linear</option>
            </select>
          </td>
        </tr>
        <tr><td style="width:85%;vertical-align:middle;"><?php i18n($thisfile_lightbox.'/SPEED'); ?></td><td><input type="text" name="speed" value="<?php echo htmlspecialchars(@$params['speed']); ?>" style="width:3em" class="text"/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/WIDTH'); ?></td><td><input type="text" name="width" value="<?php echo htmlspecialchars(@$params['width']); ?>" style="width:3em" class="text"/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/HEIGHT'); ?></td><td><input type="text" name="height" value="<?php echo htmlspecialchars(@$params['height']); ?>" style="width:3em" class="text"/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/ADDCLASS'); ?></td><td><input type="text" name="addClass" value="<?php echo htmlspecialchars(@$params['addClass']); ?>" style="width:7em" class="text"/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/STARTCLASS'); ?></td><td><input type="text" name="startClass" value="<?php echo htmlspecialchars(@$params['startClass']); ?>" style="width:7em" class="text"/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/BACKDROPDURATION'); ?></td><td><input type="text" name="backdropDuration" value="<?php echo @$params['backdropDuration']; ?>" style="width:3em" class="text"/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/HIDEBARSDELAY'); ?></td><td><input type="text" name="hideBarsDelay" value="<?php echo @$params['hideBarsDelay']; ?>" style="width:3em" class="text"/></td></tr>
        <tr><td style="font-size:14px;vertical-align:middle;" colspan="2"><strong><?php i18n($thisfile_lightbox.'/TITLE_BLOCK2'); ?></strong></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/USELEFT'); ?></td><td><input type="checkbox" name="useLeft" value="on" <?php echo @$params['useLeft']=='true' ? 'checked="checked"' : ''; ?>/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/CLOSABLE'); ?></td><td><input type="checkbox" name="closable" value="on" <?php echo @$params['closable']=='true' ? 'checked="checked"' : ''; ?>/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/LOOP'); ?></td><td><input type="checkbox" name="loop" value="on" <?php echo @$params['loop']=='true' ? 'checked="checked"' : ''; ?>/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/ESCKEY'); ?></td><td><input type="checkbox" name="escKey" value="on" <?php echo @$params['escKey']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/KEYPRESS'); ?></td><td><input type="checkbox" name="keyPress" value="on" <?php echo @$params['keyPress']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/CONTROLS'); ?></td><td><input type="checkbox" name="controls" value="on" <?php echo @$params['controls']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/SLIDEENDANIMATOIN'); ?></td><td><input type="checkbox" name="slideEndAnimatoin" value="on" <?php echo @$params['slideEndAnimatoin']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/HIDECONTROLONEND'); ?></td><td><input type="checkbox" name="hideControlOnEnd" value="on" <?php echo @$params['hideControlOnEnd']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/DOWNLOAD'); ?></td><td><input type="checkbox" name="download" value="on" <?php echo @$params['download']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/COUNTER'); ?></td><td><input type="checkbox" name="counter" value="on" <?php echo @$params['counter']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/ENABLEDRAG'); ?></td><td><input type="checkbox" name="enableDrag" value="on" <?php echo @$params['enableDrag']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/ENABLETOUCH'); ?></td><td><input type="checkbox" name="enableTouch" value="on" <?php echo @$params['enableTouch']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="font-size:14px;vertical-align:middle;" colspan="2"><strong><?php i18n($thisfile_lightbox.'/TITLE_BLOCK3'); ?></strong></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/THUMBNAIL'); ?></td><td><input type="checkbox" name="thumbnail" value="on" <?php echo @$params['thumbnail']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/PAGER'); ?></td><td><input type="checkbox" name="pager" value="on" <?php echo @$params['pager']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/SHOWTHUMBBYDEFAULT'); ?></td><td><input type="checkbox" name="showThumbByDefault" value="on" <?php echo @$params['showThumbByDefault']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/ANIMATETHUMB'); ?></td><td><input type="checkbox" name="animateThumb" value="on" <?php echo @$params['animateThumb']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr>
          <td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/CURRENTPAGERPOSITION'); ?></td>
          <td>
            <select name="currentPagerPosition" style="width:8em" class="text">
              <option value="middle" <?php if ($params['currentPagerPosition'] == 'middle') echo 'selected="selected"'; ?> >middle</option>
              <option value="left" <?php if ($params['currentPagerPosition'] == 'left') echo 'selected="selected"'; ?> >left</option>
              <option value="right" <?php if ($params['currentPagerPosition'] == 'right') echo 'selected="selected"'; ?> >right</option>
            </select>
          </td>
        </tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/THUMBWIDTH'); ?></td><td><input type="text" name="thumbWidth" value="<?php echo htmlspecialchars(@$params['thumbWidth']); ?>" style="width:3em" class="text"/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/THUMBCONTHEIGHT'); ?></td><td><input type="text" name="thumbContHeight" value="<?php echo htmlspecialchars(@$params['thumbContHeight']); ?>" style="width:3em" class="text"/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/THUMBMARGIN'); ?></td><td><input type="text" name="thumbMargin" value="<?php echo htmlspecialchars(@$params['thumbMargin']); ?>" style="width:3em" class="text"/></td></tr>
        <tr><td style="font-size:14px;vertical-align:middle;" colspan="2"><strong><?php i18n($thisfile_lightbox.'/TITLE_BLOCK4'); ?></strong></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/AUTOPLAY'); ?></td><td><input type="checkbox" name="autoplay" value="on" <?php echo @$params['autoplay']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/PAUSE'); ?></td><td><input type="text" name="pause" value="<?php echo htmlspecialchars(@$params['pause']); ?>" style="width:3em" class="text"/></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/PROGRESSBAR'); ?></td><td><input type="checkbox" name="progressBar" value="on" <?php echo @$params['progressBar']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/AUTOPLAYCONTROLS'); ?></td><td><input type="checkbox" name="autoplayControls" value="on" <?php echo @$params['autoplayControls']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="font-size:14px;vertical-align:middle;" colspan="2"><strong><?php i18n($thisfile_lightbox.'/TITLE_BLOCK5'); ?></strong></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/FULLSCREEN'); ?></td><td><input type="checkbox" name="fullScreen" value="on" <?php echo @$params['fullScreen']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>
        <tr><td style="vertical-align:middle;"><?php i18n($thisfile_lightbox.'/ZOOM'); ?></td><td><input type="checkbox" name="zoom" value="on" <?php echo @$params['zoom']=='true' ? 'checked="checked"' : ''; ?>/></td><td></td></tr>

      </tbody>
    </table>
    <input type="submit" name="save" value="<?php i18n($thisfile_lightbox.'/SAVE_CONFIGURATION'); ?>" class="submit"/>
    <input type="submit" name="reset" value="<?php i18n($thisfile_lightbox.'/RESET_CONFIGURATION'); ?>" class="submit"/>
  </form>

  <?php } else { ?>
    <p style="font-size:14px;"><strong><?php i18n($thisfile_lightbox.'/LIGHTBOX_DESC'); ?></strong></p>
    <p style="margin-bottom:1em;"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK1'); ?></p>
    <p style="margin-bottom:1em;"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK2'); ?></p>
    <code style="display:block;padding-left:2em;margin-bottom:1em;">&lt;?php get_header(); ?&gt;</code>
    <p style="margin-bottom:1em;"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK3'); ?></p>
    <code style="display:block;padding-left:2em;margin-bottom:1em;">&lt;?php get_footer(); ?&gt;</code>
    <p style="margin-bottom:1em;"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK4'); ?> <code>&lt;/body&gt;</code>.</p>
    <p style="margin-bottom:1em;"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK5'); ?></p>
    <p style="margin-bottom:1em;"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK6'); ?></p>
    <p style="margin-bottom:1em;"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK7'); ?> - <i style="font-weight:bold">nolb</i>.</p>
    <p style="margin-bottom:1em;"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK8'); ?> - <i style="font-weight:bold">fixed-size</i>.</p>
    <p style="margin-bottom:3em;"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK9'); ?> <a href="http://sachinchoolur.github.io/lightGallery/" target="_blank">jQuery lightgallery</a>.</p>
    <p style="margin-bottom:1em;text-align:center;"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK10'); ?>: <a href="https://money.yandex.ru/to/41001747758246" target="_blank"><?php i18n($thisfile_lightbox.'/USAGE_BLOCK11'); ?></a>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="https://www.paypal.me/polnikov" target="_blank">PayPal</a></p>
  <?php }
}

  function del_p_of_img ($content) {
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
  }

  function lightbox_show_css() {
    global $plugin_path, $version;
    echo "\n<link rel=\"stylesheet\" href=\"" . $plugin_path . "css/lightbox-plugin.css?v=" . $version . "\">\n";
  }

  function lightbox_show_js() {
    global $plugin_path, $params, $version;
    echo '
    <script src="' . $plugin_path . 'js/lightbox-plugin.js?v=' . $version . '"></script>
    <script src="' . $plugin_path . 'js/lightbox_config.js?v=' . $version . '"></script>
    ';
  }
?>

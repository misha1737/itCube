
<meta charset="utf-8">

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="ua" > <!--<![endif]-->
<head>
<meta charset="utf-8">
  <title><?php get_page_clean_title(); ?> - <?php get_site_name(); ?></title>
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link href="<?php get_theme_url(); ?>/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php get_theme_url(); ?>/build/css/application.css?v=<?php echo get_site_version(); ?>" rel="stylesheet">


	   
	<!--[if lt IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]--> 
	
	<!--[if lt IE 7 ]>
    <script src="<?php get_theme_url(); ?>/assets/js/dd_belatedpng.js"></script>
    <script> DD_belatedPNG.fix('img, .png_bg'); //fix any <img> or .png_bg background-images </script>
  <![endif]-->

	<?php get_i18n_header(); ?>

</head> 
<body id="<?php get_page_slug(); ?>" >
	<!-- site header -->
	<header>

<div class=" head ">
				
				<header>


					<div id="mobileMenu">
						<span class="glyphicon glyphicon-menu-hamburger"></span>
					</div>
					<div class="modalMenu disablet">
							<?php get_i18n_navigation(return_page_slug(),0,99, I18N_SHOW_MENU); ?>


					</div>

				<div class="container relbox">
					<div class="row"></div>
					<div class="col-sm-6 col-md-4">
						<a href="<?php get_site_url(); ?>" class="logo1" ><img src="images/logoIT.svg" alt="logo" class="img-responsive aboutImg"></a>
						<h4> smart IT-рішення</h4>
							
								<div class="phone contactsMobile"><img src="images/phoneIT.svg" alt=""> <h5>+38 044 733 73 73</h5></div>
								<div class="mail contactsMobile"><a href="mailto:Polyanovsky@it-cube.com.ua"><img src="images/mailIT.svg" alt=""><a/>
									<div class="faceBook"><a href="#"><img src="images/faceBookIT.svg" alt=""></a></div>
						<div class="youtube"><a href="#"><img src="images/youtubeIT.svg" alt=""></a></div>
								</div>
								
								
					</div>
					<div class="contacts ">
						<div class="phone"><img src="images/phoneIT.svg" alt=""> <h5>+38 044 733 73 73</h5></div>
						<div class="mail"><a href="mailto:Polyanovsky@it-cube.com.ua"><img src="images/mailIT.svg" alt=""></a></div>
						<div class="faceBook"><a href="#"><img src="images/faceBookIT.svg" alt=""></a></div>
						<div class="youtube"><a href="#"><img src="images/youtubeIT.svg" alt=""></a></div>
					</div>
					<div class="languageBlock">
						<div class="language-item l_en">
							<a href="<?php echo htmlspecialchars(return_i18n_setlang_url('en'));?>">eng
							</a>
							</div>
							<div class="language-item l_ru">
							<a href="<?php echo htmlspecialchars(return_i18n_setlang_url('ru'));?>">ru
							</a>
							</div>
							<div class="language-item l_ua">
							<a href="<?php echo htmlspecialchars(return_i18n_setlang_url('ua'));?>">ua	
							</a>
							</div>
						</div>	
					</div>	
					</div>	
					<div class="headBaner">
						<h1>SMART IT<br> РІШЕННЯ</h1>
						<p>Нам цікаво реалізовувати нові проекти, вирішувати
складні задачі в сфері інформаційних технологій.<br><br>
Ми виконали достатньо велику кількість замовлень 
та підтримуємо тривалі відносини у співпраці з нашими клієнтами.</p>
						<img src="images/Header_site.jpg" alt="" class="img-responsive aboutImg pcH">
						<img src="images/mobile.jpg" alt="" class="img-responsive aboutImg mobileH">
						<img src="images/mobile.jpg" alt="" class="img-responsive aboutImg mobileHH" style="opacity: 0">
					</div>
					<ul class="menu">
				<?php get_i18n_navigation(return_page_slug(),0,99, I18N_SHOW_MENU); ?>
			</ul>
			</div>

				</header>
			
		</div>


			

	

		
			
  </header>
<!DOCTYPE html>
<html lang="en-US">
<head>

	<!-- Meta Tags -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>||||||</title>   

	<meta name="description" content="Insert Your Site Description" /> 

	<!-- Mobile Specifics -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="HandheldFriendly" content="true"/>
	<meta name="MobileOptimized" content="320"/>   

	<!-- Bootstrap -->
	<link href="_include/css/bootstrap.min.css" rel="stylesheet">

	<!-- Main Style -->
	<link href="_include/css/main.css" rel="stylesheet">

	<!-- Supersized -->
	<link href="_include/css/supersized.css" rel="stylesheet">
	<link href="_include/css/supersized.shutter.css" rel="stylesheet">

	<!-- FancyBox -->
	<link href="_include/css/fancybox/jquery.fancybox.css" rel="stylesheet">

	<!-- Font Icons -->
	<link href="_include/css/fonts.css" rel="stylesheet">

	<!-- Shortcodes -->
	<link href="_include/css/shortcodes.css" rel="stylesheet">

	<!-- Responsive -->
	<link href="_include/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="_include/css/responsive.css" rel="stylesheet">

	<!-- Supersized -->
	<link href="_include/css/supersized.css" rel="stylesheet">
	<link href="_include/css/supersized.shutter.css" rel="stylesheet">

	<!-- Google Font -->
	<link href='_include/css/googleapis.css' rel='stylesheet' type='text/css'>

	<!-- Fav Icon -->
	<link rel="shortcut icon" href="#">

	<link rel="apple-touch-icon" href="#">
	<link rel="apple-touch-icon" sizes="114x114" href="#">
	<link rel="apple-touch-icon" sizes="72x72" href="#">
	<link rel="apple-touch-icon" sizes="144x144" href="#">

	<!-- Modernizr -->
	<script src="_include/js/modernizr.js"></script>

	<!-- Analytics -->
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'Insert Your Code']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
	<!-- End Analytics -->

</head>

<body>

	<!-- This section is for Splash Screen -->
	<div class="ole">
	<section id="jSplash">
		<div id="circle"></div>
	</section>
	</div>
	<!-- End of Splash Screen -->

	<!-- Homepage Slider -->
	<div id="home-slider">	
	    <div class="overlay"></div> <!-- затемняет картинки на главной странице -->
		
		<!-- Надпись на главной по средине -->
		<div class="slider-text">
	    	<div id="slidecaption"></div>
	    </div>
		
		<div class="control-nav">
	        <a id="prevslide" class="load-item"><i class="font-icon-arrow-simple-left"></i></a>
	        <a id="nextslide" class="load-item"><i class="font-icon-arrow-simple-right"></i></a>
	        <ul id="slide-list"></ul>
	        
	        <a id="nextsection" href="#work"><i class="font-icon-arrow-simple-down"></i></a>
	    </div>
	</div>
	<!-- End Homepage Slider -->

	<!-- Header -->
	<header>
	    <div class="sticky-nav">
	    	<a id="mobile-nav" class="menu-nav" href="#menu-nav"></a>
	        
	        <div id="logo">
	        	<a id="goUp" href="#home-slider" title="Начало"></a>
	        </div>
	        
	        <nav id="menu">
	        	<ul id="menu-nav">
	            	<li class="current"><a href="#home-slider">Главная</a></li>
	                <li><a href="#work">Портфолио</a></li>
	                <li><a href="#about">Услуги</a></li>
	                <li><a href="#contact">Контакты</a></li>
					<!-- <li><a href="shortcodes.html" class="external">Shortcodes</a></li> -->
	            </ul>
	        </nav>
	        
	    </div>
	</header>
	<!-- End Header -->

	<!-- Our Work Section -->
	<div id="work" class="page">
		<div class="container">
	    	<!-- Title Page -->
	        <div class="row">
	            <div class="span12">
	                <div class="title-page">
	                    <h2 class="title">Примеры работ</h2>
	                    <h3 class="title-description">Больше фотографий в <a href="#">группе</a>.</h3>
	                </div>
	            </div>
	        </div>
	        <!-- End Title Page -->
	        
	        <!-- Portfolio Projects -->
	        <div class="row">

	        	<div class="span3">
	            	<!-- Filter -->
	                <nav id="options" class="work-nav">
	                    <ul id="filters" class="option-set" data-option-key="filter">
	                    	<li class="type-work">Название альбома</li>
	                        <li><a href="#filter" data-option-value="*" class="selected">Все фотографии</a></li>
	                        @if(isset($alboms) && count($alboms))
		                        @foreach($alboms as $albom)
		                        	<li><a href="#filter" data-option-value=".{{ $albom->id }}">{{ $albom->name }}</a></li>
		                        @endforeach
		                    @endif
	                    </ul>
	                </nav>
	                <!-- End Filter -->
	            </div>
	            
	            <div class="span9">
	            	<div class="row">
	                	<section id="projects">
	                    	<ul id="thumbs">

	                    		@if(isset($photos) && count($photos))
	                    			@foreach($photos as $photo)
	                    				<!-- Item Project and Filter Name -->
			                        	<li class="item-thumbs span3 {{ $photo->id_albom }}">
			                            	<!-- Fancybox - Gallery Enabled - Title - Full Image -->
			                            	<a class="hover-wrap fancybox" data-fancybox-group="gallery" title="{{ $photo->title }}" href="{{ $photo->reference_img }}">
			                                	<span class="overlay-img"></span>
			                                    <span class="overlay-img-thumb font-icon-plus"></span>
			                                </a>
			                                <!-- Thumb Image and Description -->
			                                <img src="{{ $photo->reference_img }}" alt="{{ $photo->description }}">
			                            </li>
			                        	<!-- End Item Project -->
	                    			@endforeach
	                    		@endif

	                        </ul>
	                    </section>
	                    <div align="right"><a id="btn-more" type="button" class="btn btn-danger">More</a></div>
	            	</div>
	            </div>

	        </div>
	        <!-- End Portfolio Projects -->
	    </div>
	</div>
	<!-- End Our Work Section -->

	<!-- About Section -->
	<div id="about" class="page-alternate">
		<div class="container">
		    <!-- Title Page -->
		    <div class="row">
		        <div class="span12">
		            <div class="title-page">
		                <h2 class="title">About Us</h2>
		                <h3 class="title-description">Learn About our Team &amp; Culture.</h3>
		            </div>
		        </div>
		    </div>
		    <!-- End Title Page -->
		    
		    <!-- People -->
		    <div class="row">

		    	<div class="span6 profile">
		        	<div class="image-wrap">
		                <!-- <div class="hover-wrap">
		                    <span class="overlay-img"></span>
		                    <span class="overlay-text-thumb">CTO/Founder</span>
		                </div> -->
		                <img src="_include/img/profile/profile-02.jpg" alt="">
		            </div>
		        </div>

		        <div class="span4 profile">
			        <h3 class="profile-name">John Doe</h3>
		            <p class="profile-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac augue at erat <a href="#">hendrerit dictum</a>. 
		            Praesent porta, purus eget sagittis imperdiet, nulla mi ullamcorper metus, id hendrerit metus diam vitae est. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
		            
		            <div class="social">
		            	<ul class="social-icons">
		                	<li><a href="#"><i class="font-icon-social-evernote"></i></a></li>
		                </ul>
		            </div>
		        </div>

		    </div>
		    <!-- End People -->
		</div>
	</div>
	<!-- End About Section -->


	<!-- Contact Section -->
	<div id="contact" class="page">
		<div class="container">
		    <!-- Title Page -->
		    <div class="row">
		        <div class="span12">
		            <div class="title-page">
		                <h2 class="title">Контактная информация</h2>
		            </div>
		        </div>
		    </div>
		    <!-- End Title Page -->
		    
		    <!-- Contact Form -->
		    <div class="row">	        
		        <div class="span12">
		        	<div class="contact-details">
			        	<div class="title-page">
			                <ul>
			                    <li><a href="#">hello@brushed.com</a></li>
			                    <li>(916) 375-2525</li>
			                    <li>
			                        Brushed Studio
			                        <br>
			                        5240 Vanish Island. 105
			                        <br>
			                        Unknow
			                    </li>
			                </ul>
			            </div>
			        </div>
		        </div>
		    </div>
		    <!-- End Contact Form -->

		    <!-- Socialize -->
			<div id="social-area">
		    	<div class="row">
	                <nav id="social">
	                    <ul>
	                        <li><a href="#" title="Follow Me on Twitter" target="_blank"><span class="font-icon-social-twitter"></span></a></li>
	                        <li><a href="#" title="Follow Me on Dribbble" target="_blank"><span class="font-icon-social-dribbble"></span></a></li>
	                        <li><a href="#" title="Follow Me on Forrst" target="_blank"><span class="font-icon-social-forrst"></span></a></li>
	                        <li><a href="#" title="Follow Me on Behance" target="_blank"><span class="font-icon-social-behance"></span></a></li>
	                        <li><a href="#" title="Follow Me on Facebook" target="_blank"><span class="font-icon-social-facebook"></span></a></li>
	                        <li><a href="#" title="Follow Me on Google Plus" target="_blank"><span class="font-icon-social-google-plus"></span></a></li>
	                        <li><a href="#" title="Follow Me on LinkedIn" target="_blank"><span class="font-icon-social-linkedin"></span></a></li>
	                        <li><a href="#" title="Follow Me on ThemeForest" target="_blank"><span class="font-icon-social-envato"></span></a></li>
	                        <li><a href="#" title="Follow Me on Zerply" target="_blank"><span class="font-icon-social-zerply"></span></a></li>
	                    </ul>
	                </nav>
		        </div>
			</div>
			<!-- End Socialize -->
		</div>
	</div>
	<!-- End Contact Section -->	

	<!-- Footer -->
	<footer>
		<p class="credits">&copy;2013 Brushed. <a href="http://themes.alessioatzeni.com/html/brushed/" title="Brushed | Responsive One Page Template">Brushed Template</a> by <a href="http://www.alessioatzeni.com/" title="Alessio Atzeni | Web Designer &amp; Front-end Developer">Alessio Atzeni</a> & <a href="http://lab.yurbasik.org.ua/">LAB</a></p>
	</footer>
	<!-- End Footer -->

	<!-- Back To Top -->
	<a id="back-to-top" href="#">
		<i class="font-icon-arrow-simple-up"></i>
	</a>
	<!-- End Back to Top -->


	<!-- Js -->
	<script src="_include/js/ajax.googleapis.com.js"></script> <!-- jQuery Core -->
	<script src="_include/js/bootstrap.min.js"></script> <!-- Bootstrap -->
	<script src="_include/js/supersized.3.2.7.min.js"></script> <!-- Slider -->
	<script src="_include/js/waypoints.js"></script> <!-- WayPoints -->
	<script src="_include/js/waypoints-sticky.js"></script> <!-- Waypoints for Header -->
	<script src="_include/js/jquery.isotope.js"></script> <!-- Isotope Filter -->
	<script src="_include/js/jquery.fancybox.pack.js"></script> <!-- Fancybox -->
	<script src="_include/js/jquery.fancybox-media.js"></script> <!-- Fancybox for Media -->
	<script src="_include/js/jquery.tweet.js"></script> <!-- Tweet -->
	<script src="_include/js/plugins.js"></script> <!-- Contains: jPreloader, jQuery Easing, jQuery ScrollTo, jQuery One Page Navi -->
	<script src="_include/js/main.js"></script> <!-- Default JS -->
	<script type="text/javascript">
		function onAlbumChanged(cur_album) {
			var view_photos, limit = 0;
			if(cur_album != '*') {
				view_photos = $('#thumbs .' + cur_album).length;
				limit = alboms[cur_album];
			} else {
				view_photos = $('#thumbs .item-thumbs').length;
				for(var id in alboms){
					limit += alboms[id];
				}
			}
			if(view_photos >= limit) {
				$("#btn-more").hide();
			} else {
				$("#btn-more").show();
			}
		}

		function get_more_photo(albom_id, limit){
			var offset = $('#thumbs .' + albom_id).length;
			$.post("/more-photo", {aid: albom_id, offset: offset, limit:limit}, function(data){// функция обрабатывает ответ
				data.forEach(function(photo) {
					var $item = $('<li class="item-thumbs span3 '+photo.id_albom+'">\
		                            	<a class="hover-wrap fancybox" data-fancybox-group="gallery" title="'+photo.title+'" href="'+photo.ref_img+'">\
		                                	<span class="overlay-img"></span>\
		                                    <span class="overlay-img-thumb font-icon-plus"></span>\
		                                </a>\
		                                <img src="'+photo.ref_img+'" alt="'+photo.desc+'">\
		                            </li>');

					$("#thumbs").isotope('insert', $item);
				});
				onAlbumChanged(cur_album);
				BRUSHED.nav();
				BRUSHED.mobileNav();
				BRUSHED.listenerMenu();
				BRUSHED.menu();
				BRUSHED.goSection();
				BRUSHED.goUp();
				BRUSHED.filter();
				BRUSHED.fancyBox();
				BRUSHED.contactForm();
				//1 BRUSHED.tweetFeed();
				BRUSHED.scrollToTop();
				BRUSHED.utils();
				BRUSHED.accordion();
				BRUSHED.toggle();
				BRUSHED.toolTip();
			});
		}

		
		var alboms = new Array();
		@foreach($alboms as $albom)
			alboms[{{ $albom->id }}] = {{ $albom->count }};
	    @endforeach
		onAlbumChanged('*');// При загрузки страницы проверяем нужна ли кнопка More

		$("#btn-more").click(function(){
			$("#btn-more").hide();
			if(cur_album == '*') {
				for(var id in alboms){
					get_more_photo(id, 3);
				}
			}
			else {
				get_more_photo(cur_album, 3);
			}
		});
	</script>
	<!-- End Js -->
</body>
</html>
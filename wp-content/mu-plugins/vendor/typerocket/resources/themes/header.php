<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

?>

<!DOCTYPE html>
<html lang="<?php echo get_bloginfo('language'); ?>" 
      dir="<?php if( is_rtl() ) { echo 'rtl'; } else { echo 'ltr'; } ?>"
>
<head>
    <meta charset="<?php echo get_bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title> -->
        <?php
            // if( get_bloginfo('description') ) {
            //     echo get_bloginfo('name') . ' | ' . get_bloginfo('description');
            // } else {
            //     echo get_bloginfo('name');
            // }
        ?>
    <!-- </title> -->
    <?php wp_head(); ?>
</head>
<body <?php body_class('overflow'); ?> >

  <!-- Header Start -->
  <header id="header" class="container-fluid header">
    <div class="container">
      <div class="wsmenucontainer clearfix">
        <div class="overlapblackbg"></div>

        <!--Mobile Menu HTML Code Start-->
        <div class="offheader">  
          <a id="wsnavtoggle" class="animated-arrow"><span></span></a>
          <div class="logo"><a href="#"><img src="<?php echo TYPEROCKET_DIR_URL; ?>resources/assets/img/global/logo.jpeg" alt="" ></a></div>
          <div class="menues">
            <a href="<?php echo get_home_url().'/cart'; ?>" class="cart">
              <i class="las la-shopping-cart"></i>
            </a>
            <a href="<?php echo get_home_url().'/account'; ?>" class="account">
              <i class="las la-user-circle"></i>
            </a>
          </div>
          <!-- <a id="wsnavtoggle02" class="opener-arrow"><i class="fa fa-info-circle" aria-hidden="true"></i> </a> -->
        </div>
        <!--Mobile Menu HTML Code End-->

        <!--Desktop Menu HTML Code Start-->
        <div class="header">
          <!--Main Menu HTML Code-->
          <nav class="wsmenu clearfix">
            <ul class="mobile-sub wsmenu-list clearfix">
              <li><a href="#" class="active"><span class="hometext">صفحه نخست</span></a></li>
              <li><a href="#">آخرین گالری‌ها<span class="arrow"></span></a></li>
              <li><a href="#">گالری‌های منتخب<span class="arrow"></span></a></li>
              <li><a href="#">دسته بندی‌ها<span class="arrow"></span></a>
                <ul class="wsmenu-submenu">
                  <li><a href="#">لیگ برتر و جام حذفی فوتبال ایران</a></li>
                  <li><a href="#">لیگ آزادگان</a></li>
                  <li><a href="#">فوتبال ملی</a></li>
                  <li><a href="#">سایر رشته‌ها</a>
                    <ul class="wsmenu-submenu-sub">
                      <li><a href="#">رشته ورزشی 1</a></li>
                      <li><a href="#">رشته ورزشی 2</a></li>
                      <li><a href="#">رشته ورزشی 3</a></li>
                      <li><a href="#">رشته ورزشی 4</a>
                      <ul class="wsmenu-submenu-sub-sub">
                      <li><a href="#">رشته ورزشی 1</a></li>
                      <li><a href="#">رشته ورزشی 2</a></li>
                      <li><a href="#">رشته ورزشی 3</a></li>
                      <li><a href="#">رشته ورزشی 4</a></li>
                    </ul>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
          <!--Menu HTML Code-->
        </div>
        <!--Desktop Menu HTML Code End-->

        <!-- <div class="wsmenu02 clearfix">
          <div class="wsmenutwohead clearfix">
            <a href="#" class="wsmenutwo-close"><span></span><span></span></a>
            <div class="wsmenu02-title">About Web Slide Menu</div>
          </div>
          <div class="wsmenu02text clearfix">
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. orem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaininpassages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining en the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer tondard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remainingy five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer toove centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printerfive centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remt only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printe centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer td not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, reve centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
          </div>
        
        </div> -->
      </div>
    </div>
  </header>
  <!-- Header End -->
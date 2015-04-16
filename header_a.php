<?php
    require_once("includes/config.php");
    $page = end(explode('/', $_SERVER["REQUEST_URI"]));
?>
<script>
function sticky_relocate() {
  var window_top = $(window).scrollTop();
  var div_top = $('#sticky-anchor').offset().top;
  if (window_top > div_top) {
    $('#sticky').addClass('stick');
  } else {
    $('#sticky').removeClass('stick');
  }
}

$(function() {
  $(window).scroll(sticky_relocate);
  sticky_relocate();
});
</script>

<style>
#sticky {
  width: 100%;
  background-color: #fff;
  height: 98px;
  clear:both;
}
#sticky.stick {
  position: fixed;
  top: -15px;
  
height: 98px;
  z-index: 10000;
	 box-shadow: 0px 0px 3px #000;
	 -webkit-box-shadow: 0px 0px 3px #000;
	 -moz-box-shadow: 0px 0px 3px #000;
  border-bottom: 1px solid #f0f0f0;
  -webkit-border-bottom: 1px solid #f0f0f0;
  -moz-border-bottom: 1px solid #f0f0f0;
 
}
</style>
<section id="header_con">
    <div id="sticky-anchor"></div>
    <div id="sticky">
        <header id="ewo-header">
         <div class="pagewrap" style="position:relative; width: 100%; max-width:1200px;margin:0 auto;background:#fff; z-index:9999;">
             <div class="ewo-logo floatleft">
                   <a href="<?php echo $SiteUrl;?>" ><img src="signup_images/logo.png"/></a>
              </div>
                <nav class="floatright">
                    <button type="button" class="btn" data-toggle="show">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                    </button>
                    <ol class="mobile-menu">
                            <li <?php if($page == "features.php"){?> class="active" <?php }?>><a href="features.php">FEATURES</a></li>
                            <li <?php if($page == "pricing.php"){?> class="active" <?php }?>><a href="pricing.php">PRICING</a> </li>
                            <li <?php if($page == "about.php"){?> class="active" <?php }?>><a href="about.php">ABOUT</a></li>
                            <li <?php if($page == "feature.php"){?> class="active" <?php }?>><a href="blogs">BLOG</a></li>
                            <li <?php if($page == "reseller.php"){?> class="active" <?php }?>><a href="reseller.php" class="darkgray">RESELLER?</a></li>
                            <li <?php if($page == "features.php"){?> class="active" <?php }?>><a href="freedemo.php" class="darkgray">FREE DEMO!</a></li>
                            <li <?php if($page == "features.php"){?> class="active" <?php }?>><a href="client_login.php"><img src="signup_images/user_icon.png" align="top"/> LOGIN</a></li>
                     </ol>
                    <ul class="menu">
                            <li <?php if($page == "features.php"){?> class="active" <?php }?>><a href="features.php">FEATURES</a></li>
                            <li <?php if($page == "pricing.php"){?> class="active" <?php }?>><a href="pricing.php">PRICING</a> </li>
                            <li <?php if($page == "about.php"){?> class="active" <?php }?>><a href="about.php">ABOUT</a></li>
                            <li <?php if($page == "feature.php"){?> class="active" <?php }?>><a href="blogs">BLOG</a></li>
                            <li <?php if($page == "reseller.php"){?> class="active" <?php }?>><a href="reseller.php" class="darkgray">RESELLER?</a></li>
                            <li <?php if($page == "freedemo.php"){?> class="active" <?php }?>><a href="freedemo.php" class="darkgray">FREE DEMO!</a></li>
                            <a href="client_login.php" <?php if($page == "client_login.php"){?> class="active" <?php }?>><img src="signup_images/user_icon.png" align="top" height="18"/> LOGIN</a>
                    </ul>
                </nav>
            </div>
        </header>
        <br clear="all"/>
    </div>
    <br clear="all"/>
</section>
 
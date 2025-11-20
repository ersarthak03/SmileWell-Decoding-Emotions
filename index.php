<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SmileWell:Decoding Emotions</title> 

	<!-- mobile responsive meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/responsive.css">

	<link rel="apple-touch-icon" sizes="180x180" href="images/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="images/favicons/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="images/favicons/favicon-16x16.png" sizes="16x16">

 

</head>
<body>

<div class="boxed_wrapper">



            

    </div>
</div>

<section class="theme_menu stricky">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
               
            </div>
            <div class="col-md-9 menu-column">
    <nav class="defaultmainmenu" id="main_menu">
        <ul class="defaultmainmenu-menu">
            <li><a href="index.php">Home</a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Logged-in Menu Items -->
                <li class="active"><a href="smilewell.html">Smile Tracker</a>
                </li>

                <li><a href="quiz.php">Games</a>
                    
                </li>

                <li><a href="motivation_board.php">Motivation Board</a>
                </li>

                <li><a href="article.php">Articles </a>
                </li>

                <li><a href="/community/">Community</a>
                </li>

                <li><a href="leaderboard.php">LeaderBoard</a>
                </li>

                <li><a href="profile.php">Profile</a>
                    
                </li>
                
                <li><a href="logout.php">Logout</a></li>
                
                </li>
                
                <li><a href="admin/admin_dashboard.php">Admin  Panel</a></li>
                
            <?php else: ?>
                <!-- Non-logged-in Menu Items -->
                <li><a href="login.php">Login</a></li>
                <li><a href="login.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav> 
</div>
            


        </div>
                

   </div>
</section>

 


 


<!--Start rev slider wrapper-->     
<section class="rev_slider_wrapper">
    <div id="slider1" class="rev_slider"  data-version="5.0">
        <ul>
            
            <li data-transition="fade">
                <img src="images/slider/2.gif"  alt="" width="1920" height="888" data-bgposition="top center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="1" >
                
                <div class="tp-caption  tp-resizeme" 
                    data-x="left" data-hoffset="15" 
                    data-y="top" data-voffset="350" 
                    data-transform_idle="o:1;"         
                    data-transform_in="x:[-175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0.01;s:3000;e:Power3.easeOut;" 
                    data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;" 
                    data-mask_in="x:[100%];y:0;s:inherit;e:inherit;" 
                    data-splitin="none" 
                    data-splitout="none"
                    data-responsive_offset="on"
                    data-start="700">
                    <div class="slide-content-box">
                        
                        
                    </div>
                </div>
                <div class="tp-caption tp-resizeme" 
                    data-x="left" data-hoffset="15" 
                    data-y="top" data-voffset="580" 
                    data-transform_idle="o:1;"                         
                    data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"                     
                    data-splitin="none" 
                    data-splitout="none" 
                    data-responsive_offset="on"
                    data-start="2300">
                    <div class="slide-content-box">
                        <div class="button">
                            <a class="thm-btn" href="#">read more</a>     
                        </div>
                    </div>
                </div>
                <div class="tp-caption tp-resizeme" 
                    data-x="left" data-hoffset="190" 
                    data-y="top" data-voffset="580" 
                    data-transform_idle="o:1;"                         
                    data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"                     
                    data-splitin="none" 
                    data-splitout="none" 
                    data-responsive_offset="on"
                    data-start="2600">
                    <div class="slide-content-box">
                        <div class="button">
                            <a class="thm-btn style-3" href="cause.html">our causes</a>     
                        </div>
                    </div>
                </div>
            </li>
            <li data-transition="fade">
                <img src="images/slider/1.jpg"  alt="" width="1920" height="580" data-bgposition="top center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="1" >
                
                <div class="tp-caption  tp-resizeme" 
                    data-x="center" data-hoffset="" 
                    data-y="top" data-voffset="370" 
                    data-transform_idle="o:1;"         
                    data-transform_in="x:[-175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0.01;s:3000;e:Power3.easeOut;" 
                    data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;" 
                    data-mask_in="x:[100%];y:0;s:inherit;e:inherit;" 
                    data-splitin="none" 
                    data-splitout="none"
                    data-responsive_offset="on"
                    data-start="700">
                    <div class="slide-content-box center">
                        <p><h1>A smile is a curve that sets everything straight </h1>  </p>
                       
                    </div>
                </div>
                <div class="tp-caption tp-resizeme" 
                    data-x="center" data-hoffset="-90" 
                    data-y="top" data-voffset="550" 
                    data-transform_idle="o:1;"                         
                    data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"                     
                    data-splitin="none" 
                    data-splitout="none" 
                    data-responsive_offset="on"
                    data-start="2300">
                    <div class="slide-content-box">
                        <div class="button">
                               
                        </div>
                    </div>
                </div>
                <div class="tp-caption tp-resizeme" 
                    data-x="center" data-hoffset="90" 
                    data-y="top" data-voffset="550" 
                    data-transform_idle="o:1;"                         
                    data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"                     
                    data-splitin="none" 
                    data-splitout="none" 
                    data-responsive_offset="on"
                    data-start="2600">
                    <div class="slide-content-box">
                        <div class="button">
                            <a class="thm-btn style-3" href="cause.html">our causes</a>     
                        </div>
                    </div>
                </div>
            </li>
            <li data-transition="fade">
                <img src="images/slider/3.gif"  alt="" width="1920" height="580" data-bgposition="top center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="1" >
                
                <div class="tp-caption  tp-resizeme" 
                    data-x="center" data-hoffset="200" 
                    data-y="top" data-voffset="340" 
                    data-transform_idle="o:1;"         
                    data-transform_in="x:[-175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0.01;s:3000;e:Power3.easeOut;" 
                    data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;" 
                    data-mask_in="x:[100%];y:0;s:inherit;e:inherit;" 
                    data-splitin="none" 
                    data-splitout="none"
                    data-responsive_offset="on"
                    data-start="700">
                    <div class="slide-content-box">
                        <h1>A Little Amount Of <br>Smile Save Life</h1>
                        <p>Let your smile change the world, but don’t let the world change your smile </p>
                    </div>
                </div>
                <div class="tp-caption tp-resizeme" 
                    data-x="center" data-hoffset="0" 
                    data-y="top" data-voffset="570" 
                    data-transform_idle="o:1;"                         
                    data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"                     
                    data-splitin="none" 
                    data-splitout="none" 
                    data-responsive_offset="on"
                    data-start="2300">
                    <div class="slide-content-box">
                        <div class="button">
                            <a class="thm-btn" href="#">Join With Us</a>     
                        </div>
                    </div>
                </div>
                <div class="tp-caption tp-resizeme" 
                    data-x="center" data-hoffset="190" 
                    data-y="top" data-voffset="570" 
                    data-transform_idle="o:1;"                         
                    data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"                     
                    data-splitin="none" 
                    data-splitout="none" 
                    data-responsive_offset="on"
                    data-start="2600">
                    
                </div>
            </li>
        </ul>
    </div>
</section>
<!--End rev slider wrapper--> 



<section class="about-2 sec-padd3">
    <div class="container">
        <div class="section-title center">
            <h2>Welcome to <span class="thm-color">Smile:Well</span></h2>
            <p>A Platform for decoding emotions and well being.</p>
        </div>
        <div class="row">
            <article class="col-md-4 col-sm-6 col-xs-12">
                <div class="single-item">
                    <div class="img-box"><img src="images/resource/about1.jpg" alt=""></div>
                    <div class="content">
                        <div class="clearfix">
                            <div class="icon_box"><span class="icon-people"></span></div>
                            <div class="text">
                                <h4>Decode and Enhance Your Emotions</h4>
                                <p>Smile Everyday</p>
                            </div>
                        </div>
                        <p>Our advanced AI analyzes facial expressions to help you improve emotional well-being. Track your smiles, earn rewards, and stay motivated.</p>
                    </div>
                </div>
            </article>
            <article class="col-md-4 col-sm-6 col-xs-12">
                <div class="single-item">
                    <div class="img-box"><img src="images/resource/about2.jpg" alt=""></div>
                    <div class="content">
                        <div class="clearfix">
                            <div class="icon_box"><span class="icon-animals"></span></div>
                            <div class="text">
                                <h4>Celebrate Every Smile</h4>
                                <p>Smile and make other smile</p>
                            </div>
                        </div>
                        <p>Join a community that promotes positivity. Engage in challenges, share your moments, and get insights into your emotional patterns.</p>
                    </div>
                </div>
            </article>
            <article class="col-md-4 col-sm-6 col-xs-12">
                <div class="single-item">
                    <div class="img-box"><img src="images/resource/about3.jpg" alt=""></div>
                    <div class="content">
                        <div class="clearfix">
                            <div class="icon_box"><span class="icon-nature"></span></div>
                            <div class="text">
                                <h4>Unlock the Power of Your Smile</h4>
                                <p>The world always looks brighter from behind a smile.</p>
                            </div>
                        </div>
                        <p>Analyze your emotions in real time. Participate in fun challenges, gain motivation, and track your mood changes.</p>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>














<footer class="main-footer">
    
    <!--Widgets Section-->
    <div class="widgets-section">
        <div class="container">
            <div class="row">
                <!--Big Column-->
                <article class="big-column col-md-6 col-sm-12 col-xs-12">
                    <div class="row clearfix">
                        
                        <!--Footer Column-->
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <div class="footer-widget about-column">
                                <div class="section-title">
                                    <h4>About Us</h4>
                                </div>
                                
                                <div class="text"><p>Welcome to Happy Fun Zone, where joy, creativity, and a little bit of chaos collide! We’re a team of digital dreamers who believe the internet should be as fun as a confetti cannon at a unicorn party. 🦄✨
                                    Because life’s too short for boring websites! We mix whimsy, wild animations, and happy vibes to create online experiences that make you smile, laugh, and maybe even snort a little.
                                </p> </div>
                                <div class="link"><a href="#" class="default_link">Read More <i class="fa fa-long-arrow-right"></i></a></div>
                            </div>
                        </div>
                        <!--Footer Column-->
                       
                    </div>
                </article>
                
                <!--Big Column--> 
                <article class="big-column col-md-6 col-sm-12 col-xs-12">
                    <div class="row clearfix">
                        
                        <!--Footer Column-->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="footer-widget post-column">
                                <div class="section-title">
                                    <h4>Recent Post</h4>
                                </div>
                                <div class="post-list">
                                    <div class="post">
                                        <a href="article.php"><h5>10 Ways to Turn Your Cat Into a Stand-Up Comedian</h5></a>
                                        <div class="post-info">March 27, 2025</div>
                                    </div>
                                    <div class="post">
                                        <a href="article.php"><h5>The Secret Dance Moves of Office Chairs
</h5></a>
                                        <div class="post-info">March 25, 2025</div>
                                    </div>
                                    <div class="post">
                                        <a href="article.php"><h5>Pizza Toppings That Will Make You Question Reality
</h5></a>
                                        <div class="post-info">March 26, 2025</div>
                                    </div>

                                </div>
                                
                            </div>
                        </div>
                        
                        <!--Footer Column-->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="footer-widget contact-column">
                                <div class="section-title">
                                    <h4>Get In Touch</h4>
                                </div>
                                <ul class="contact-info">
                                    <li><i class="icon-phone"></i> <span> Phone: </span>+91 9557667882<br></li>
                                    <li><i class="icon-back"></i><span>Email: </span>admin@Smilewell.software</li>
                                </ul>
                            </div>
                        </div>
                        
                        
                    </div>
                </article>
                
             </div>
         </div>
     </div>
     
</footer>


                                                                                             
    <!-- Scroll Top  -->
	<button class="scroll-top tran3s color2_bg"><span class="fa fa-angle-up"></span></button>
	<!-- preloader  -->
	<div class="preloader"></div>
    <div id="donate-popup" class="donate-popup">
    <div class="close-donate theme-btn"><span class="fa fa-close"></span></div>
    <div class="popup-inner">


    <div class="container">
        <div class="donate-form-area">
            <div class="section-title center">
                <h2>Donation Information</h2>
            </div>

            <h4>How much would you like to donate:</h4>

            <form  action="#" class="donate-form default-form">
                <ul class="chicklet-list clearfix">
                    <li>
                        <input type="radio" id="donate-amount-1" name="donate-amount" />
                        <label for="donate-amount-1" data-amount="1000" >$1000</label>
                    </li>
                    <li>
                        <input type="radio" id="donate-amount-2" name="donate-amount" checked="checked" />
                        <label for="donate-amount-2" data-amount="2000">$2000</label>
                    </li>
                    <li>
                        <input type="radio" id="donate-amount-3" name="donate-amount" />
                        <label for="donate-amount-3" data-amount="3000">$3000</label>
                    </li>
                    <li>
                        <input type="radio" id="donate-amount-4" name="donate-amount" />
                        <label for="donate-amount-4" data-amount="4000">$4000</label>
                    </li>
                    <li>
                        <input type="radio" id="donate-amount-5" name="donate-amount" />
                        <label for="donate-amount-5" data-amount="5000">$5000</label>
                    </li>
                    <li class="other-amount">

                        <div class="input-container" data-message="Every dollar you donate helps end hunger.">
                            <span>Or</span><input type="text" id="other-amount" placeholder="Other Amount"  />
                        </div>
                    </li>
                </ul>

                <h3>Donor Information</h3>

                <div class="form-bg">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            
                            <div class="form-group">
                                <p>Your Name*</p>
                                <input type="text" name="fname" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <p>Email*</p>
                                <input type="text" name="fname" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <p>Address*</p>
                                <input type="text" name="fname" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <p>Phn Num*</p>
                                <input type="text" name="fname" placeholder="">
                            </div>
                        </div>  

                    </div>
                </div>

                <ul class="payment-option">
                    <li>
                        <h4>Choose your payment method:</h4>
                    </li>
                    <li>
                        <div class="checkbox">
                            <label>
                                <input name="pay-us" type="checkbox">
                                <span>Paypal</span>
                            </label>
                        </div>
                    </li>
                    <li>
                       <div class="checkbox">
                            <label>
                                <input name="pay-us" type="checkbox">
                                <span>Offline Donation</span>
                            </label>
                        </div> 
                    </li>
                    <li>
                        <div class="checkbox">
                            <label>
                                <input name="pay-us" type="checkbox">
                                <span>Credit Card</span>
                            </label>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox">
                            <label>
                                <input name="pay-us" type="checkbox">
                                <span>Debit Card</span>
                            </label>
                        </div>
                    </li>
                </ul>

                <div class="center"><button class="thm-btn" type="submit">Donate Now</button></div>
                    
            
            </form>
        </div>
    </div>

            
        
    </div>
</div>

	<!-- jQuery -->
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/menu.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.mixitup.min.js"></script>
    <script src="js/jquery.fancybox.pack.js"></script>
    <script src="js/imagezoom.js"></script> 
    <script src="js/jquery.magnific-popup.min.js"></script> 
    <script src="js/jquery.polyglot.language.switcher.js"></script>
    <script src="js/SmoothScroll.js"></script>
    <script src="js/jquery.appear.js"></script>
    <script src="js/jquery.countTo.js"></script>
    <script src="js/validation.js"></script> 
    <script src="js/wow.js"></script> 
    <script src="js/jquery.fitvids.js"></script> 
    <script src="js/nouislider.js"></script> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
                                                                                                                                                                                                                                                                                                                  
	<!-- revolution slider js -->
    <script src="js/rev-slider/jquery.themepunch.tools.min.js"></script>
    <script src="js/rev-slider/jquery.themepunch.revolution.min.js"></script>
    <script src="js/rev-slider/revolution.extension.actions.min.js"></script>
    <script src="js/rev-slider/revolution.extension.carousel.min.js"></script>
    <script src="js/rev-slider/revolution.extension.kenburn.min.js"></script>
    <script src="js/rev-slider/revolution.extension.layeranimation.min.js"></script>
    <script src="js/rev-slider/revolution.extension.migration.min.js"></script>
    <script src="js/rev-slider/revolution.extension.navigation.min.js"></script>
    <script src="js/rev-slider/revolution.extension.parallax.min.js"></script>
    <script src="js/rev-slider/revolution.extension.slideanims.min.js"></script>
    <script src="js/rev-slider/revolution.extension.video.min.js"></script>


	<script src="js/custom.js"></script>

</div>
	
</body>
</html>                 
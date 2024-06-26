
<html>
    <head>
    <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->	
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>/assests/login/images/icons/favicon.ico"/>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assests/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assests/login/fonts/iconic/css/material-design-iconic-font.min.css">
    
    <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assests/login/css/util.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/assests/login/css/main.css">
    <!--===============================================================================================-->
    </head>
    <body>
        <div class="limiter">
            <div class="container-login100" style="background-image: url('<?php echo base_url(); ?>/assests/login/images/bg-01.jpg');">
                <div class="wrap-login100">
                    <form class="login100-form validate-form" action="<?php echo base_url();?>auth/login" method = "post">
                        <span class="login100-form-logo">
                            <i class="zmdi zmdi-landscape"></i>
                        </span>

                        <span class="login100-form-title p-b-34 p-t-27">
                            Log in
                        </span>

                        <div class="wrap-input100 validate-input" data-validate = "Enter username">
                            <input class="input100" type="text" name="identity" placeholder="Username">
                            <span class="focus-input100" data-placeholder="&#xf207;"></span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Enter password">
                            <input class="input100" type="password" name="password" placeholder="Password">
                            <span class="focus-input100" data-placeholder="&#xf191;"></span>
                        </div>

                        <div class="contact100-form-checkbox">
                            <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember" id="remember">
                            <label class="label-checkbox100" for="ckb1">
                                Remember me
                            </label>
                        </div>

                        <div class="container-login100-form-btn">
                            <button class="login100-form-btn" type="submit">
                                Login
                            </button>
                        </div>

                        <div class="text-center p-t-90">
                            <a class="txt1" href="forgot_password">
                                Forgot Password?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
	    </div>
	

	<div id="dropDownSelect1"></div>	
    <!--===============================================================================================-->
         <script src="<?php echo base_url(); ?>/assests/login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
        <script src="<?php echo base_url(); ?>/assests/login/js/main.js"></script>
    </body>
</html>
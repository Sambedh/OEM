<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo base_url('assests/admin');?>/images/favicon1.ico" type="image/ico" />

    <title>Online exam| </title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url('assests/admin');?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url('assests/admin');?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo base_url('assests/admin');?>/vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url('assests/admin');?>/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
		<link href="<?php echo base_url('assests/admin');?>/build/css/custom.min.css" rel="stylesheet">
		
		<link href="<?php echo base_url('assests/admin');?>/vendors/dropzone/dist/min/dropzone.min.css" type= 'text/css'  rel="stylesheet">

		<link href="<?php echo base_url('assests/select');?>/vanillaSelectBox.css" type= 'text/css'  rel="stylesheet">
		
		<link href="<?php echo base_url('assests/admin');?>/vendors/normalize-css/normalize.css" rel="stylesheet">

     <!-- Datatables -->
    
    <link href="<?php echo base_url('assests/admin');?>/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assests/admin');?>/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assests/admin');?>/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="top_nav">
          <div class="nav_menu">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
              <nav class="nav navbar-nav">
              <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                  <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
									<?php
									$user = $this->ion_auth->user()->row();
									if(empty($user->image)){
										$email = $_SESSION['email'];
										$default = "";
										$size = 30;
										$grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
										echo '<img src= "'.$grav_url.'" alt="">';
									} else {
										echo '<img src= "'.base_url('uploads/img/users/'.$user->image).'" alt="">';
									}
                    echo $user->first_name . $user->last_name;
                  ?>
                  </a>
                  <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
										<?php 
										if($this->ion_auth->in_group(2)) {
											echo '<a class="dropdown-item"  href="'.base_url('faculty/user_profile').'"> Profile</a>';
										}
										elseif($this->ion_auth->in_group(3)) {
											echo '<a class="dropdown-item"  href="'.base_url('student/user_profile').'"> Profile</a>';
										} 
										elseif($this->ion_auth->in_group(5)) {
											echo '<a class="dropdown-item"  href="'.base_url('admin/user_profile').'"> Profile</a>';
										} ?>
                      <!-- <a class="dropdown-item"  href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                  <a class="dropdown-item"  href="javascript:;">Help</a> -->
                    <a class="dropdown-item"  href="<?php echo base_url('Auth/logout');?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                  </div>
                </li>

                <!-- <li role="presentation" class="nav-item dropdown open">
                  <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">6</span>
                  </a>
                  <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                    <li class="nav-item">
                      <a class="dropdown-item">
                        <span class="image"><img src="<?php echo base_url('assests/admin');?>/images/user.png" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <div class="text-center">
                        <a class="dropdown-item">
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li> -->
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->
        
        <div class="col-md-3 left_col menu_fixed" >
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="" class="site_title"><i class="fa fa-institute"></i> <span>SoftWeb</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
							<?php
								$user = $this->ion_auth->user()->row();
								if(empty($user->image)){
									$email = $_SESSION['email'];
									$default = "";
									$size = 30;
									$grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
									echo '<img src= "'.$grav_url.'" class="img-circle profile_img" alt="">';
								} else {
									echo '<img src= "'.base_url('uploads/img/users/'.$user->image).'" style = "height  : 60px; width : 70px" class="img-circle profile_img" alt="">';
								}
								?>
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>
                  <?php
                    $user = $this->ion_auth->user()->row();
                    echo $user->first_name . $user->last_name;
                  ?>
                </h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url('admin');?>">Dashboard</a></li>
                      <!-- <li><a href="index2.html">Dashboard2</a></li>
                      <li><a href="index3.html">Dashboard3</a></li> -->
                    </ul>
									</li>
									<li><a href="<?php echo base_url('admin/user');?>"><i class="fa fa-users"></i>Manage users</a></li>
                  <li><a href="<?php echo base_url('admin/course');?>"><i class="fa fa-graduation-cap"></i> Manage course</a></li>
                  <li><a href="<?php echo base_url('admin/subject');?>"><i class="fa fa-book"></i> Manage subject</a></li>
                  <li><a href="<?php echo base_url('admin/exam');?>"><i class="fa fa-laptop"></i> Manage exam</a></li>
                  <li><a href="<?php echo base_url('admin/mcq');?>"><i class="fa fa-question"></i> Manage Question & answer</a></li>
                  <li><a href="<?php echo base_url('admin/result');?>"><i class="fa fa-trophy"></i> Examination Result</a></li>
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo base_url('Auth/logout');?>">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>
      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">

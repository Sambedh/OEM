
<style>
	.user-detail{
		color : #17a2b8;
		font-size: 15px;
		font-family :'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
	}
	h5{
		font-weight : bold;
	}
	.profile-img .file {
    position: relative;
    overflow: hidden;
    margin-top: -20%;
    width: 70%;
    border: none;
    border-radius: 0;
    font-size: 15px;
}
.profile_img .file input {
    position: absolute;
    opacity: 0;
	right: 0;
	top: 0;

}
</style>
<div class="page-title">
	<div class="title_left">
		<h3>User Profile</h3>
	</div>
</div>

<div class="clearfix"></div>

<div class="row">
	<div class="col-md-12 col-sm-12 ">
		<div class="x_panel">
			<div class="x_content">
				<div class="col-md-3 col-sm-3  profile_left">
					<form method = "post" enctype = 'multipart/form-data' action = '<?php echo base_url('admin/update_dp'); ?>'>
						<div class="profile_img">
							<div id="crop-avatar">
								<div class="image view view-first">
									<!-- Current avatar -->
									<?php
									if(empty($user->image)){
										$email = $user->email;
										$default = "";
										$size = 225;
										$grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
										echo '<img class="img-responsive avatar-view"  id = "profile_dp" src=" '.$grav_url.'" alt="Avatar" title="Change the avatar">';
									} else {
										echo '<img class="img-responsive avatar-view"  id = "profile_dp" src="'.base_url('uploads/img/users/'.$user->image).'" alt="Avatar"  style = "height : 225px; width : 225px" title="Change the avatar">';										
									}
									?>
									<div class="mask no-caption" style =" height: 100% ">
										<div class="tools tools-bottom"  style =" margin-top: 70%; ">
											<div class="file btn btn-xs btn-link" style =" margin: 0% ; padding : 0%  ">
												<i class="fa fa-pencil"></i>
												<input type="file" name="user_img"  accept="image/*" onchange = "change_dp(this)"/>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<br />
						<input type = 'submit' value ="Update profile pic" id = "submit_dp" class = "btn btn-success" style = "display : none">
					</form>
					<h4>Course : <span><?php echo $course->name; ?></span></h4> 
				</div>
				<div class="col-md-9 col-sm-9 ">

					<div class="profile_title">
						<div class="col-md-6">
							<h1><?php echo ucfirst($user->first_name) . ' ' . ucfirst($user->last_name);?> </h1>
							<h1 style = "color : #17a2b8; font-weight : bold" ><?php echo $this->admin_model->get_data_by_id('users',$user->reference_id)->company; ?> </h1>
							<ul class="list-unstyled user_data">
								<li>
									<i class="fa fa-map-marker user-profile-icon"></i> Nepal
								</li>
								
								<li>
									<i class="fa fa-user user-profile-icon"></i> Student
								</li>
							</ul>
						</div>
					</div>
					

					<div class="" role="tabpanel" data-example-id="togglable-tabs">
						<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
							<li role="presentation" class=""><a href="#tab_content3" id="home-tab"  role="tab"  data-toggle="tab" aria-expanded="true">Profile</a>
							</li>
							<li role="presentation" class="active"><a href="#tab_content1" role="tab" data-toggle="tab" id="profile-tab2" aria-expanded="false">Recent Activity</a>
							</li>
							<li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Projects Worked on</a>
							</li>
						</ul>
						<div id="myTabContent" class="tab-content">
							<div role="tabpanel" class="tab-pane fade" id="tab_content1" aria-labelledby="profile-tab">

								<!-- start recent activity -->
								<!-- <ul class="messages">
									<li>
										<img src="" class="avatar" alt="Avatar">
										<div class="message_date">
											<h3 class="date text-info">24</h3>
											<p class="month">May</p>
										</div>
										<div class="message_wrapper">
											<h4 class="heading">Desmond Davison</h4>
											<blockquote class="message">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth.</blockquote>
											<br />
											<p class="url">
												<span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
												<a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
											</p>
										</div>
									</li>
								</ul> -->
								<!-- end recent activity -->

							</div>
							<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

							</div>
							<div role="tabpanel" class="tab-pane active" id="tab_content3" aria-labelledby="home-tab">
								<div class = 'row'>
									<div class = "col-md-6">
										<h5>First Name</h5>
									</div>
									<div class = "col-md-6"><p class = "user-detail"><?php echo $user->first_name; ?></p></div>
								</div>
								</br>
								<div class = 'row'>
									<div class = "col-md-6">
										<h5>Last Name</h5>
									</div>
									<div class = "col-md-6"><p class = "user-detail"><?php echo $user->last_name; ?></p></div>
								</div>
								</br>
								<div class = 'row'>
									<div class = "col-md-6">
										<h5>Email</h5>
									</div>
									<div class = "col-md-6"><p class = "user-detail"><?php echo $user->email; ?></p></div>
								</div>
								</br>
								<div class = 'row'>
									<div class = "col-md-6">
										<h5>Phone</h5>
									</div>
									<div class = "col-md-6"><p class = "user-detail"><?php echo $user->phone; ?></p></div>
								</div>
								<div class = "row">
									<div class ="offset-2">
										<p class = "user-detail">Change Password ? <a href="<?php echo base_url('auth/change_password'); ?>"> Click Here </a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


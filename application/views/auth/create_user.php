
<div class="page-title">
    <div class="title_left">
        <h1><?php echo lang('index_heading');?></h1>
    </div>

    <!-- <div class="title_right">
        <div class="col-md-5 col-sm-5  form-group pull-right top_search">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">Go!</button>
                </span>
            </div>
        </div>
    </div> -->
</div>
<!-- <h1></h1>
<p><?php //echo lang('index_subheading');?></p> -->
<!-- <div id="infoMessage"><?php echo $message;?></div> -->
<div class="clearfix"></div>


<p><?php //echo lang('create_user_subheading');?></p>
<div id="infoMessage">
	<?php if($message) { ?>
		<div class="alert alert-warning" style = "color:red;">
			<h4><i class="fa fa-warning"></i> Warning!</h4>
			<?php echo $message;?>
		</div>
	<?php } ?>
</div>

<div class = "row">
	<div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo lang('create_user_heading');?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                        </div>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form class="form-label-left input_mask" action='<?php echo base_url('auth/create_user');?>' method='post'>
					<div class = "row" >
						<div class="col-md-6 col-sm-6  form-group has-feedback">
							<?php echo form_input($first_name);?>
							<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
						</div>
						<div class="col-md-6 col-sm-6  form-group has-feedback">
							<?php echo form_input($last_name);?>
							<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
						</div>
						<?php
							if($identity_column!=='email') {
								echo '<p>';
								echo lang('create_user_identity_label', 'identity');
								echo '<br />';
								echo form_error('identity');
								echo form_input($identity);
								echo '</p>';
							}
						?>
						<div class="col-md-6 col-sm-6  form-group has-feedback">
							<?php echo form_input($email); ?>
							<span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
						</div>

						<div class="col-md-6 col-sm-6  form-group ">
							<?php echo form_input($phone);?>	
							<span class="fa fa-phone form-control-feedback right" aria-hidden="true"></span>
						</div>
						
						<div class="col-md-4 col-sm-4  form-group has-feedback">
							<?php echo form_dropdown('role',$role_options,$selected_role,$role); ?>
							<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
						</div>

						<?php if(!$this->ion_auth->is_admin()) { ?>
							<div class="col-md-4 col-sm-4  form-group has-feedback ">
							<?php 	if( $selected_role == 2 ) {
										echo form_multiselect('course[]',$course_options,$selected_course,$course);
									} else {
										echo form_dropdown('course',$course_options,$selected_course,$course);
									}
									 ?>
								<span class="fa fa-graduation-cap form-control-feedback left" aria-hidden="true"></span>
							</div>
							<div class="col-md-4 col-sm-4  form-group has-feedback ">    
								<div id = 'select_subject' style="display : <?php if( $selected_role != 2 ){ echo 'none' ; } ?>">
									<?php echo form_multiselect('subject[]',$subject_options,$selected_subject,$subject); ?>
									<span class="fa fa-book form-control-feedback left" aria-hidden="true"></span>
								</div>
							</div>
						<?php } else { ?>
							<div  class="col-md-8 col-sm-8" id = 'for_local_admin' style = "display : <?php if( $selected_role != 5 ){ echo 'none' ; } ?>;">
								<div class="col-md-6 col-sm-6  form-group has-feedback ">    
									<?php echo form_input($company); ?>
									<span class="fa fa-institution form-control-feedback left" aria-hidden="true"></span>
								</div>
							
								<div class="col-md-6 col-sm-6  form-group">
									<?php echo form_input($date); ?>
								</div>
							</div>
						<?php } ?>
					</div>	
					</div class = 'row'>
						<div class="col-md-6 col-sm-6  form-group has-feedback">
							<?php echo form_input($password);?>
							<a style = "cursor: pointer;" onclick = "unmask(1)" ><span id = "icon1" class="fa fa-eye-slash form-control-feedback right" aria-hidden="true"></span></a>
						</div>

						<div class="col-md-6 col-sm-6  form-group has-feedback">
							<?php echo form_input($password_confirm);?>
							<a  style = "cursor: pointer;" onclick = "unmask(0)" ><span id = "icon2" class="fa fa-eye-slash form-control-feedback right" aria-hidden="true"></span></a>
						</div>
					</div>

					<div class="ln_solid"></div>
                    <div class="row">
                        <div class="form-group offset-md-6">
                            <!-- <button type="button" class="btn btn-primary">Cancel</button>
                            <button type="reset" class="btn btn-primary">Reset</button> -->
                            <input  value="true" name="insert" hidden/>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function check_role(){
        var role = document.getElementById('role').value;
        if ( role == 2 ){
			<?php if (!$this->ion_auth->is_admin()) { ?>
			document.getElementById('select_subject').style.display = 'block';
            document.getElementById('course').multiple = true;
            document.getElementById('course').required = true;
            document.getElementById('subject').required = true;
            document.getElementById('course').setAttribute("name", "course[]");
			<?php } ?>
        } else if ( role == 3 ) {
			<?php if(!$this->ion_auth->is_admin() ) { ?>
			document.getElementById('select_subject').style.display = 'none';  
            document.getElementById('course').required = true;
            document.getElementById('course').multiple = false;
            document.getElementById('subject').required = false;
            document.getElementById('course').setAttribute("name", "course");
			<?php } ?>
        } else if( role == 5 ) {
			<?php if($this->ion_auth->is_admin()) { ?>
			document.getElementById('for_local_admin').style.display = 'block';  
            document.getElementById('subscription').required = true;
            document.getElementById('company').required = true;
			<?php } else { ?>
				location.reload();
			<?php } ?>
		} else if( role == 1 ) {
			<?php if($this->ion_auth->is_admin()) { ?>
			document.getElementById('for_local_admin').style.display = 'none';  
            document.getElementById('subscription').required = false;
            document.getElementById('company').required = false;
			<?php } else { ?>
				location.reload();
			<?php } ?>
		}
    }

    var courses = [];
    function get_sub(){
        var course = document.getElementById("course");
		//document.getElementById("subject").innerHTML='';
        for(var i = 0 ; i < course.options.length ; i++){
            var opt = course.options[i];
            if ( opt.selected && courses.includes(opt.value) == false ) {
                courses.push( opt.value );
            }
            if ( opt.selected == false && courses.includes(opt.value) ){
                courses.splice(courses.indexOf(opt.value), 1);
            }
        }
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("subject").innerHTML=this.responseText;
        }
        };
        xhttp.open("GET", "<?php echo base_url(); ?>admin/get_sub?course_id="+courses, true);
        xhttp.send();
		courses = [];
    }

	function unmask($flag){
		if($flag){
			var pass = document.getElementById('password');
			var icon = document.getElementById('icon1');
		} else {
			var pass = document.getElementById('password_confirm');
			var icon = document.getElementById('icon2');
		}
		if(pass.type === "password" ) {
			pass.type = "text";
			icon.classList.remove("fa-eye-slash");
			icon.classList.add("fa-eye");
		} else {
			pass.type = "password";
			icon.classList.remove("fa-eye");
			icon.classList.add("fa-eye-slash");
		}
	}
</script>



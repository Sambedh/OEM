<div class="page-title">
    <div class="title_left">
        <h1><?php echo lang('index_heading');?></h1>
    </div>
</div>
<div class="clearfix"></div>
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
                <h2><?php  echo lang('edit_user_heading'); ?></h2>
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
				<?php echo form_open(uri_string() , 'class= form-label-left input_mask');?>
					<div class = "row" >
						<div class="col-md-6 col-sm-6  form-group has-feedback">
							<?php echo form_input($first_name);?>
							<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
						</div>
						<div class="col-md-6 col-sm-6  form-group has-feedback">
							<?php echo form_input($last_name);?>
							<span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
						</div>

						<div class="col-md-6 col-sm-6  form-group has-feedback">
							<input type=text class="form-control has-feedback-left"  value='<?php echo $this->ion_auth->get_users_groups($user->id)->row()->name;  ?>' readonly>
									
							<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
						</div>
						<div class="col-md-6 col-sm-6  form-group has-feedback">
							<?php echo form_input($phone);?>
							<span class="fa fa-phone form-control-feedback right" aria-hidden="true"></span>
						</div>
						
						<?php if ( $this->ion_auth->in_group(2,$user->id) ) { ?>
							<div class="col-md-6 col-sm-6  form-group has-feedback">
								<select class="form-control has-feedback-left" id="course"  name="course[]" disable onchange = 'get_sub()' multiple>
										<option value=""> Select Course</option>
										<?php foreach($courses as $role):?>
											<option value='<?php echo $role->id; ?>'<?php foreach($selected_courses as $course): if($role->id == $course) { echo "selected"; } endforeach;  ?>><?php echo $role->name; ?></option>
										<?php endforeach;?>
								</select>
								<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
							</div>
							<div class="col-md-6 col-sm-6  form-group has-feedback ">
								<select class="select2_multiple form-control has-feedback-left" id="subject" name = "subject[]" multiple >
										<option value = ''> Select Subject</option>
										<?php foreach($subjects as $item):
													foreach($item as $role):?>
															<option value='<?php echo $role->id; ?>'<?php foreach($teachers as $teacher): if($role->id == $teacher->subject_id) echo "selected"; endforeach; ?>><?php echo $role->name; ?></option>
										<?php       endforeach;
												endforeach; ?>
								</select>
								<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
							</div>
						<?php } elseif (  $this->ion_auth->in_group(3,$user->id) ) { ?>
							<div class="col-md-6 col-sm-6  form-group has-feedback">
								<select class="form-control has-feedback-left"  id="course" name="course" >
										<option value=""> Select Course</option>
										<?php foreach($courses as $role):?>
											<option value='<?php echo $role->id; ?>'<?php if($student) if($role->id == $student[0]->course_id) echo "selected"; ?>><?php echo $role->name; ?></option>
										<?php endforeach;?>
								</select>
								<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
							</div>
						<?php } elseif (  $this->ion_auth->in_group(5,$user->id) ) { ?>
							<div class="col-md-6 col-sm-6  form-group has-feedback "> 
								<?php echo form_input($company);?>
								<span class="fa fa-institution form-control-feedback left" aria-hidden="true"></span>
							</div>
						
							<div class="col-md-6 col-sm-6  form-group">
								<?php echo form_input($date);?>
								<!-- <span class="fa fa-calendar form-control-feedback right" aria-hidden="true"></span> -->
							</div>
						<?php } ?>

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
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                    <?php echo form_hidden('id', $user->id);?>
                    <?php echo form_hidden($csrf); ?>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<script>
    var courses = [];
    function get_sub(){
        var course = document.getElementById("course");
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
    }

	function unmask($flag){
		if($flag){
			var pass = document.getElementById('password');
			var icon = document.getElementById('icon1');
		} else {
			var pass = document.getElementById('confirm_password');
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

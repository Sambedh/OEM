<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Change Password </title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url('assests/admin');?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url('assests/admin');?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url('assests/admin');?>/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <div class="login_wrapper">
          <section class="login_content">
		<?php echo form_open("auth/change_password");?>
		  <h1><?php echo lang('change_password_heading');?></h1>
		  <div id="infoMessage">
			<?php if($message) { ?>
			<div class="alert alert-warning" style = "color:red;">
				<h4><i class="fa fa-warning"></i> Warning!</h4>
				<?php echo $message;?>
			</div>
			<?php } ?>
		  </div>
              <div>
		 	<?php echo lang('change_password_old_password_label', 'old_password');?> <br />
		  	<?php echo form_input($old_password); ?>
              </div>
              <div>
		  	<label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label> <br />
		  	<?php echo form_input($new_password); ?>
		  </div>
		  <div>
		  	<?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?> <br />
		  	<?php echo form_input($new_password_confirm);?>
              </div>
              <div class="d-flex justify-content-center">
		  	<?php echo form_submit('submit', lang('change_password_submit_btn'),array('class' => 'btn btn-default submit' , 'style' => 'margin : 0px;')); ?>
              </div>
		  <?php echo form_input($user_id); ?>
              <div class="clearfix"></div>
		<?php echo form_close();?>
          </section>
      </div>
    </div>
  </body>
</html>


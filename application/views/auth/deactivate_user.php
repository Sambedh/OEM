
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Dactivate User</title>

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
				<?php echo form_open("auth/deactivate/".$user->id);?>
					<h1><?php echo lang('deactivate_heading');?></h1>
					<p style = 'font-size : 17px'><?php echo sprintf(lang('deactivate_subheading'), $user->{$identity}); ?></p>
					<div>
						<p>
							<?php echo lang('deactivate_confirm_y_label', 'confirm');?>
							<input type="radio"  name="confirm" value="yes" checked="checked" />
							<?php echo lang('deactivate_confirm_n_label', 'confirm');?>
							<input type="radio"  name="confirm" value="no" />
						</p>
					</div>
					<div class="d-flex justify-content-center">
						<?php echo form_submit('submit', lang('deactivate_submit_btn'),array('class' => 'btn btn-link submit' , 'style' => 'margin : 0px;')); ?>
					</div>
						<?php echo form_hidden($csrf); ?>
					<?php echo form_hidden(['id' => $user->id]); ?>
					<div class="clearfix"></div>
				<?php echo form_close();?>
				</section>
			</div>
		</div>
	</body>
</html>


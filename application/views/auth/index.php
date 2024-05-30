<style>
	.pagination {
		display : none;
	}
	.dataTables_info{
		display : none;
	}
	#datatable-responsive_filter {
		display : none;
	}
</style>
<div class="page-title">
    <div class="title_left">
        <h3><?php echo lang('index_heading');?></h3>
    </div>
</div>
<div id="infoMessage"><?php echo $message;?></div>
<div class="clearfix"></div>
<p><i class = 'fa fa-plus'> </i><?php echo anchor('auth/create_user', lang('index_create_user_link')) ?> | <?php //echo anchor('auth/create_group', lang('index_create_group_link'))?></p>
<div class = "row">
	<div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Existing Users</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <!-- <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<a class="dropdown-item" href="#">Settings 1</a>
							<a class="dropdown-item" href="#">Settings 2</a>
						</div>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
					</li> -->
					<?php if ($this->ion_auth->is_admin()) { ?>
						<li>
							<div class = 'form-group' >
								<select class = 'form-control' id = 'company' onchange = 'get_course()'>
									<option value = '' >All</option>
									<?php 
									foreach($users as $user):
										if($this->ion_auth->in_group(5,$user->id)){
											echo '<option value = "'.$user->id.'">'.$user->company.'</option>';
										}
									endforeach;
									?>
								</select>
							</div>
						</li>	
					<?php } ?>
					<li>
						<input value = "" id = 'company' hidden>
						<div class = 'form-group'>
							<select class = 'form-control' id = 'course' onchange = 'load_tbody()'>
								<option value = ''>All</option>
								<?php 
								if ($this->ion_auth->is_admin()) {
									foreach($courses as $course):
										echo '<option value = "'.$course['id'].'">'.$course['name'].'</option>';
									endforeach;
								} else {
									foreach($courses as $course):
										echo '<option value = "'.$course->id.'">'.$course->name.'</option>';
									endforeach;
								} 
								?>
							</select>
						</div>
					</li>
					<li>
						<div class = 'form-group'>
							<select class = 'form-control' id ='role' onchange='load_tbody()'>
								<option value = ''>All</option>
								<?php 
								if ($this->ion_auth->is_admin()) {
									echo '<option value = "5" selected>Admin</option>';
									echo '<option value = "2" >Faculty</option>';
									echo '<option value = "3" >Student</option>';
								} else {
									echo '<option value = "2" >Faculty</option>';
									echo '<option value = "3" >Student</option>';
								} 
								?>
							</select>
						</div>
					</li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
							<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>  
                                    <tr>
                                        <th><?php echo lang('index_fname_th');?></th>
                                        <th><?php echo lang('index_lname_th');?></th>
                                        <th><?php echo lang('index_email_th');?></th>
                                        <?php  if ($this->ion_auth->is_admin()){ ?>
                                        <th><?php echo lang('index_groups_th');?></th>
										<th>Subscription</th>
                                        <?php } else {
											echo '<th>Role</th>';
										} ?>
                                        <th><?php echo lang('index_status_th');?></th>
                                        <th><?php echo lang('index_action_th');?></th>
                                    </tr>
                                </thead>
                                <tbody id = 'table_body'>
                                </tbody>
							</table>
                        </div>
                    </div>
				</div>
				<div class = row style = " cursor : pointer;">
					<div class = 'col-md-1 col-sm-1'>
						<button id = 'previous' disabled class = "btn btn-link" onclick = 'previous()'>Previous</button>  
					</div>
					<div class = 'col-md-1 col-sm-1 offset-10'>
						<button id = 'next' class = "btn btn-link" onclick = 'next()'>Next</button>
					</div>	
					<input type = 'hidden' value = '0' id ='page_rendered'>	
					<input type = 'hidden' value = '0' id ='action'>
				</div>
            </div>
        </div>
    </div>
</div>
<script>
	function get_course() {
        var company = document.getElementById("company").value;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("course").innerHTML=this.responseText;
        }
        };
        xhttp.open("GET", "<?php echo base_url(); ?>super_admin/get_course?company_id="+company, true);
        xhttp.send();
		document.getElementById("course").value = '';
		document.getElementById("role").value = '';	
		load_tbody();
    }
	function next(){
		var page =  parseInt(document.getElementById('page_rendered').value);
		document.getElementById('page_rendered').value = page + 1;
		load_tbody();
	}
	function load_tbody() {
		var course = document.getElementById("course").value;
		var role = document.getElementById("role").value;
		var company = document.getElementById("company").value;
		var action = parseInt(document.getElementById('action').value);
		var rows = parseInt(document.getElementsByName('datatable-responsive_length')[0].value);
		var page =  parseInt(document.getElementById('page_rendered').value);
		if(page == 0){
			document.getElementById('page_rendered').value = 1;
			document.getElementById('action').value = 1;
			document.getElementsByName('datatable-responsive_length')[0].addEventListener("change", load_tbody);
		}
		if( action > page){
			var flag = 'previous';
			document.getElementById('action').value = page;
		} else if( page > action) {
			var flag = 'next';
			document.getElementById('action').value = page;
		} else {
			var flag = '';
		}
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("table_body").innerHTML=this.responseText;
				var track = document.getElementById("track").innerHTML;
				switch(track){
					case 'disable':
						document.getElementById('previous').disabled = true;
						document.getElementById('next').disabled = true;
						break;
					case 'disablenext':
						document.getElementById('next').disabled = true;
						document.getElementById('previous').disabled = false;
						break;
					case 'disableprevious':
						document.getElementById('previous').disabled = true;
						document.getElementById('next').disabled = false;
						break;
					case 'previous':
						document.getElementById('previous').disabled = false;
						break;
					default :
						document.getElementById('next').disabled = false;
						document.getElementById('previous').disabled = false;
				}
			}
		};
		xhttp.open("POST", "<?php echo base_url(); ?>admin/get_users", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send("rows="+rows+"&course="+course+"&role="+role+"&company="+company+"&action="+flag);
	}
	function previous(){
		var page =  parseInt(document.getElementById('page_rendered').value);
		document.getElementById('page_rendered').value = page - 1;
		load_tbody();
	}
	window.addEventListener("load", load_tbody);
</script>


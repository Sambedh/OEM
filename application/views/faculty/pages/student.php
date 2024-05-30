<div class="page-title">
    <div class="title_left">
        <h3>Results</h3>
    </div>
</div>
<div class="clearfix"></div>
<div class = "row">
	<div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>All Examinations Results</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
							<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>  
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone number</th>
                                        <th>Course</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $items):
                                            foreach ($items as $student):
                                                $user = $this->admin_model->get_data_by_id('users',$student->user_id);
                                                $course = $this->admin_model->get_data_by_id('course',$student->course_id); ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8'); ?></td>
                                                    <td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
                                                    <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
                                                    <td><?php echo htmlspecialchars($user->phone,ENT_QUOTES,'UTF-8');?></td>
                                                    <td><?php echo $course->name; ?></td>
                                                    <td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link')) : anchor("auth/activate/". $user->id, lang('index_inactive_link'));?></td>
                                                </tr>
                                            <?php  
                                        endforeach;
                                    endforeach; ?>
                                </tbody>
							</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



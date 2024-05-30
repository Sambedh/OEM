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
                                        <th>Exam</th>
                                        <th>Subject</th>
                                        <th>User</th>
                                        <th>Total Grade</th>
                                        <th>Obtain Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php foreach ($results as $result):
                                    $paper = $this->admin_model->get_data_by_id('exam_sub',$result['exam_sub_id']);
                                    $exam = $this->admin_model->get_data_by_id('exam',$paper->exam_id);
                                    $subject = $this->admin_model->get_data_by_id('subject',$paper->subject_id); 
                                    $user = $this->admin_model->get_data_by_id('users',$result['user_id']);?>
									<tr>
										<td><?php echo $exam->name; ?></td>
										<td><?php echo $subject->name;?></td>
										<td><?php echo ucfirst($user->first_name).' '. ucfirst($user->last_name); ?></td>
										<td><?php echo $paper->total_questions * $paper->each_mark; ?></td>
										<td> <?php echo $result['marks']; ?> </td>
									</tr>
								<?php endforeach;?>
                                </tbody>
							</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



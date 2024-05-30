<?php foreach ($exams as $exam):
    if( date('Y-m-d') >= $exam->start_date && date('Y-m-d') <= $exam->end_date ) { ?>
    <div class="page-title">
        <div class="title_left">
        <h3><?php echo $exam->name; ?></h3>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php $papers = $this->admin_model->get_data_by_attr_id('exam_sub','exam_id',$exam->id); 
    foreach($papers as $paper):
        $subject = $this->admin_model->get_data_by_id('subject',$paper->subject_id);
        if($this->admin_model->get_data_by_2attr_id('result','user_id','exam_sub_id',$user->id,$paper->id)->num_rows() == 0){?>
        <div class="row">
            <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                <h2><?php echo $subject->name; ?></h2>
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
                    <div class='row'>
                        <div class = 'col-md-6 col-sm-6'>
                            <span style ="font-weight: bold;">Examination Date : </span><?php echo $paper->date; ?></br>
                            <span style ="font-weight: bold;">Time : </span>From&nbsp;<?php echo date ('h:i a' ,strtotime($paper->start_time)); ?>&nbsp;to&nbsp;<?php echo date ('h:i a' ,strtotime($paper->end_time)); ?></br>
                            <span style ="font-weight: bold;">Duration : </span><?php echo $paper->duration?> minutes</br>
                            <span style ="font-weight: bold;">Total Marks : </span><?php echo $paper->total_questions*$paper->each_mark; ?></br>
                            <span>
                        </div>
                        <div class = 'col-md-2 col-sm-2 offset-4'>
                            <?php if( date('Y-m-dH:i:s') < $paper->date.$paper->start_time ) { ?>
                                <h5 style ="color : #17a2b8; "> Your exam will start soon.</h5>
                            <?php } elseif ( date('Y-m-dH:i:s') > $paper->date.$paper->end_time ) { ?>
                                <h5 style ="color : red; ">You missed the Exam</h5>
                            <?php } else { ?>
                                <a href="<?php echo base_url('student/paper/'.$paper->id);?>"><button class='btn btn-primary btn-block'>Attempt Exam</button></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    <?php }  endforeach; ?>
<?php } endforeach; ?>

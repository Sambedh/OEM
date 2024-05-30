
<div class="page-title">
    <div class="title_left">
        <h3>Results</h3>
    </div>
</div>

<div class="clearfix"></div>
<?php 
foreach( $exams as $exam ): ?>
    <div class="row">
        <div class="col-md-12 col-sm-12  ">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?php echo $exam->name; ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
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
                <div class="x_content" style = 'display : none;'>
                    <div class='row'>
                        <div class = 'col-md-4 col-sm-4'>
                            <span style ="font-weight: bold;">Subject 
                        </div>
                        <div class = 'col-md-3 col-sm-3'>
                            <span style ="font-weight: bold;">Total Marks 
                        </div>
                        <div class = 'col-md-3 col-sm-3'>
                            <span style ="font-weight: bold;">Obtain Marks 
                        </div>
                        <div class = 'col-md-2 col-sm-2'>
                            <span style ="font-weight: bold;">View Answersheet 
                        </div>
                    </div>
                    <?php $papers = $this->admin_model->get_data_by_attr_id('exam_sub','exam_id',$exam->id);
                    foreach($papers as $paper):
                        foreach($results as $result):
                            if( $paper->id == $result->exam_sub_id ) { ?>
                                <div class='row'>
                                    <div class = 'col-md-4 col-sm-4'>
                                        <?php echo $this->admin_model->get_data_by_id('subject',$paper->subject_id)->name; ?> 
                                    </div>
                                    <div class = 'col-md-3 col-sm-3'>
                                        <?php echo $paper->total_questions*$paper->each_mark; ?>
                                    </div>
                                    <div class = 'col-md-3 col-sm-3'>
                                        <?php echo $result->marks; ?>
                                    </div>
                                    <div class = 'col-md-2 col-sm-2'>
                                        <a href="<?php echo base_url('student/paper/'.$paper->id);?>" class = 'btn btn-link'>Click here</a>
                                    </div>
                                </div> 
                                <div class="clearfix"></div>
                            <?php }
                        endforeach;
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
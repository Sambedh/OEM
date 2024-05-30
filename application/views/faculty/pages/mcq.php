
<div class="page-title">
    <div class="title_left">
    <h3>Manage Question Papers</h3>
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
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Create Question Paper</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                    </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
            <!-- Smart Wizard -->
                <h2>Setup New Question Paper</h2>
                <form class="form-horizontal form-label-left" action="<?php echo base_url('faculty/mcq');?>" method="post">
                    <div id="wizard" class="form_wizard wizard_horizontal">
                        
                        <ul class="wizard_steps">
                        <li>
                            <a href="#step-1">
                            <span class="step_no">1</span>
                            <span class="step_descr">
                                                Step 1<br />
                                                <small>Select Exam</small>
                                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-2">
                            <span class="step_no">2</span>
                            <span class="step_descr">
                                                Step 2<br />
                                                <small>Select Subject</small>
                                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-3">
                            <span class="step_no">3</span>
                            <span class="step_descr">
                                                Step 3<br />
                                                <small>Define grades</small>
                                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="#step-4">
                            <span class="step_no">4</span>
                            <span class="step_descr">
                                                Step 4<br />
                                                <small>Define time</small>
                                            </span>
                            </a>
                        </li>
                        </ul>
                        <div>
                                <div id="step-1">
                                <h5 class="StepTitle" style="text-align: center;">Select Exam</h5>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-1 col-sm-1">Select Exam
                                        </label>
                                        <div class="col-md-4 col-sm-4 ">
											<?php echo form_dropdown('exam',$exam_options,$selected_exam,$exam); ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="step-2">
                                    <h2 class="StepTitle" style="text-align: center;">Select Subject</h2>
                                    <p style="text-align: center;">Message</p>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align" >Select Subject
                                        </label>
                                        <div class="col-md-5 col-sm-5 ">
											<?php echo form_dropdown('subject',$subject_options,$selected_subject,$subject); ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="step-3">
                                    <h2 class="StepTitle" style="text-align: center;">Define Grades</h2>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2 col-sm-2 label-align offset-3" >Total questions
                                        </label>
                                        <div class="col-md-2 col-sm-2 ">
											<?php echo form_input($total_questions); ?>
                                        </div>
                                        <label class="col-form-label col-md-2 col-sm-2 label-align" >Each marks
                                        </label>
										<div class="col-md-2 col-sm-2 ">
											<?php echo form_input($each_marks); ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="step-4">
                                    <h2 class="StepTitle" style="text-align: center;">Define Date & Time</h2>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2 col-sm-2 label-align offset-4" >Start time
                                        </label>
                                        <div class="col-md-2 col-sm-2 ">
										<?php echo form_input($start_time); ?>
                                        </div>
                                        <label class="col-form-label col-md-2 col-sm-2 label-align" >End time
                                        </label>
                                        <div class="col-md-2 col-sm-2 ">
										<?php echo form_input($end_time); ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-2 col-sm-2 label-align offset-4" >Date
                                        </label>
										<div class="col-md-2 col-sm-2 " id = 'date_container' >
										<?php echo form_input($date); ?>
                                        </div>
                                        <label class="col-form-label col-md-2 col-sm-2 label-align" >Duration
                                        </label>
										<div class="col-md-2 col-sm-2 ">
										<?php echo form_input($duration); ?><span>In minutes</span>
                                        </div>
                                    </div>
                                </div>
                                <input  value="true" name="insert" hidden/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Existing question papers</h2>
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
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Exam</th>
                                    <th>Subject</th>
                                    <th>Total question</th>
                                    <th>Each Mark</th>
                                    <th>Date</th>
                                    <th>Start time</th>
                                    <th>End time</th>
                                    <th>Duration</th>
                                    <th style='width:17%'>Manage</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach($exam_subjects as $items):
                                        foreach($items as $detail):
                                            $total_questions = count($this->admin_model->get_data_by_attr_id('mcq','exam_sub_id',$detail->id));
                                            echo "<tr>";
                                            echo "<td>".$this->admin_model->get_data_by_id('exam',$detail->exam_id)->name."</td>";
                                            echo "<td>".$this->admin_model->get_data_by_id('subject',$detail->subject_id)->name."</td>";
                                            echo "<td>".$detail->total_questions."</td>";
                                            echo "<td>".$detail->each_mark."</td>";
                                            echo "<td>".$detail->date."</td>";
                                            echo "<td>".$detail->start_time."</td>";
                                            echo "<td>".$detail->end_time."</td>";
                                            echo "<td>".$detail->duration."</td>";
                                            $url = site_url()."faculty/mcq?id=".$detail->id."&update=true";
                                            $url2 = site_url()."faculty/mcq?id=".$detail->id."&delete=true";
                                            echo "<td>";?>
                                            <a  class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal_<?php echo $detail->id; ?>"><i class="fa fa-pencil"></i> Edit </a>
                                            <a onclick="confirmDelete(<?php echo $detail->id; ?>);"   class="btn btn-danger btn-xs" style="cursor:pointer;" data-toggle="tooltip"  title=" Remove Program">
												<i class="fa fa-trash-o"></i> Delete
											</a>
                                            <?php echo"</td>";?>
                                            <div class="modal fade" id="myModal_<?php echo $detail->id; ?>" role="dialog">
                                                <div class="modal-dialog">
                                                <!-- Modal content-->
                                                    <form action='<?php echo $url ?>' method='post'>
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Update</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>  
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group row ">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Examination
                                                                    </label>
                                                                    <div class="col-md-9 col-sm-9 ">
                                                                        <input type = 'text' class="form-control" name='exam<?php echo $detail->id; ?>' value = '<?php echo $this->admin_model->get_data_by_id('exam',$detail->exam_id)->name; ?>' disabled >
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row ">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" >Subject
                                                                    </label>
                                                                    <div class="col-md-9 col-sm-9 ">
                                                                        <input type = 'text' class="form-control" name='subject<?php echo $detail->id; ?>' value = '<?php echo $this->admin_model->get_data_by_id('subject',$detail->subject_id)->name; ?>' disabled > 
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row ">
                                                                    <label class="control-label col-md-3 col-sm-3 ">Total Questions</label>
                                                                    <div class="col-md-3 col-sm-3 ">
                                                                        <input type="number" class="form-control" placeholder="" name='total_questions<?php echo $detail->id; ?>' value="<?php echo $total_questions; ?>" disabled required>
                                                                    </div>
                                                                    <label class="control-label col-md-3 col-sm-3 ">Each mark</label>
                                                                    <div class="col-md-3 col-sm-3 ">
                                                                        <input type="number" class="form-control" placeholder="" name='each_marks<?php echo $detail->id; ?>' value="<?php echo $detail->each_mark; ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row ">
                                                                    <label class="control-label col-md-2 col-sm-2 ">Start Time</label>
                                                                    <div class="col-md-4 col-sm-4 ">
                                                                        <input type="time" class="form-control" placeholder="" name='start_time<?php echo $detail->id; ?>' value="<?php echo $detail->start_time; ?>" required>
                                                                    </div>
                                                                
                                                                    <label class="control-label col-md-2 col-sm-2 ">End time</label>
                                                                    <div class="col-md-4 col-sm-4 ">
                                                                        <input type="time" class="form-control" placeholder="" name='end_time<?php echo $detail->id; ?>' value="<?php echo $detail->end_time; ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row ">
                                                                    <label class="control-label col-md-2 col-sm-2 ">Date</label>
                                                                    <div class="col-md-4 col-sm-4 ">
                                                                        <input type="date" class="form-control" placeholder="" name='date<?php echo $detail->id; ?>' value="<?php echo $detail->date; ?>" required>
                                                                    </div>
                                                                    <label class="control-label col-md-2 col-sm-2 ">Duration</label>
                                                                    <div class="col-md-4 col-sm-4 ">
                                                                        <input type="number" class="form-control" placeholder="" name='duration<?php echo $detail->id; ?>' value="<?php echo $detail->duration; ?>" required>
                                                                    </div>
                                                                    <!-- <div class="col-md-3 col-sm-3 ">
                                                                        <span style="font-size:large">In minutes</span>
                                                                    </div>    -->

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <?php $paper_url=site_url()."faculty/paper?id=".$detail->id; ?>
                                                                <input type="submit" name="btnUserUpdate" class="btn btn-success" value="Update" />	
                                                                <a href ="<?php echo $paper_url; ?>"><button type="button" class="btn btn-secondary">Edit question paper</button></a>						
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>	
                                            <?php echo "</tr>";
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
<div id="Delete_Model" class="modal modal-primary" >
      <!-- Modal content -->
    <div class="modal-dialog">
      <div  class="modal-content">
        <div class="modal-header">
			<h4 class="modal-title">Confirm  Delete?</h4>
          	<span style ="cursor : pointer" class="close" onclick="reset_confirmDelete();" >×</span>
        </div>
        <div class="modal-body">
		  <p>Click Yes To Delete and No to cancel</p>
		  <p>Note : All questions and answers related with this paper will be deleted</p>
          <p></p>
        </div>
        <div class="modal-footer" >
            <form method="post" action="<?php echo base_url('faculty/mcq'); ?>" name="deleteForm" >
                <input type="hidden"  name="id" id="deleteId" />
				<input type="hidden"  name="deleteMCQ" value="delete" />
                <input type="submit"   value="YES" class="btn btn-success" />
                <button type="button"  class="btn btn-outline" onclick="reset_confirmDelete();">NO </button>
            </form>
        </div>
     </div>
    </div>
</div> 
<script>
	var Delete_Model = document.getElementById('Delete_Model');
	document.onkeydown = function(evt) {
	    evt = evt || window.event;
	    if (evt.keyCode == 27) {
	    	reset_confirmDelete();
	    }
	    if(evt.keyCode == 13){
	    	if(Delete_Model.style.display == 'block')
			{
	    		document.deleteForm.submit();
			}
	    }
	};
	function confirmDelete(inqid)
	{
	   var posDel = document.getElementById("deleteId");
	   posDel.value = inqid;
	   Delete_Model.style.display = "block";
	   return false;
	}
	function reset_confirmDelete()
	{
	    var posDel = document.getElementById("deleteId");
	    posDel.value = 0;        
	    Delete_Model.style.display = "none";
	}  

  function check_subject(){
    var exam=document.getElementById("exam").value;       
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("subject").innerHTML=this.responseText;
      }
    };
    xhttp.open("GET", "<?php echo base_url(); ?>admin/ret_sub?exam_id="+exam, true);
    xhttp.send();
	if(exam){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("date_container").innerHTML=this.responseText;
		}
		};
		xhttp.open("GET", "<?php echo base_url(); ?>admin/ret_date?exam_id="+exam, true);
		xhttp.send();
	}
  }
  document.addEventListener("DOMContentLoaded", check_subject);
  </script>


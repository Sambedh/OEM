<div class="page-title">
    <div class="title_left">
        <h3>Courses</h3>
    </div>
</div>
<div class="clearfix"></div>
<div id="infoMessage">
	<?php 
	if(isset($_SESSION['delete_error'])) { ?>
		<div class="alert alert-warning" style = "color:red;">
			<h4><i class="fa fa-warning"></i> Warning!</h4>
			<?php echo $_SESSION['delete_error'];
					$this->session->unset_userdata('delete_error'); ?>
		</div>
	<?php } ?>
	<?php if($message) { ?>
		<div class="alert alert-warning" style = "color:red;">
			<h4><i class="fa fa-warning"></i> Warning!</h4>
			<?php echo $message;?>
		</div>
		<style>
			.course{
				display : block;
			}
		</style>
	<?php } else { ?>
		<style>
			.course{
				display : none;
			}
		</style>
	<?php } ?>
</div>
<div class = "row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add new course</h2>
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
            <div class="x_content course">
                <br />
                <form class="form-horizontal form-label-left" action='<?php echo base_url('admin/course');?>' method='post'>

                    <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">Course name</label>
                        <div class="col-md-9 col-sm-9 ">
							<?php echo form_input($course); ?>
                        </div>
                    </div>
                   
                    
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Description <span class="required"></span>
                        </label>
                        <div class="col-md-9 col-sm-9 ">
							<?php echo form_textarea($desc); ?>
                        </div>
                    </div>
                    


                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9  offset-md-3">
                            <input  value="true" name="insert" hidden/>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Existing Courses</h2>
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
                                    <!-- <th style="width: 5%;">Id</th> -->
                                    <th style="width: 33%;">Course name</th>
                                    <th style="width: 50%;">Description</th>
                                    <th style="width: 17%;">Manage</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach($courses as $detail):
                                    echo "<tr>";
                                    //echo "<td>".$detail->id."</td>";
                                    echo "<td>".$detail->name."</td>";
                                    echo "<td type='textarea'>".$detail->description."</td>";
                                    $url = site_url()."admin/course?id=".$detail->id."&update=true";
                                    //$url2 = site_url()."admin/course?id=".$detail->id."&delete=true";
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
                                                            <label class="control-label col-md-3 col-sm-3 ">Course name</label>
                                                            <div class="col-md-9 col-sm-9 ">
                                                                <input type="text" class="form-control" placeholder="Course" name='name<?php echo $detail->id; ?>' value="<?php echo $detail->name; ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="control-label col-md-3 col-sm-3 ">Description <span class="required"></span>
                                                            </label>
                                                            <div class="col-md-9 col-sm-9 ">
                                                                <textarea class="form-control" rows="3" placeholder="About course" name='desc<?php echo $detail->id; ?>' required><?php echo $detail->description; ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" name="btnUserUpdate" class="btn btn-success" value="Update" />								
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
									</div>
                                    <?php echo "</tr>";   
                                    endforeach;?>
                                </tbody>
                            </table>
            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Delete Model -->
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
          <p></p>
        </div>
        <div class="modal-footer" >
            <form method="post" action="<?php echo base_url('admin/course'); ?>" name="deleteForm" >
                <input type="hidden"  name="id" id="deleteId" />
				<input type="hidden"  name="deleteCourse" value="delete" />
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
</script>                    
<!--End  Delete Model -->

<?php
$update_id = isset($_GET['id'])?$_GET['id'] : '' ;
$insert_id = isset($_GET['paper_id'])?$_GET['paper_id'] : '' ;
?>

<?php if(!empty($insert_id)){
    $message = 'Fill Questions and Answer';
    ?>
    <div class="page-title">
        <div class="title_left">
            <h3><?php echo $message; ?></h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <form class="form-horizontal form-label-left" enctype="multipart/form-data" action='<?php echo base_url('faculty/paper');?>' method='post'>
        <?php for ($x=1 ; $x<=$n ; $x++){ ?>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                    <h2>Question number <?php echo $x; ?></h2>
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
                    <div class="x_content" style='display : none'>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Enter Question <span class="required"></span>
                                </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <textarea class="form-control" rows="3" placeholder="Question" name='ques<?php echo $x; ?>' required></textarea>
                                </div>
							</div>
							<div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Select Image for Question(if neccessary) <span class="required"></span>
                                </label>
                                <div class="col-md-9 col-sm-9">
                                    <input  type='file' class="form-control"  placeholder="" name='img<?php echo $x; ?>[]' >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Option 1 <span class="required"></span>
                                </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <textarea class="form-control" rows="2" placeholder="Option 1" name='opt1<?php echo $x; ?>' required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Option 2 <span class="required"></span>
                                </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <textarea class="form-control" rows="2" placeholder="Option 2" name='opt2<?php echo $x; ?>' required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Option 3 <span class="required"></span>
                                </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <textarea class="form-control" rows="2" placeholder="Option 3" name='opt3<?php echo $x; ?>' required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Option 4 <span class="required"></span>
                                </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <textarea class="form-control" rows="2" placeholder="Option 4" name='opt4<?php echo $x; ?>' required></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Correct option<span class="required"></span>
                                </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <label class="radio-inline">
                                        <input type="radio" name="ans<?php echo $x; ?>" value="1">Option 1 <span> &emsp; </span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="ans<?php echo $x; ?>" value="2">Option 2 <span> &emsp; </span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="ans<?php echo $x; ?>" value="3">Option 3 <span> &emsp; </span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="ans<?php echo $x; ?>" value="4">Option 4
                                    </label>
                                </div>
                            </div>
                        
                    </div>
                </div>
                </div>
            </div>
        <?php } ?>
        <div class="form-group row">
            <input type="hidden" name="insert" value="TRUE"/>
            <input type='hidden' name='n' value=<?php echo $n;?>/>
            <input type='hidden' name='paper_id' value=<?php echo $insert_id;?>>
            <button type='submit'  class="btn btn-success">Submit</button>
        </div>
    </form>
<?php }elseif(!empty($update_id)){ 
    $n = $this->admin_model->get_data_by_id('exam_sub',$update_id)->total_questions;
    $x = 0;
    $message = 'Edit Questions and Answer';?>
     <div class="page-title">
        <div class="title_left">
            <h3><?php echo $message; ?></h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <form class="form-horizontal form-label-left" enctype="multipart/form-data" action='<?php echo base_url('faculty/paper');?>' method='post'>
        <?php foreach ($mcqs as $mcq): 
                    $x = $x +1; 
                    $a = 0;
                    $b = 0;
					$options = $this->admin_model->get_data_by_attr_id('mcq_options','mcq_id',$mcq->id);
					$gallery = $this->admin_model->get_data_by_attr_id('gallery_images','mcq_id',$mcq->id); ?>
            <div class="row">
                <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                    <h2>Question number <?php echo $x; ?></h2>
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
                    <div class="x_content" style='display : none'>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Enter Question <span class="required"></span>
                            </label>
                            <div class="col-md-9 col-sm-9 ">
                                <textarea class="form-control" rows="3" placeholder="Question" name='ques<?php echo $x; ?>' required><?php echo $mcq->question;?></textarea>
                            </div>
						</div>
						<div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Select Image for Question(if neccessary) <span class="required"></span>
                            </label>
							<?php if(empty($gallery)){ ?>
								 <div class="col-md-9 col-sm-9">
									 <input  type='file' class="form-control"  placeholder="" name='img<?php echo $x; ?>[]'>
							 	</div>
							 <?php } else { ?>
								<div class="col-md-9 col-sm-9">
									<div class = 'row' >
										<?php foreach($gallery as $img): ?>
											<div class = "col-md-3" id = "image<?php echo $img->id; ?>">	
												<div class="thumbnail" style = "height : auto; ">
													<div class="image view view-first">
														<img style="width: 100%; display: block;" src="<?php echo base_url('uploads/img/mcq'.$img->file_name); ?>" alt="image" />
														<div class="mask no-caption">
															<div class="tools tools-bottom">
																<a href=""><i class="fa fa-expand"></i></a>
																<a href=""><i class="fa fa-pencil"></i></a>
																<a onclick = 'deleteImg(<?php echo $img->id; ?>)'><i class="fa fa-times"></i></a>
															</div>
														</div>
													</div>
													<div class="caption">
														
													</div>
												</div>
											</div>

										<?php endforeach;  ?>
										<div class="col-md-4 col-sm-4" id = "newimage<?php echo $img->id; ?>" style = "display : none;">
									 		<input  type='file' class="form-control"  placeholder="Add Image" name='img<?php echo $x; ?>[]' >
							 			</div>	
									</div>
								</div>
							<?php } ?>
                        </div>
                        <?php foreach($options as $option):
                            $a= $a+1;
                            if($option->status) $b=$a;?>
                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Option <?php echo $a;?> <span class="required"></span>
                                </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <textarea class="form-control" rows="2" placeholder="Option 1" name='opt<?php echo $a.$x; ?>' required><?php echo $option->name;?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Correct option<span class="required"></span>
                            </label>
                            <div class="col-md-9 col-sm-9 ">
                                <label class="radio-inline">
                                    <input type="radio" name="ans<?php echo $x; ?>" value="1" <?php if($b == 1) echo 'checked';?>>Option 1 <span> &emsp; </span>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="ans<?php echo $x; ?>" value="2" <?php if($b == 2) echo 'checked';?>>Option 2 <span> &emsp; </span>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="ans<?php echo $x; ?>" value="3" <?php if($b == 3) echo 'checked';?>>Option 3 <span> &emsp; </span>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="ans<?php echo $x; ?>" value="4" <?php if($b == 4) echo 'checked';?>>Option 4
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="form-group row">
            <input type="text" name="update" value="TRUE" hidden>
            <input type='hidden' name='n' value=<?php echo $n;?>/>
            <input type='hidden' name='paper_id' value=<?php echo $update_id;?>/>
            <button type='submit'  class="btn btn-success">Update</button>
        </div>
    </form>
<?php }else{
    $n = 0;
    $message = 'Error encountered Please Delete paper and create again';
    echo $message;
}?>
<script>
	function deleteImg(id) {
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", "<?php echo base_url(); ?>admin/delete_image", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send("gallery_id="+id);
		document.getElementById('image'.concat(id)).remove();
		document.getElementById('newimage'.concat(id)).style.display = "block";
	}
</script>

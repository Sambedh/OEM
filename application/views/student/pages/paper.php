
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo base_url('assests/admin');?>/images/favicon1.ico" type="image/ico" />
    <title>Exam | </title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url('assests/admin');?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url('assests/admin');?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- Custom Theme Style -->
        <link href="<?php echo base_url('assests/admin');?>/build/css/custom.min.css" rel="stylesheet">
    </head>
    <body class="nav-md">
        <div class="container body">
            <?php if($result || $time_out){ ?>
                <div class="main_container">
                    <div class="col-md-3 left_col">
                        <div class="left_col scroll-view">
                            <div class="navbar nav_title" style="border: 0;">
                                <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Soft Web</span></a>
                            </div>

                            <div class="clearfix"></div>

                            <!-- menu profile quick info -->
                            <div class="profile clearfix">
                                <div class="profile_pic">
                                    <img src="<?php echo base_url('assests/admin');?>/images/user.png" alt="..." class="img-circle profile_img">
                                </div>
                                <div class="profile_info">
                                    <span>Welcome,</span>
                                    <h2>
                                    <?php
                                        echo ucfirst($user->first_name) . ' ' . ucfirst($user->last_name);
                                    ?>
                                    </h2>
                                </div>
                            </div>
                            <!-- /menu profile quick info -->

                            <br />

                            <!-- sidebar menu -->
                            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                                <div class="menu_section">
                                    <h3>Questions Index</h3>
                                    <div class='item form-group row'>
                                        <?php 
                                        $n = $paper->total_questions;
                                        for ($x=1 ; $x<=$n ; $x++ ){ ?>
                                                <div id= "index<?php echo $x;?>" class="col-md-2 col-sm-2" style = 'margin : 2px; border : 2px solid black; padding:10px; background-color : red;'>
                                                    <a onclick="change_question(<?php echo $x.','.$n ;?>)"><span class="" style = 'color : black; font-size : 20px; cursor : pointer;'><?php echo $x;?><span></a> 
                                                </div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class='sidebar-footer hidden-small '>
                                <a style = "width : 100%;" href = '<?php echo base_url('student/result'); ?>'><button class="btn btn-success col-md-12 col-sm-12" >Other Results</button></a>
                            </div>
                            <!-- /sidebar menu -->
                        </div>
                    </div>

                    <!-- top navigation -->
                    <div class="top_nav">
                        <div class="nav_menu">
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /top navigation -->

                    <!-- page content -->
                    <div class="right_col" role="main">
                        <div class="">
                            <div class="page-title">
                                <div class="title_left">
                                    <h3><?php echo $exam->name;?></h3>
                                </div>
                                <h2><?php echo $this->admin_model->get_data_by_id('subject',$paper->subject_id)->name; ?></h2>
                            </div>
                            <div class="clearfix"></div>
                            <?php 
                            $x=0;
                            foreach($mcqs as $mcq):
                                $x++;
                                $i = 0;
								$options = $this->admin_model->get_data_by_attr_id('mcq_options','mcq_id',$mcq->id);
								$gallery = $this->admin_model->get_data_by_attr_id('gallery_images','mcq_id',$mcq->id);
                                $flag = FALSE ;
                                ?>
                                <div  id="mcq_block<?php echo $x; ?>" style="display : <?php if($x != 1) echo 'none';?>;">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12  ">
                                            <div class="x_panel">
                                                <div class="x_title">
                                                    <h2>Question no. <?php echo $x;?></h2>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="x_content">
                                                    <div class ="control-label col-md-12 col-sm-12">
                                                        <span style="font-size : large ; font-weight : bold; "><?php echo $mcq->question;?></span>
													</div>
													<?php if(!empty($gallery)) { 
															foreach($gallery as $img): ?>
																<div class ="control-label col-md-12 col-sm-12">
																	<img src="<?php echo base_url('uploads/img/mcq'.$img->file_name); ?>" alt = 'image' />
																</div>
															<?php endforeach;
														 } ?>
                                                    <div class = "form-group col-md-12 col-sm-12">
                                                        <?php foreach($options as $option):
                                                            $radio = '' ;
                                                            if($option->status){
                                                                $ans = $option->name ;
                                                            }
                                                            foreach($answers as $answer): 
                                                                if($answer->mcq_options_id == $option->id) {
                                                                    if ($option->status) { 
                                                                        $flag = TRUE ;
                                                                        echo "<script> document.getElementById('index'.concat(".$x.")).style.backgroundColor = 'green'; </script>" ;
                                                                   }
                                                                   $radio =  ' checked ' ;
                                                                    break;
                                                                }
                                                            endforeach;?>
                                                            <div class="radio">
                                                            </br>
                                                                <label><input type="radio" name = 'opt<?php echo $x; ?>' <?php echo $radio ; ?> disabled ><?php echo $option->name; ?></label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class = "col-md-12 col-sm-12">
                                                    <?php if ( $flag ) { 
                                                        echo '<h3 style = "color : green ; " >Correct Answer</h3>';
                                                    } else {
                                                        echo '<h2 style = "color : red ; " >Wrong Answer</h2>';
                                                        echo '<span>Correct Answer is '.$ans.'</span>';
                                                    }
                                                    ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="actionBar">
                                        <div class ='loader row'>
                                            <div class = 'col-md-1 col-sm-1'>
                                                <a  style='color : black;'  class="btn btn-info" <?php if($x == 1 ) echo 'disabled'; else{?> onclick='previous(<?php echo $x; ?>)' <?php }?>>Previous</a>  
                                            </div>
                                            <div class = 'col-md-1 col-sm-1 offset-10'>
                                                <a   style='color : black;' class="btn btn-primary" <?php if($x == $n ) echo 'disabled'; else{?> onclick='next(<?php echo $x; ?>)' <?php }?>>Next</a>
                                            </div>
                                        </div>                     
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- /page content -->

                    <!-- footer content -->
                    <footer>
                        <div class="pull-right">
                        </div>
                        <div class="clearfix"></div>
                    </footer>
                    <!-- /footer content -->
                </div>
            <?php } else { ?>
                <form class="form-horizontal form-label-left" id='mcq_form' action = "<?php echo base_url('student/answers/'.$paper->id); ?>" method='post'>
                    <div class="main_container">
                        <div class="col-md-3 left_col">
                            <div class="left_col scroll-view">
                                <div class="navbar nav_title" style="border: 0;">
                                    <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Soft Web</span></a>
                                </div>

                                <div class="clearfix"></div>

                                <!-- menu profile quick info -->
                                <div class="profile clearfix">
                                    <div class="profile_pic">
                                        <img src="<?php echo base_url('assests/admin');?>/images/user.png" alt="..." class="img-circle profile_img">
                                    </div>
                                    <div class="profile_info">
                                        <span>Best Of Luck,</span>
                                        <h2>
                                        <?php
                                            echo ucfirst($user->first_name) . ' ' . ucfirst($user->last_name);
                                        ?>
                                        </h2>
                                    </div>
                                </div>
                                <!-- /menu profile quick info -->

                                <br />

                                <!-- sidebar menu -->
                                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                                    <div class="menu_section">
                                        <h3>Questions Index</h3>
                                        <div class='item form-group row'>
                                            <?php 
                                            $n = $paper->total_questions;
                                            for ($x=1 ; $x <= $n ; $x++ ){?>
                                                    <div id= "index<?php echo $x;?>" class="col-md-2 col-sm-2" style = 'margin : 2px; border : 2px solid black; padding:10px;'>
                                                        <a onclick="change_question(<?php echo $x.','.$n ;?>)"><span class="" style = 'color : black; font-size : 20px; cursor : pointer;'><?php echo $x; ?><span></a> 
                                                    </div>
                                            <?php } ?>
										</div>
										<div style = "margin-top : 35px">
											<button style = "height:70px" type='submit' class="btn btn-primary col-md-12 col-sm-12" >Finish Exam</button>
										</div>	
                                    </div>
                                </div>
                                <div class='sidebar-footer hidden-small'>
                                            <!-- <button type='submit' class="btn btn-success col-md-12 col-sm-12" >End Exam</button> -->
                                </div>
                                <!-- /sidebar menu -->
                            </div>
                        </div>

                        <!-- top navigation -->
                        <div class="top_nav">
                            <div class="nav_menu">
                                <div class="nav toggle">
                                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                                </div>
                            </div>
                        </div>
                        <!-- /top navigation -->

                        <!-- page content -->
                        <div class="right_col" role="main">
                            <div class="">
                                <div class="page-title">
                                    <div class="title_left">
										<h3><?php echo $exam->name;?></h3>
									</div>
                                    <div class="title_right">
                                        <div class=" pull-right">
                                            <h5 id='timer'></h5>
                                            <span id= 'minutes' hidden></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <?php 
                                $x=0;
                                foreach($mcqs as $mcq):
                                    $x++;
                                    $i = 0;
									$options = $this->admin_model->get_data_by_attr_id('mcq_options','mcq_id',$mcq->id);
									$gallery = $this->admin_model->get_data_by_attr_id('gallery_images','mcq_id',$mcq->id);
                                    foreach($temp_answers as $temp_answer):
                                        if ($temp_answer->mcq_id == $mcq->id)
                                            echo "<script> document.getElementById('index'.concat(".$x.")).style.backgroundColor = 'green'; </script>" ;
									endforeach;
									if($selected_mcq == $mcq->id ){
										$display = 'block';
									}  else {
										if(!$selected_mcq && $x == 1) {
											$display = 'block';
										} else {
											$display = 'none';
										} 
									}
                                    ?>
                                    <div  id="mcq_block<?php echo $x; ?>" style="display : <?php echo $display; ?>;">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12  ">
                                                <div class="x_panel">
                                                    <div class="x_title">
                                                        <h2>Question no. <?php echo $x;?></h2>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="x_content">
                                                        <div class ="control-label col-md-12 col-sm-12">
                                                            <span style="font-size : large ; font-weight : bold; "><?php echo $mcq->question;?></span>
														</div>
														<?php if(!empty($gallery)) { 
															foreach($gallery as $img): ?>
																<div class ="control-label col-md-12 col-sm-12">
																	<img src="<?php echo base_url('uploads/img/mcq'.$img->file_name); ?>" alt = 'image' />
																</div>
															<?php endforeach;
														 } ?>
														
                                                        <div class = "form-group col-md-12 col-sm-12">
                                                            <?php foreach($options as $option):
                                                                $i++; ?>
                                                                <div class="radio">
                                                                </br>
                                                                    <label><input type="radio" id='opt<?php echo $i.$x; ?>' name="opt<?php echo $x ?>" value = '<?php echo $option->id; ?>' onchange="highlight_index(<?php echo $x.','.$i; ?>)" <?php
                                                                    foreach($temp_answers as $temp_answer):
                                                                        if($temp_answer->option_id == $option->id){
                                                                            echo 'checked';
                                                                            break;
                                                                        }
                                                                    endforeach;
                                                                    ?>/><?php echo $option->name; ?></label>
                                                                    <span id='option_value<?php echo $x; ?>' hidden></span>
                                                                </div>
                                                            <?php endforeach;?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="actionBar">
                                            <div class ='loader'>
                                                <div class = 'col-md-1 col-sm-1'>
                                                    <a  style='color : black;'  class="btn btn-success" <?php if($x == 1 ) echo 'disabled'; else{?> onclick='previous(<?php echo $x; ?>)' <?php }?>>Previous</a>  
                                                </div>
                                                <div class = 'col-md-1 col-sm-1 offset-10'>
                                                    <a   style='color : black;' class="btn btn-info" <?php if($x == $n ) echo 'disabled'; else{?> onclick='next(<?php echo $x; ?>)' <?php }?>>Next</a>
                                                </div>
                                            </div>                     
                                        </div>
                                    </div>
								<?php endforeach; ?>
                                <div id = 'message' style = 'display : none;'>
                                    <h1 style = 'color : red; text-align : center;'>HURRYUP BUDDY,</br>
                                    YOU HAVE LIMITED TIME.</h1>
                                </div>
                            </div>
                        </div>
                        <!-- /page content -->

                        <!-- footer content -->
                        <footer>
                            <div class="pull-right">
                            </div>
                            <div class="clearfix"></div>
                        </footer>
                        <!-- /footer content -->
                    </div>
                    <script>
						//document.addEventListener("offline", location.reload());
                        var minutes = <?php echo $_SESSION['minutes'.$paper->id]; ?>;
                        document.getElementById('minutes').innerHTML = minutes;
                        var a = <?php echo $_SESSION['seconds'.$paper->id]; ?>;
                        var x = setInterval(function() {
                            if (a < 0){
                                a = 59;
                                minutes = minutes - 1 ;
                                document.getElementById('minutes').innerHTML = minutes;
                            }
                            var hour = parseInt(minutes / 60);
                            document.getElementById('timer').innerHTML = hour + 'h: ' + minutes + 'm : ' + a + 's' ;
                            a = a-1;
                            if(a % 10 == 0){
                                var xhttp = new XMLHttpRequest();
                                xhttp.open("GET", "<?php echo base_url(); ?>student/update_time?min="+minutes+'&sec='+a+'&paper_id=<?php echo$paper->id;?>', true);
                                xhttp.send();
                                
                            }
                            if( minutes < 5){
                                document.getElementById('timer').style.color = 'red' ;
                                document.getElementById('message').style.display = 'block' ;
                            }
                            if (minutes == 0 && a == 0 ) {
                                clearInterval(x);
                                document.getElementById('mcq_form').submit();
                            }
                        }, 1000);
                    </script>
                </form>
            <?php } ?>
            <!-- jQuery -->
            <script src="<?php echo base_url('assests/admin');?>/vendors/jquery/dist/jquery.min.js"></script>
            <!-- Bootstrap -->
            <script src="<?php echo base_url('assests/admin');?>/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <!-- FastClick -->
            <script src="<?php echo base_url('assests/admin');?>/vendors/fastclick/lib/fastclick.js"></script>

            
            <!-- Custom Theme Scripts -->
            <script src="<?php echo base_url('assests/admin');?>/build/js/custom.min.js"></script>
            <script>
                function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
                $(document).on("keydown", disableF5);
                function next(id){
                    id = parseInt(id);
                    document.getElementById('mcq_block'.concat(id)).style.display = 'none';
                    document.getElementById('mcq_block'.concat(id+1)).style.display = 'block';
                }
                function previous(id){
                    id = parseInt(id);
                    document.getElementById('mcq_block'.concat(id)).style.display = 'none';
                    document.getElementById('mcq_block'.concat(id-1)).style.display = 'block';
                }
                function change_question(id,n){
                    id = parseInt(id);
                    n = parseInt(n);
                    for( var i = 1; i<=n; i++){
                        if(i != id){
                            document.getElementById('mcq_block'.concat(i)).style.display = 'none';
                        }
                    }
                    document.getElementById('mcq_block'.concat(id)).style.display = 'block';
                }
                function highlight_index(id,i){
                    document.getElementById('index'.concat(id)).style.backgroundColor = "green";
                    var opt = document.getElementById('opt'.concat(i,id)).value; 
                    document.getElementById('option_value'.concat(id)).innerHTML = opt;
                    var option = document.getElementById('option_value'.concat(id)).innerHTML;
                    var time = document.getElementById('minutes').innerHTML;
                    var xhttp = new XMLHttpRequest();
                    xhttp.open("GET", "<?php echo base_url(); ?>student/temp_answer?option_id="+option+'&time='+time, true);
                    xhttp.send();
                    
                }
            </script>
        </div>
    </body>
</html>

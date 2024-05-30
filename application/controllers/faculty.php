<?php
    class Faculty extends CI_Controller{
        public function __construct()
        {
            parent::__construct();
            $this->load->database();
            $this->load->library(['ion_auth', 'form_validation']);
            $this->load->helper(['url', 'language']);

            $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

            $this->lang->load('auth');
        }
        public function index(){
            if (!$this->ion_auth->in_group(2)){
                redirect(base_url());
            }
            $this->load->view('templates/header');
            $this->load->view('faculty/templates/sidebar');
            $this->load->view('faculty/pages/dashboard');
            $this->load->view('templates/footer');
        }

        public function student(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(2)){
                redirect(base_url());
            }
            $teacher = $this->admin_model->get_data_by_attr_id('faculty' , 'user_id' , $user->id );
            $courses = array();
            $students = array();
            foreach ( $teacher as $faculty ):
                $flag = 1 ;
                $subject = $this->admin_model->get_data_by_id('subject',$faculty->subject_id);
                foreach($courses as $course):
                    if ( $course == $subject->course_id ) {
                        $flag = 0 ;
                        break;
                    }
                endforeach;
                if ( $flag ) {
                    array_push($courses,$subject->course_id);
                    array_push($students , $this->admin_model->get_data_by_attr_id('student','course_id',$subject->course_id));
                }
            endforeach;
            $data['students'] = $students;
            $data['selected_courses'] = $courses;
            $this->load->view('templates/header');
            $this->load->view('faculty/templates/sidebar');
            $this->load->view('faculty/pages/student',$data);
            $this->load->view('templates/footer');
        }

        public function mcq(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(2)){
                redirect(base_url());
			}

			$teacher = $this->admin_model->get_data_by_attr_id('faculty' , 'user_id' , $user->id );
            $courses = array();
            $exams = array();
            $papers = array();
            foreach ( $teacher as $faculty ):
                $flag = 1;
                $subject = $this->admin_model->get_data_by_id('subject',$faculty->subject_id);
                array_push( $papers , $this->admin_model->get_data_by_attr_id('exam_sub','subject_id',$subject->id));
                foreach($courses as $course):
                    if ( $course == $subject->course_id ) {
                        $flag = 0 ;
                        break;
                    }
                endforeach;
                if ( $flag ) {
                    array_push($courses,$subject->course_id);
                    array_push( $exams , $this->admin_model->get_data_by_attr_id('exam','course_id',$subject->course_id) );
                }
            endforeach;
            $data['exam_subjects'] = $papers;
			$data['exams'] = $exams;
			// print_r($exams);
			// die;
			if ( $this->input->post('start_time') && $this->input->post('end_time') ) {
				$origin = new DateTime($this->input->post('start_time'));
				$target = new DateTime($this->input->post('end_time'));
				$interval = $origin->diff($target);
				$minutes = $interval->h * 60;
				$minutes += $interval->i;
				//$minutes = strval($minutes);
				//$this->form_validation->set_rules('duration', 'Duration does not match the defined time differene i.e.' , 'trim|required|matches[' . $minutes . ']');
				$this->form_validation->set_rules('duration', 'Duration does not match the defined time differene i.e.' , 'trim|required|less_than_equal_to[' . $minutes . ']');
			}
			//$max_date= strtotime($this->admin_model->get_data_by_id('exam',$this->input->post('exam'))->end_date);
			$this->form_validation->set_rules('subject', 'Selecting subject' , 'trim|required');
			$this->form_validation->set_rules('exam', 'Selecting Exam' , 'trim|required');
			$this->form_validation->set_rules('total_questions', 'Total questions' , 'trim|required|numeric');
			$this->form_validation->set_rules('each_marks', 'Each marks' , 'trim|required|numeric');
			$this->form_validation->set_rules('start_time', 'Start Time' , 'trim|required');
			$this->form_validation->set_rules('end_time', 'End Time' , 'trim|required');
			$this->form_validation->set_rules('date', 'Examination Date' , 'trim|required');
			if ($this->form_validation->run() === TRUE) {
				$insert_data=array(
                    'duration' => $this->input->post('duration'),
                    'exam_id' => $this->input->post('exam'),
                    'subject_id' => $this->input->post('subject'),
                    'start_time' =>$this->input->post('start_time'),
                    'end_time' =>$this->input->post('end_time'),
                    'date' =>$this->input->post('date'),
                    'total_questions' =>$this->input->post('total_questions'),
                    'each_mark' =>$this->input->post('each_marks'),
                );
			} 
			if ($this->form_validation->run() === TRUE && $this->admin_model->insert('exam_sub',$insert_data) ) { 
				$paper_id = $this->admin_model->get_data_by_2attr_id('exam_sub','subject_id','exam_id',$this->input->post('subject'),$this->input->post('exam'))->row();
				$url = site_url().'faculty/paper?paper_id='.$paper_id->id;
                redirect( $url );
			} else {
				$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$data['total_questions'] = [
					'name' => 'total_questions',
					'type' => 'number',
					'class' => 'form-control',
					'placeholder' => '',
					'value' => $this->form_validation->set_value('total_questions'),
				];
				$data['each_marks'] = [
					'name' => 'each_marks',
					'type' => 'number',
					'class' => 'form-control',
					'placeholder' => '',
					'value' => $this->form_validation->set_value('each_marks'),
				];
				$data['start_time'] = [
					'name' => 'start_time',
					'type' => 'time',
					'class' => 'form-control',
					'placeholder' => '',
					'value' => $this->form_validation->set_value('start_time'),
				];
				$data['end_time'] = [
					'name' => 'end_time',
					'type' => 'time',
					'class' => 'form-control',
					'placeholder' => '',
					'value' => $this->form_validation->set_value('end_time'),
				];
				$data['duration'] = [
					'name' => 'duration',
					'type' => 'number',
					'class' => 'form-control',
					'placeholder' => '',
					'value' => $this->form_validation->set_value('duration'),
				];
				$data['date'] = [
					'name' => 'date',
					'id' => 'date',
					'type' => 'text',
					'min' => date('Y-m-d'),
					'class' => 'date-picker form-control',
					'onfocus' => "this.type='date'" ,
					'onmouseover' => "this.type='date'" ,
					'onclick' => "this.type='date'" ,
					'onblur' => "this.type='text'" ,
					'onmouseout' => "timeFunctionLong(this)",
					'placeholder' => 'Examination Date',
					'value' => $this->form_validation->set_value('date'),
				];
				
				$exam_options = array(
					'' => 'Select Exam',
				);
				foreach($exams as $item):
					foreach($item as $exam): 
						$exam_options[$exam->id] = $exam->name;
					endforeach;
				endforeach; 
				$data['exam_options'] = $exam_options;
				$data['selected_exam'] = $this->form_validation->set_value('exam') ;
				$data['exam'] = [
					'class' => "form-control" ,
					'id' => "exam" ,
					'onchange' => "check_subject()"
				];
				$data['subject_options'] = array();
				$data['selected_subject'] = $this->form_validation->set_value('subject') ;
				$data['subject'] = [
					'class' => "form-control" ,
					'id' => "subject" ,
				];
			}

            if($this->input->post('deleteMCQ')){
                $mcqs = $this->admin_model->get_data_by_attr_id('mcq','exam_sub_id',$this->input->post('id'));
                foreach($mcqs as $mcq):
					$this->admin_model->del_data_by_attr_id('mcq_options','mcq_id',$mcq->id);
					$gallery_ids = $this->admin_model->get_data_by_attr_id('gallery_images','mcq_id',$mcq->id);
					foreach($gallery_ids as $gallery_id) {
						unlink('uploads/img/mcq'.$gallery_id->file_name);
					}
					$this->admin_model->del_data_by_attr_id('gallery_images','mcq_id',$mcq->id);
                endforeach;
                $this->admin_model->del_data_by_attr_id('mcq','exam_sub_id',$this->input->post('id'));
                $this->admin_model->del_data_by_attr_id('result','exam_sub_id',$this->input->post('id'));
                $this->admin_model->del_data_by_attr_id('answers','exam_sub_id',$this->input->post('id'));
                $this->admin_model->del_data('exam_sub',$this->input->post('id'));
                redirect('faculty/mcq');
            }
           
            if($this->input->get('update')){
                $update_id = $this->input->get('id');
                $update_data=array(
                    'duration' => $this->input->post('duration'.$update_id),
                    'start_time' =>$this->input->post('start_time'.$update_id),
                    'end_time' =>$this->input->post('end_time'.$update_id),
                    'date' =>$this->input->post('date'.$update_id),
                    'each_mark' =>$this->input->post('each_marks'.$update_id),
                );
                $this->admin_model->update('exam_sub',$update_id,$update_data);
                redirect('faculty/mcq');
            }
            $this->load->view('templates/header');
            $this->load->view('faculty/templates/sidebar');
            $this->load->view('faculty/pages/mcq',$data);
            $this->load->view('templates/footer');
        }
        public function paper(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(2)){
                redirect(base_url());
            }
            $paper_id = $this->input->get('id');
            if(empty($paper_id)){
                $paper_id = $this->input->post('paper_id');
            }
            if(empty($paper_id)){
                $paper_id = $this->input->get('paper_id');
            }
            $paper = $this->admin_model->get_data_by_id('exam_sub',$paper_id);
			$exam = $this->admin_model->get_data_by_id('exam',$paper->exam_id);
            if($this->admin_model->get_data_by_id('course',$exam->course_id)->admin_id != $user->reference_id || $this->admin_model->get_data_by_2attr_id('faculty','subject_id','user_id',$paper->subject_id,$user->id)->num_rows() == 0) {
                redirect(base_url());
            }
            if($this->input->post('insert')){
                $n= (int)$this->input->post('n');
                $insert_data = array();
                for ( $x = 1; $x <= $n; $x++ ) {
                   	$ques = 'ques'.$x;
                    $ans =  'ans'.$x;
                    $insert_data=array(
                        'exam_sub_id' => $paper_id,
                        'question' => $this->input->post($ques),
                    );
					$insert = $this->admin_model->insert('mcq',$insert_data);
					$galleryID = $insert; 
                    $mcq = $this->admin_model->get_data_by_2attr_id('mcq','exam_sub_id','question',$paper_id,$this->input->post($ques))->row();
                    for($i=1 ; $i<=4 ; $i++){
                        if($this->input->post($ans) == $i){
                            $insert_option=array(
                                'mcq_id' => $mcq->id,
                                'name' => $this->input->post('opt'.$i.$x),
                                'status' => 1
                            );
                            $this->admin_model->insert('mcq_options',$insert_option);
                            $insert_option=array();
                        }else{
                            $insert_option=array(
                                'mcq_id' => $mcq->id,
                                'name' => $this->input->post('opt'.$i.$x),
                            );
                            $this->admin_model->insert('mcq_options',$insert_option);
                            $insert_option=array();
                        }
                    }
                    if($insert){
						if(!empty($_FILES['img'.$x]['name'])){ 
							$filesCount = count($_FILES['img'.$x]['name']); 
							for($i = 0; $i < $filesCount; $i++){ 
								$_FILES['file']['name']     = $_FILES['img'.$x]['name'][$i]; 
								$_FILES['file']['type']     = $_FILES['img'.$x]['type'][$i]; 
								$_FILES['file']['tmp_name'] = $_FILES['img'.$x]['tmp_name'][$i]; 
								$_FILES['file']['error']    = $_FILES['img'.$x]['error'][$i]; 
								$_FILES['file']['size']     = $_FILES['img'.$x]['size'][$i]; 
								 
								// File upload configuration 
								$uploadPath = 'uploads/img/mcq'; 
								$config['upload_path'] = $uploadPath; 
								$config['allowed_types'] = 'jpg|jpeg|png|gif'; 
								$newname = $galleryID.$i.$_FILES['img'.$x]['name'][$i]; 
								$config['file_name'] = $newname;
								// Load and initialize upload library 
								$this->load->library('upload', $config); 
								$this->upload->initialize($config); 
								// Upload file to server 
								if($this->upload->do_upload('file')){ 
									// Uploaded file data 
									$fileData = $this->upload->data(); 
									$uploadData[$i]['mcq_id'] = $galleryID; 
									$uploadData[$i]['file_name'] = $newname; 
									//$uploadData[$i]['uploaded_on'] = date("Y-m-d H:i:s"); 
								} else { 
									//$errorUpload .= $fileImages[$key].'('.$this->upload->display_errors('', '').') | ';  
								} 
								
							} 
							 
							// File upload error message 
							$errorUpload = !empty($errorUpload)?' Upload Error: '.trim($errorUpload, ' | '):''; 
							if(!empty($uploadData)){ 
								// Insert files info into the database 
								$this->admin_model->insert_image($uploadData); 
							} 
							
						} 
						$this->session->set_userdata('success_msg', 'Gallery has been added successfully.'.$errorUpload); 
					} else {
						$data['error_msg'] = 'Some problems occurred, please try again.'; 
					}
					$insert_data = array();
					$uploadData = array();
                }
                redirect('faculty/mcq');
            }
            if($this->input->post('update')){
                $n= $this->input->post('n');
                $x = 0;
                $mcqs = $this->admin_model->get_data_by_attr_id('mcq','exam_sub_id',$paper_id);
                foreach ($mcqs as $mcq):
                    $x= $x +1;
                    $n = 1;
                    $ques = 'ques'.$x;
                    $ans =  'ans'.$x;
                    $update_id = $mcq->id;
                    $update_data=array(
                        'exam_sub_id' => $paper_id,
                        'question' => $this->input->post($ques),
                    );
					$test = $this->admin_model->update('mcq',$update_id,$update_data);
                    $options = $this->admin_model->get_data_by_attr_id('mcq_options','mcq_id',$mcq->id);
					$gallery = $this->admin_model->get_data_by_attr_id('gallery_images','mcq_id',$mcq->id);
					$galleryID = $update_id;
					foreach ($options as $option):
                        if($this->input->post($ans) == $n){
                            $update_option=array(
                                'name' => $this->input->post('opt'.$n.$x),
                                'status' => 1,
                            );
                            $this->admin_model->update('mcq_options',$option->id,$update_option);
                            $update_option=array();
                        }else{
                            if($option->status == 1){
                                $update_option=array(
                                    'name' => $this->input->post('opt'.$n.$x),
                                    'status' => 0
                                );
                                $this->admin_model->update('mcq_options',$option->id,$update_option);
                                $update_option=array();
                            }else{
                                $update_option=array(
                                    'name' => $this->input->post('opt'.$n.$x),
                                );
                                $this->admin_model->update('mcq_options',$option->id,$update_option);
                                $update_option=array();
                            }
                        }
                        $n =$n+1;
					endforeach;
					if($test) {
						// foreach($gallery as $item):
						// 	unlink('uploads/img/'.$item->file_name);
						// 	$this->admin_model->del_data('gallery_images',$item->id);
						// endforeach;
						if(!empty($_FILES['img'.$x]['name'])){ 
							$filesCount = count($_FILES['img'.$x]['name']); 
							for($i = 0; $i < $filesCount; $i++){ 
								$_FILES['file']['name']     = $_FILES['img'.$x]['name'][$i]; 
								$_FILES['file']['type']     = $_FILES['img'.$x]['type'][$i]; 
								$_FILES['file']['tmp_name'] = $_FILES['img'.$x]['tmp_name'][$i]; 
								$_FILES['file']['error']    = $_FILES['img'.$x]['error'][$i]; 
								$_FILES['file']['size']     = $_FILES['img'.$x]['size'][$i]; 
								 
								// File upload configuration 
								$uploadPath = 'uploads/img/mcq'; 
								$config['upload_path'] = $uploadPath; 
								$config['allowed_types'] = 'jpg|jpeg|png|gif'; 
								$newname = $galleryID.$i.$_FILES['img'.$x]['name'][$i]; 
								$config['file_name'] = $newname;
								// Load and initialize upload library 
								$this->load->library('upload', $config); 
								$this->upload->initialize($config); 
								// Upload file to server 
								if($this->upload->do_upload('file')){ 
									// Uploaded file data 
									$fileData = $this->upload->data(); 
									$uploadData[$i]['mcq_id'] = $galleryID; 
									$uploadData[$i]['file_name'] = $newname; 
									//$uploadData[$i]['uploaded_on'] = date("Y-m-d H:i:s"); 
								} else { 
									//$errorUpload .= $fileImages[$key].'('.$this->upload->display_errors('', '').') | ';  
								} 
								
							} 
							 
							// File upload error message 
							$errorUpload = !empty($errorUpload)?' Upload Error: '.trim($errorUpload, ' | '):''; 
							if(!empty($uploadData)){ 
								// Insert files info into the database 
								$this->admin_model->insert_image($uploadData); 
							} 
							
						} 
					}
                endforeach;
                redirect('faculty/mcq');
            }
            $data['n'] = $this->admin_model->get_data_by_id('exam_sub',$paper_id)->total_questions;
            $data['mcqs'] = $this->admin_model->get_data_by_attr_id('mcq','exam_sub_id',$paper_id);
            $this->load->view('templates/header');
            $this->load->view('faculty/templates/sidebar');
            $this->load->view('faculty/pages/mcq_paper',$data);
            $this->load->view('templates/footer');
        }
        public function result(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(2)){
                redirect(base_url());
			}
			$selected_result =array();
			$teacher = $this->admin_model->get_data_by_attr_id('faculty' , 'user_id' , $user->id );
			foreach($teacher as $item):
				$papers = $this->admin_model->get_data_by_attr_id('exam_sub','subject_id',$item->subject_id);
				foreach($papers as $paper):
					$selected_result = $this->admin_model->get_data_by_attr_id('result','exam_sub_id',$paper->id);
				endforeach;
			endforeach;
            $data['results'] = $selected_result;
            $this->load->view('templates/header');
            $this->load->view('faculty/templates/sidebar');
            $this->load->view('faculty/pages/result',$data);
            $this->load->view('templates/footer');
		}
		public function user_profile() {
            if (!$this->ion_auth->in_group(2)){
                redirect(base_url());
            }
			$data['user'] = $this->ion_auth->user()->row();
			$teachers = $this->admin_model->get_data_by_attr_id('faculty','user_id',$data['user']->id);
			foreach($teachers as $teacher):
				$subject[] = $this->admin_model->get_data_by_id('subject',$teacher->subject_id);
			endforeach;
			$data['subjects'] = $subject;
			$this->load->view('templates/header');
            $this->load->view('faculty/templates/sidebar');
            $this->load->view('faculty/pages/user_profile',$data);
            $this->load->view('templates/footer');
		}
        
    }
?>

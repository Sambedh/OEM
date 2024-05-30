<?php
    class Admin extends CI_Controller{
        
            
        public function __construct()
        {
            
            parent::__construct();
            $this->load->database();
            $this->load->library(['ion_auth', 'form_validation']);
            $this->load->helper(['url', 'language']);

            $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

			$this->lang->load('auth');  
			$this->user_index = isset($_SESSION['user_index']) ? $_SESSION['user_index'] : '';
        }
        public function index(){
            if (!$this->ion_auth->in_group(5)){
                redirect(base_url());
            }
            $this->load->view('templates/header');
            $this->load->view('admin/templates/sidebar');
            $this->load->view('admin/pages/dashboard');
            $this->load->view('templates/footer');
        }

        public function course() {
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(5)){
                redirect(base_url());
            }
			$this->form_validation->set_rules('name', 'Course Name' , 'trim|required');
			$this->form_validation->set_rules('desc', 'Description' , 'trim|required');
			if ($this->form_validation->run() === TRUE) {
				$insert_data=array(
					'name' => $this->input->post('name'),
					'description' => $this->input->post('desc'),
					'admin_id' =>  $this->ion_auth->user()->row()->id,
				);
			} 
			if ($this->form_validation->run() === TRUE &&  $this->admin_model->insert('course',$insert_data)) {
				redirect('admin/course');
			} else {
				$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$data['course'] = [
					'name' => 'name',
					'type' => 'text',
					'class' => 'form-control',
					'placeholder' => 'Course Name',
					'value' => $this->form_validation->set_value('name'),
				];
				$data['desc'] = [
					'name' => 'desc',
					'class' => 'form-control',
					'rows' => '3',
					'placeholder' => 'About Course',
					'value' => $this->form_validation->set_value('desc'),
				];
			}
            if($this->input->get('update')){
                $update_id = $this->input->get('id');
                $update_data=array(
                    'name' => $this->input->post('name'.$update_id),
                    'description' =>$this->input->post('desc'.$update_id),
                );
                $this->admin_model->update('course',$update_id,$update_data);
                redirect('admin/course');
            }
            if($this->input->post('deleteCourse')){
				$subjects = $this->admin_model->get_data_by_attr_id('subject','course_id',$this->input->post('id'));
				$exams = $this->admin_model->get_data_by_attr_id('exam','course_id',$this->input->post('id'));
				if( empty($subjects) && empty($exams)) {
					$this->admin_model->del_data('course',$this->input->post('id'));	
					$this->admin_model->del_data_by_attr_id('student','course_id',$this->input->post('id'));
				} else {
					$name = $this->admin_model->get_data_by_id('course',$this->input->post('id'))->name;
					$_SESSION['delete_error'] = 'Unable to delete '.$name.' . Please delete data related to the '.$name.' first.';
				}
				redirect('admin/course');
			}
			$data['courses'] = $this->admin_model->get_data_by_attr_id('course','admin_id',$user->id);
            $this->load->view('templates/header');
            $this->load->view('admin/templates/sidebar');
            $this->load->view('admin/pages/course',$data);
            $this->load->view('templates/footer');
        }
        public function subject(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(5)){
                redirect(base_url());
			}
			$courses = $this->admin_model->get_data_by_attr_id('course','admin_id',$user->id);
			$this->form_validation->set_rules('course', 'Selecting course' , 'trim|required');
			$this->form_validation->set_rules('name', 'Subject Name' , 'trim|required');
			$this->form_validation->set_rules('desc', 'Description' , 'trim');
			if ($this->form_validation->run() === TRUE) {
				$insert_data=array(
                    'name' => $this->input->post('name'),
                    'course_id' => $this->input->post('course'),
                    'description' =>$this->input->post('desc'),
                );
			} 
			if ($this->form_validation->run() === TRUE && $this->admin_model->insert('subject',$insert_data) ) { 
				redirect('admin/subject');
			} else {
				$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$data['subject'] = [
					'name' => 'name',
					'type' => 'text',
					'class' => 'form-control',
					'placeholder' => 'Subject Name',
					'value' => $this->form_validation->set_value('name'),
				];
				$data['desc'] = [
					'name' => 'desc',
					'class' => 'form-control',
					'rows' => '3',
					'placeholder' => 'About subject (optional)',
					'value' => $this->form_validation->set_value('desc'),
				];
				$course_options = array();
				foreach($courses as $course):
					$course_options[$course->id]= $course->name;
				endforeach;
				$data['course_options'] = $course_options;
				$data['selected_course'] = $this->form_validation->set_value('course') ;
				$data['course'] = [
					'class' => "form-control" ,
				];
			}
            if($this->input->get('update')){
                $update_id = $this->input->get('id');
                $update_data=array(
                    'name' => $this->input->post('name'.$update_id),
                    'description' =>$this->input->post('desc'.$update_id),
                    'course_id' => $this->input->post('course'.$update_id),
                );
                $this->admin_model->update('subject',$update_id,$update_data);
                redirect('admin/subject');
            }
            if($this->input->post('deleteSubject')){
                $papers = $this->admin_model->get_data_by_attr_id('exam_sub','subject_id',$this->input->post('id'));
                if(empty($papers)) {
					$this->admin_model->del_data('subject',$this->input->post('id'));
					$this->admin_model->del_data_by_attr_id('faculty','subject_id',$this->input->post('id'));
				} else {
					$name = $this->admin_model->get_data_by_id('subject',$this->input->post('id'))->name;
					$_SESSION['delete_error'] = 'Unable to delete '.$name.' . Please delete data related to this '.$name.' first.';
				}
                redirect('admin/subject');
            }
            $subjects = array();
            foreach ($courses as $course ):
                array_push($subjects,$this->admin_model->get_data_by_attr_id('subject','course_id',$course->id));
            endforeach;
			$data['subjects'] = $subjects;
			$data['courses'] = $courses;
            $this->load->view('templates/header');
            $this->load->view('admin/templates/sidebar');
            $this->load->view('admin/pages/subject',$data);
            $this->load->view('templates/footer');
        }
        public function exam(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(5)){
                redirect(base_url());
			}
			$courses = $this->admin_model->get_data_by_attr_id('course','admin_id',$user->id);
			$this->form_validation->set_rules('course', 'Selecting course' , 'trim|required');
			$this->form_validation->set_rules('name', 'Exam Name' , 'trim|required');
			$this->form_validation->set_rules('start_date', 'Start Date' , 'trim|required');
			$this->form_validation->set_rules('end_date', 'End Date' , 'trim|required');
			if ($this->form_validation->run() === TRUE) {
				$insert_data=array(
                    'name' => $this->input->post('name'),
                    'course_id' => $this->input->post('course'),
                    'start_date' =>$this->input->post('start_date'),
                    'end_date' =>$this->input->post('end_date'),
                );
			} 
			if ($this->form_validation->run() === TRUE && $this->admin_model->insert('exam',$insert_data) ) { 
				redirect('admin/exam');
			} else {
				$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$data['exam'] = [
					'name' => 'name',
					'type' => 'text',
					'class' => 'form-control',
					'placeholder' => 'Examination Name',
					'value' => $this->form_validation->set_value('name'),
				];
				$data['start_date'] = [
					'name' => 'start_date',
					'type' => 'text',
					'min' => date('Y-m-d'),
					'class' => 'date-picker form-control',
					'onfocus' => "this.type='date'" ,
					'onmouseover' => "this.type='date'" ,
					'onclick' => "this.type='date'" ,
					'onblur' => "this.type='text'" ,
					'onmouseout' => "timeFunctionLong(this)",
					'placeholder' => 'Starting From',
					'value' => $this->form_validation->set_value('start_date'),
				];
				$data['end_date'] = [
					'name' => 'end_date',
					'type' => 'text',
					'min' => date('Y-m-d'),
					'class' => 'date-picker form-control',
					'onfocus' => "this.type='date'" ,
					'onmouseover' => "this.type='date'" ,
					'onclick' => "this.type='date'" ,
					'onblur' => "this.type='text'" ,
					'onmouseout' => "timeFunctionLong(this)",
					'placeholder' => 'Ending In',
					'value' => $this->form_validation->set_value('end_date'),
				];
				$course_options = array();
				foreach($courses as $course):
					$course_options[$course->id]= $course->name;
				endforeach;
				$data['course_options'] = $course_options;
				$data['selected_course'] = $this->form_validation->set_value('course') ;
				$data['course'] = [
					'class' => "form-control" ,
				];
			}
            if($this->input->get('update')){
                $update_id = $this->input->get('id');
                $update_data=array(
                    'name' => $this->input->post('name'.$update_id),
                    'course_id' => $this->input->post('course'.$update_id),
                    'start_date' =>$this->input->post('start_date'.$update_id),
                    'end_date' =>$this->input->post('end_date'.$update_id),
                );
                $this->admin_model->update('exam',$update_id,$update_data);
                redirect('admin/exam');
            }
            if($this->input->post('deleteExam')) {
                $papers = $this->admin_model->get_data_by_attr_id('exam_sub','exam_id',$this->input->post('id'));
                if(empty($papers)) {
					$this->admin_model->del_data('exam',$this->input->post('id'));
				} else {
					$name = $this->admin_model->get_data_by_id('exam',$this->input->post('id'))->name;
					$_SESSION['delete_error'] = 'Unable to delete '.$name.' . Please delete data related to this '.$name.' first.';
				}
                redirect('admin/exam');
            }
            $exams = array();
            foreach ( $courses as $course ):
                array_push($exams,$this->admin_model->get_data_by_attr_id('exam','course_id',$course->id));
            endforeach;
			$data['exams'] = $exams;
			$data['courses'] = $courses;
            $this->load->view('templates/header');
            $this->load->view('admin/templates/sidebar');
            $this->load->view('admin/pages/exam',$data);
            $this->load->view('templates/footer');
        }
        public function mcq(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(5)){
                redirect(base_url());
			}
			$courses = $this->admin_model->get_data_by_attr_id('course','admin_id',$user->id);
            $exams = array();
            $paper =  array();
            foreach ( $courses as $course ):
                array_push($exams,$this->admin_model->get_data_by_attr_id('exam','course_id',$course->id));
            endforeach;
			if ( $this->input->post('start_time') && $this->input->post('end_time') ) {
				$origin = new DateTime($this->input->post('start_time'));
				$target = new DateTime($this->input->post('end_time'));
				$interval = $origin->diff($target);
				$minutes = $interval->h * 60;
				$minutes += $interval->i;
				$this->form_validation->set_rules('duration', 'Duration' , 'trim|required|less_than_equal_to['.$minutes.']');
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
                $url = site_url().'admin/paper?paper_id='.$paper_id->id;
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
                redirect('admin/mcq');
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
                redirect('admin/mcq');
            }
            foreach($exams as $item):
                foreach($item as $exam):
                    array_push($paper,$this->admin_model->get_data_by_attr_id('exam_sub','exam_id',$exam->id));
                endforeach;
            endforeach;
            $data['exams'] = $exams;
            $data['exam_subjects'] = $paper;
            $data['subjects'] = $this->admin_model->get_data('subject');
            $this->load->view('templates/header');
            $this->load->view('admin/templates/sidebar');
            $this->load->view('admin/pages/mcq',$data);
            $this->load->view('templates/footer');
        }
        public function result(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(5)){
                redirect(base_url());
            }
			$results = $this->admin_model->get_data('result');
            $selected_result = array();
            foreach ($results as $result):
                if ($this->admin_model->get_data_by_id('users',$result['user_id'])->reference_id == $user->id){
                    $selected_result[] = $result;
                }
			endforeach;
            $data['results'] = $selected_result ;
            $this->load->view('templates/header');
            $this->load->view('admin/templates/sidebar');
            $this->load->view('admin/pages/result',$data);
            $this->load->view('templates/footer');
        }

        public function paper(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(5)){
                
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
            if($this->admin_model->get_data_by_id('course',$exam->course_id)->admin_id != $user->id) {
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
                redirect('admin/mcq');
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
                redirect('admin/mcq');
            }
            $data['n'] = $this->admin_model->get_data_by_id('exam_sub',$paper_id)->total_questions;
            $data['mcqs'] = $this->admin_model->get_data_by_attr_id('mcq','exam_sub_id',$paper_id);
            $this->load->view('templates/header');
            $this->load->view('admin/templates/sidebar');
            $this->load->view('admin/pages/mcq_paper',$data);
            $this->load->view('templates/footer');
        }

        public function user(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(5)){
                redirect(base_url());
            }
            $this->data['title'] = $this->lang->line('index_heading');
			
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
            //$this->data['users'] = $this->ion_auth->users()->result();
            $this->data['users'] = $this->admin_model->get_data_by_attr_id('users','reference_id',$user->id);
			$this->data['courses'] = $this->admin_model->get_data_by_attr_id('course','admin_id',$user->id);
			//USAGE NOTE - you can do more complicated queries like this
			//$this->data['users'] = $this->ion_auth->where('field', 'value')->users()->result();
			
			foreach ($this->data['users'] as $k => $user):
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			endforeach;
            $this->load->view('templates/header');
            $this->load->view('admin/templates/sidebar');
            $this->load->view('auth/index', $this->data);
            $this->load->view('templates/footer');
			//$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
		
		public function delete_image() {
			$id = isset($_POST['gallery_id'])?$_POST['gallery_id'] : '';
			if(!empty($id)){
				$gallery = $this->admin_model->get_data_by_id('gallery_images',$id);
				if(!empty($gallery)) {
					unlink('uploads/img/mcq'.$gallery->file_name);
					$this->admin_model->del_data('gallery_images',$id);
				}
			}
		}

		public function ret_sub(){
            $id = isset($_GET["exam_id"])?$_GET["exam_id"]:" ";
            $exam = $this->admin_model->get_data_by_id('exam',$id);
            $subjects = $this->admin_model->get_data_by_attr_id('subject','course_id',$exam->course_id);
			$exam_subs = $this->admin_model->get_data('exam_sub');
			$existing_paper = array();
			foreach($exam_subs as $item):
				$existing_paper[] = $item['subject_id'];
			endforeach;
			
            if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group(5)) {
                $faculties = $this->admin_model->get_data_by_attr_id('faculty','user_id',$this->ion_auth->user()->row()->id);
                foreach($subjects as $subject):
					foreach($faculties as $faculty):
						if($faculty->subject_id == $subject->id){
							if (in_array($subject->id,$existing_paper) ) {
								echo '<option disabled value='.$subject->id.' > '.$subject->name.'</option>';
							} else {
								echo '<option  value='.$subject->id.' > '.$subject->name.'</option>';
							}
						}
					endforeach;
                endforeach;
            } else {
                foreach($subjects as $subject):
					if (in_array($subject->id,$existing_paper)){
						echo '<option disabled value='.$subject->id.' > '.$subject->name.'</option>';
					} else {
						echo '<option  value='.$subject->id.' > '.$subject->name.'</option>';
					}
                endforeach;
			}
        }

        public function get_sub(){
            $ids = explode(",", isset($_GET["course_id"])?$_GET["course_id"]:" ");
            foreach($ids as $id):
                $subjects = $this->admin_model->get_data_by_attr_id('subject','course_id',$id);
                foreach($subjects as $subject):
                    echo '<option  value='.$subject->id.' > '.$subject->name.'</option>';
                endforeach; 
            endforeach;
        }

		public function get_users(){
			$this->n =  isset($_POST['rows']) ? $_POST['rows'] : '';
			$course = isset($_POST['course']) ? $_POST['course'] : '';
			$role = isset($_POST['role']) ? $_POST['role'] : '';
			$this->action =  isset($_POST['action']) ? $_POST['action'] : '';
			if($this->ion_auth->is_admin()) {
				$company = isset($_POST['company']) ? $_POST['company'] : '';
				if(!empty($company) && !empty($role) ){ 
					if($role == 5 ){
						$reference = 'id';
					} else {
						$reference = 'reference_id';
					}
					$users = array();
					foreach ($this->admin_model->get_data_by_attr_id('users',$reference,$company) as $item):
						if($this->ion_auth->in_group($role,$item->id)){
							$users[] = $item;
						}
					endforeach;
					$this->user_tbody($users);
					return;
				}
				if(!empty($company) && empty($course)){
					$this->user_tbody( $this->admin_model->get_data_by_attr_id('users','reference_id',$company));
					return;
				}
				$this->user_data($course,$role);	
			} elseif($this->ion_auth->in_group(5)) {
				if(!empty($course)) {
					if(empty($this->admin_model->get_data_by_2attr_id('course','id','admin_id',$course,$_SESSION['user_id'])->result())) {
						return;
					}
				}
				if(!empty($role) && ($role != 2 && $role != 3) ){
					return;
				}
				$this->user_data($course,$role);
			}	
		}

		public function user_data($course,$role) {
			if (empty($course) && !empty($role)) {
				if($this->ion_auth->is_admin()) {
					$this->user_tbody($this->ion_auth->users($role)->result());
				} else {
					$users = array();
					foreach ($this->admin_model->get_data_by_attr_id('users','reference_id',$_SESSION['user_id']) as $item):
						if($this->ion_auth->in_group($role,$item->id)){
							$users[] = $item;
						}
					endforeach;
					$this->user_tbody($users);
				}
			} elseif (empty($role) && !empty($course)){
				$users = array();
				$students = $this->admin_model->get_data_by_attr_id('student','course_id',$course);
				foreach($students as $student):
					$users[] = $this->admin_model->get_data_by_id('users',$student->user_id);
				endforeach;
				$subjects = $this->admin_model->get_data_by_attr_id('subject','course_id',$course);
				$ids = array();
				foreach ($subjects as $subject):
					$teachers = $this->admin_model->get_data_by_attr_id('faculty','subject_id',$subject->id);
					foreach($teachers as $teacher):
						if(!in_array($teacher->user_id , $ids)) {
							$users[] = $this->admin_model->get_data_by_id('users',$teacher->user_id);
							$ids[] = $teacher->user_id;
						}
					endforeach;
				endforeach;
				$this->user_tbody($users);
			} elseif(!empty($role) && !empty($course)) {
				$users = array();
				if($role == 3) {
					$students = $this->admin_model->get_data_by_attr_id('student','course_id',$course);
					foreach($students as $student):
						$users[] = $this->ion_auth->user($student->user_id)->row();
					endforeach;
				} elseif($role == 2) {
					$subjects = $this->admin_model->get_data_by_attr_id('subject','course_id',$course);
					$ids = array();
					foreach ($subjects as $subject):
						$teachers = $this->admin_model->get_data_by_attr_id('faculty','subject_id',$subject->id);
						foreach($teachers as $teacher):
							if(!in_array($teacher->user_id , $ids)){
								$users[] = $this->ion_auth->user($teacher->user_id)->row();
								$ids[] = $teacher->user_id;
							}
						endforeach;
					endforeach;
				}
				$this->user_tbody($users);
			} else {
				if($this->ion_auth->is_admin()) {
					$this->user_tbody($this->ion_auth->users()->result());
				} elseif($this->ion_auth->in_group(5)) {
					$this->user_tbody($this->admin_model->get_data_by_attr_id('users','reference_id',$_SESSION['user_id']));
				}
			}
		}

		public function user_tbody($users){
			if(!empty($users)) {
				$abc = $users ;
				foreach ($users as $k => $user):
					$users[$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
				endforeach;
				$num_users = count($abc);
				if(empty($this->action)){
					$this->user_index = '';
				}
				if($this->action == 'previous') {
					$this->user_index -= $_SESSION['rows'];
					if($this->user_index -  $this->n > 0){
						$i = $this->user_index -$this->n;
						echo'<span hidden id = "track">previous</span>';
					} else {
						$i = 0;
						$this->user_index = $this->n;
						echo'<span hidden id = "track">disableprevious</span>';
					}
					$this->session->set_flashdata('user_index',$this->user_index);
				} else {
					if(!empty($this->user_index)){
						$i = $this->user_index;
						if ($this->user_index +$this->n > $num_users){
							$this->user_index = $num_users;
							echo'<span  hidden id = "track">disablenext</span>';
						} else {
							$this->user_index += $this->n ;
							echo'<span  hidden id = "track"></span>';
						}	
					} else {
						$i = 0;
						if ($this->n > $num_users){
							$this->user_index = $num_users;
							echo'<span hidden id = "track">disable</span>';
						} else {
							$this->user_index = $this->n;
							echo'<span  hidden id = "track">disableprevious</span>';
						}
					}
					$this->session->set_flashdata('user_index',$this->user_index);
				}
				$this->session->set_flashdata('rows',($this->user_index - $i));
				for($i ; $i<$this->user_index ; $i++){
					echo '<tr>' ;
						echo '<td>'.htmlspecialchars($abc[$i]->first_name,ENT_QUOTES,'UTF-8').'</td>';
						echo '<td>'.htmlspecialchars($abc[$i]->last_name,ENT_QUOTES,'UTF-8').'</td>';
						echo '<td>'.htmlspecialchars($abc[$i]->email,ENT_QUOTES,'UTF-8').'</td>';
						echo "<td>";
						if($this->ion_auth->is_admin()){
							foreach ($abc[$i]->groups as $group):
								echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;
							endforeach;
							echo '<td>'.htmlspecialchars($abc[$i]->subscription,ENT_QUOTES,'UTF-8').'</td>';
						} else {
							print_r($this->ion_auth->get_users_groups($abc[$i]->id)->row()->name);
						}
						echo "</td>";
						$status = ($abc[$i]->active) ? anchor("auth/deactivate/".$abc[$i]->id, lang('index_active_link')) : anchor("auth/activate/". $abc[$i]->id, lang('index_inactive_link'));
						echo '<td>'.$status.'</td>';
						echo '<td>';
						echo '<a href="'.site_url("auth/edit_user/".$abc[$i]->id).'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>';
						echo '</td>';
					echo '</tr>';
				}
			}
		}

		public function user_profile() {
            if (!$this->ion_auth->in_group(5)){
                redirect(base_url());
            }
			$data['user'] = $this->ion_auth->user()->row();
			$this->load->view('templates/header');
            $this->load->view('admin/templates/sidebar');
            $this->load->view('admin/pages/user_profile',$data);
            $this->load->view('templates/footer');
		}

		public function ret_date(){
			if (!$this->ion_auth->in_group(5) && !$this->ion_auth->in_group(2)){
                redirect(base_url());
			}
            $id = isset($_GET["exam_id"])?$_GET["exam_id"]:" ";
		
			$exam = $this->admin_model->get_data_by_id('exam',$id);
			
			echo '<input type = "date" name = "date" id = "date" min = "'.$exam->start_date.'" max = "'.$exam->end_date.'" class = "date-picker form-control"  placeholder ="Starting Date"  >';
		}
		
		public function update_dp(){
			if(!empty($_FILES['user_img']['name'])) {
				$user =  $this->ion_auth->user()->row();
				if(!empty($user->image)){
					unlink('uploads/users/'.$user->image);
				}
				$_FILES['file']['name']     = $_FILES['user_img']['name']; 
				$_FILES['file']['type']     = $_FILES['user_img']['type']; 
				$_FILES['file']['tmp_name'] = $_FILES['user_img']['tmp_name']; 
				$_FILES['file']['error']    = $_FILES['user_img']['error']; 
				$_FILES['file']['size']     = $_FILES['user_img']['size']; 
				$uploadPath = 'uploads/img/users'; 
				$config['upload_path'] = $uploadPath; 
				$config['allowed_types'] = 'jpg|jpeg|png|gif'; 
				$newname = $user->id.$_FILES['user_img']['name']; 
				$config['file_name'] = $newname;
				// Load and initialize upload library 
				$this->load->library('upload', $config); 
				$this->upload->initialize($config); 
				// Upload file to server 
				if($this->upload->do_upload('file')){ 
					// Uploaded file data 
					$fileData = $this->upload->data(); 
					$uploadData['image'] = $newname; 

				}
				if(!empty($uploadData)){
					$this->admin_model->update('users',$user->id,$uploadData);
				}
			}
			redirect(base_url());
		}
    }
?>

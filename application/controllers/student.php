<?php
    class Student extends CI_Controller{
        public function __construct()
        {
            parent::__construct();
            $this->load->database();
            $this->load->library(['ion_auth', 'form_validation']);
            $this->load->helper(['url', 'language']);

            $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

            $this->lang->load('auth');
			date_default_timezone_set('Asia/Kathmandu');
			
			$this->check_temp_answer();
            
        }
        public function index()	{
            if (!$this->ion_auth->in_group(3)){
                redirect(base_url());
			}
            $this->load->view('templates/header');
            $this->load->view('student/templates/sidebar');
            $this->load->view('student/pages/dashboard');
            $this->load->view('templates/footer');
        }

        public function check_temp_answer() {
            $temp_answers = $this->admin_model->get_data_by_attr_id('temp_answers','user_id',$_SESSION['user_id']);
			if(!empty($temp_answers)) {
				foreach($temp_answers as $temp_answer):
					$paper = $this->admin_model->get_data_by_id('exam_sub',$temp_answer->exam_sub_id);
					if(date('Y-m-dH:i:s') > $paper->date.$paper->end_time) {
						$this->answers($paper->id);
					}
				endforeach;
			}
        }

        public function exam(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(3)){
                redirect(base_url());
            }
            $student = $this->admin_model->get_data_by_attr_id('student','user_id',$user->id);
            
            if($student){
                $exams = $this->admin_model->get_data_by_attr_id('exam','course_id',$student[0]->course_id);
            } else {
                $exams = array();
            }
            $data['exams'] = $exams;
            $data['user'] = $user;
            $this->load->view('templates/header');
            $this->load->view('student/templates/sidebar');
            $this->load->view('student/pages/exam',$data);
            $this->load->view('templates/footer');
        }
        public function paper($id){
            $user = $this->ion_auth->user()->row();
			$data['paper'] = $this->admin_model->get_data_by_id('exam_sub',$id);
			
            if(empty($data['paper']) || !$this->ion_auth->in_group(3) ){
                redirect(base_url());
			}
            $student = $this->admin_model->get_data_by_attr_id('student','user_id',$user->id);
            $data['exam'] = $this->admin_model->get_data_by_id('exam',$data['paper']->exam_id);
            $data['result'] = $this->admin_model->get_data_by_2attr_id('result','user_id','exam_sub_id',$user->id,$id)->row();
            $data['mcqs'] = $this->admin_model->get_data_by_attr_id('mcq','exam_sub_id',$id);
			$data['user'] = $user;
			$data['time_out'] = FALSE;
            if($data['result']){
                $data['answers'] = $this->admin_model->get_data_by_2attr_id('answers','user_id','exam_sub_id',$user->id,$id)->result();
            }
            if($data['exam']->course_id != $student[0]->course_id){
                redirect(base_url());
            }
            if(date('Y-m-dH:i:s') < $data['paper']->date.$data['paper']->start_time || date('Y-m-dH:i:s') > $data['paper']->date.$data['paper']->end_time){
				$data['time_out'] = TRUE;
				$data['answers'] = $this->admin_model->get_data_by_2attr_id('answers','user_id','exam_sub_id',$user->id,$id)->result();
            }
			$data['temp_answers'] = $this->admin_model->get_data_by_2attr_id('temp_answers','user_id','exam_sub_id',$user->id,$id)->result();
			if(!empty($data['temp_answers'])) {
				$mcqIdArray = array();
				foreach ($data['temp_answers'] as $ans):
					$mcqIdArray[] = $ans->mcq_id;
				endforeach;
				$data['selected_mcq'] = max($mcqIdArray);
				
			}else {
				$data['selected_mcq'] = false ;
			}
            if($this->session->userdata('minutes'.$id) || $this->session->userdata('seconds'.$id)){
				$_SESSION['minutes'.$id] = $this->session->userdata('minutes'.$id);
				if ($_SESSION['seconds'.$id] == 0) {
					$_SESSION['seconds'.$id] = 55;
				} else {
					$_SESSION['seconds'.$id] = $this->session->userdata('seconds'.$id) - 5;
				}
            }else{
                if(!empty($data['temp_answers'])){
					$mcqIdArray = array();
                    foreach ($data['temp_answers'] as $ans):
						$temp_time[] = $ans->time_left;
						$mcqIdArray[] = $ans->mcq_id;
                    endforeach;
					$time = min($temp_time)-1;
					$selected_mcq = max($mcqIdArray);
					echo $selected_mcq;
					die;
                }else{
                    $time = $data['paper']->duration - 1;
                }
                $user_data = array(
                    'minutes'.$id => $time,
					'seconds'.$id => 59,
					'start_time'.$id => date('Y-m-d H:i:s')
                );
                $this->session->set_userdata($user_data);
            }
            $this->load->view('student/pages/paper',$data);
        }
        public function update_time(){
            if (!$this->ion_auth->in_group(3)){
                redirect(base_url());
            }
            echo $_SESSION['minutes'.$this->input->get('paper_id')] = $this->input->get('min');
            echo $_SESSION['seconds'.$this->input->get('paper_id')] = $this->input->get('sec');
        }
        public function temp_answer(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(3)){
                redirect(base_url());
            }
            $id = isset($_GET['option_id'])?$_GET['option_id']:'';
            $option = $this->admin_model->get_data_by_id('mcq_options',$id);
            $mcq = $this->admin_model->get_data_by_id('mcq',$option->mcq_id);
            $temp_answer = $this->admin_model->get_data_by_attr_id('temp_answers','mcq_id',$option->mcq_id);
            if(empty($temp_answer)){
                $ans_data=array(
                    'user_id' => $user->id,
                    'mcq_id' => $option->mcq_id,
                    'exam_sub_id' => $mcq->exam_sub_id,
                    'time_left' => $this->input->get('time'),
                    'option_id' => $id
                );
                $this->admin_model->insert('temp_answers',$ans_data);
            }else{
                $ans_data=array(
                    'option_id' => $id,
                    'time_left' => $this->input->get('time'),
                );
                $this->admin_model->update_by_attr_id('temp_answers','mcq_id',$option->mcq_id,$ans_data);
            }
        }
        public function answers($id){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(3)){
                redirect(base_url());
            }
            $marks = 0;
            $each_marks = $this->admin_model->get_data_by_id('exam_sub',$id)->each_mark;
            $this->session->unset_userdata('minutes'.$id);
            $this->session->unset_userdata('seconds'.$id);
            $temp_answers = $this->admin_model->get_data_by_2attr_id('temp_answers','user_id','exam_sub_id',$user->id,$id)->result();
            foreach($temp_answers as $opt):
                $option = $this->admin_model->get_data_by_id('mcq_options',$opt->option_id);
                if($option->status){
                    $marks = $marks + $each_marks;
                }
                $answer_data = array(
                    'mcq_options_id' => $opt->option_id,
                    'exam_sub_id' => $opt->exam_sub_id,
                    'user_id' => $user->id
                );
                $this->admin_model->insert('answers',$answer_data);
                $this->admin_model->del_data('temp_answers',$opt->id);
            endforeach;
            $result_data = array(
                'user_id' => $user->id,
                'exam_sub_id' => $id,
				'marks' => $marks,
				'start_time' => $_SESSION['start_time'.$id]
			);
			$this->session->unset_userdata('start_time'.$id);
            $this->admin_model->insert('result',$result_data);
            redirect('student');
        }
        public function result(){
            $user = $this->ion_auth->user()->row();
            if (!$this->ion_auth->in_group(3)){
                redirect(base_url());
            }
            $student = $this->admin_model->get_data_by_attr_id('student','user_id',$user->id);
            if($student){
                $exams = $this->admin_model->get_data_by_attr_id('exam','course_id',$student[0]->course_id);
            } else {
                $exams = array();
            }
            $data['exams'] = $exams;
            $data['user'] = $user;
            $data['results'] =  $this->admin_model->get_data_by_attr_id('result','user_id',$user->id);
            $this->load->view('templates/header');
            $this->load->view('student/templates/sidebar');
            $this->load->view('student/pages/result',$data);
            $this->load->view('templates/footer');
		} 
		public function user_profile() {
            if (!$this->ion_auth->in_group(3)){
                redirect(base_url());
            }
			$data['user'] = $this->ion_auth->user()->row();
			$student = $this->admin_model->get_data_by_attr_id('student','user_id',$data['user']->id);
			$data['course'] = $this->admin_model->get_data_by_id('course',$student[0]->course_id);
			$this->load->view('templates/header');
            $this->load->view('student/templates/sidebar');
            $this->load->view('student/pages/user_profile',$data);
            $this->load->view('templates/footer');
		}
    }
?>

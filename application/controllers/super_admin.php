<?php
    class Super_admin extends CI_Controller{
        
            
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
            if (!$this->ion_auth->is_admin()){
                redirect(base_url());
            }
            $this->load->view('templates/header');
            $this->load->view('super_admin/templates/sidebar');
            $this->load->view('super_admin/pages/dashboard');
            $this->load->view('templates/footer');
            
        }
        public function user(){
            if (!$this->ion_auth->is_admin()){
                redirect(base_url());
            }
            $this->data['title'] = $this->lang->line('index_heading');
			
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
			$this->data['courses'] = $this->admin_model->get_data('course');
			//USAGE NOTE - you can do more complicated queries like this
			//$this->data['users'] = $this->ion_auth->where('field', 'value')->users()->result();
			
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }
            $this->load->view('templates/header');
            $this->load->view('super_admin/templates/sidebar');
            $this->load->view('auth/index', $this->data);
            $this->load->view('templates/footer');
			//$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
		
		public function get_course(){
            $id = isset($_GET['company_id'])?$_GET['company_id']:'';
			$courses = $this->admin_model->get_data_by_attr_id('course','admin_id',$id);
			echo '<option value = "">ALL</option>';
            foreach($courses as $course) {
                echo '<option value = "'.$course->id.'">'.$course->name.'</option>';
            }
		}
    }
?>

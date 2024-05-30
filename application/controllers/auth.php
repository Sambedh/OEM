<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	/**
	 * Redirect if needed, otherwise display the user list
	 */
	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		// else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		// {
		// 	// redirect them to the home page because they must be an administrator to view this
		// 	show_error('You must be an administrator to view this page.');
		// }
		else
		{
			$this->data['title'] = $this->lang->line('index_heading');
			
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
			
			//USAGE NOTE - you can do more complicated queries like this
			//$this->data['users'] = $this->ion_auth->where('field', 'value')->users()->result();
			
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
			if($this->ion_auth->is_admin()){
				redirect('super_admin', 'refresh');
			}
			elseif($this->ion_auth->in_group(5)) {
				redirect('admin', 'refresh');
			}
			elseif ($this->ion_auth->in_group(3)) {
				redirect('faculty', 'refresh');
			}
			elseif ($this->ion_auth->in_group(2)) {
				redirect('student', 'refresh');
			} 
			else {
				redirect('auth/login');
			}
			//$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	/**
	 * Log the user in
	 */
	public function login()
	{
		$this->data['title'] = $this->lang->line('login_heading');

		// validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool)$this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				//start
				// $user = $this->ion_auth->user()->row();
				// $user_groups = $this->ion_auth->get_users_groups()->result();
				// // $user_data= array(
                // //     'group' => $user_groups->id,
                // // );
				// // $this->session->set_userdata($user_data);
				$user = $this->ion_auth->user()->row();
				if($user->reference_id){
					if( !$this->admin_model->get_data_by_id('users',$user->reference_id)->active || date('Y-m-d') > $this->admin_model->get_data_by_id('users',$user->reference_id)->subscription ) {
						$this->logout();
						//redirect('auth/login', 'refresh');
					}
				}
				if ($user->subscription) {
					if(date('Y-m-d') > $user->subscription){
						$this->logout();
					}
				}
				$this->session->set_flashdata('message', $this->ion_auth->messages());
                if($this->ion_auth->is_admin()){
                    redirect('super_admin', 'refresh');
                }
                elseif($this->ion_auth->in_group(2)){
                    redirect('faculty', 'refresh');
				}
				elseif($this->ion_auth->in_group(5)){
                    redirect('admin', 'refresh');
                }
                else{
                    redirect('student', 'refresh');
                }
				//end
				//redirect('/', 'refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			];

			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
			];

			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'login', $this->data);
		}
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		$this->data['title'] = "Logout";
		//$this->session->unset_userdata('group');
		// log the user out
		$this->ion_auth->logout();
		
		// redirect them to the login page
		redirect('auth/login', 'refresh');
	}

	/**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE)
		{
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = [
				'name' => 'old',
				'id' => 'old',
				'class' => 'form-control',
				'type' => 'password',
			];
			$this->data['new_password'] = [
				'name' => 'new',
				'id' => 'new',
				'class' => 'form-control',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['new_password_confirm'] = [
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'class' => 'form-control',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['user_id'] = [
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			];

			// render
			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	/**
	 * Forgot password
	 */
	public function forgot_password()
	{
		$this->data['title'] = $this->lang->line('forgot_password_heading');
		
		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() === FALSE)
		{
			$this->data['type'] = $this->config->item('identity', 'ion_auth');
			// setup the input
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
			];

			if ($this->config->item('identity', 'ion_auth') != 'email')
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'forgot_password', $this->data);
		}
		else
		{
			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity))
			{

				if ($this->config->item('identity', 'ion_auth') != 'email')
				{
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				}
				else
				{
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$this->data['title'] = $this->lang->line('reset_password_heading');
		
		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() === FALSE)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = [
					'name' => 'new',
					'id' => 'new',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				];
				$this->data['new_password_confirm'] = [
					'name' => 'new_confirm',
					'id' => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				];
				$this->data['user_id'] = [
					'name' => 'user_id',
					'id' => 'user_id',
					'type' => 'hidden',
					'value' => $user->id,
				];
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'reset_password', $this->data);
			}
			else
			{
				$identity = $user->{$this->config->item('identity', 'ion_auth')};

				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($identity);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		$activation = FALSE;

		if ($code !== FALSE)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}
		else if ( $this->ion_auth->in_group(5) ){
			$user = $this->ion_auth->user($id)->row();
			if ($user->reference_id == $this->ion_auth->user()->row()->id ){
				$activation = $this->ion_auth->activate($id);
			}
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() && !$this->ion_auth->in_group(5) )
		{
			// redirect them to the home page because they must be an administrator to view this
			show_error('You must be an administrator to view this page.');
		}

		$id = (int)$id;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() === FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();
			$this->data['identity'] = $this->config->item('identity', 'ion_auth');

			$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() || $this->ion_auth->in_group(5) )
				{
					
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}

	/**
	 * Create a new user
	 */
	public function create_user()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');
		$this->data['roles'] = $this->admin_model->get_data('groups');
		$admin_user = $this->ion_auth->user()->row();
		if(!$this->ion_auth->is_admin()){
			$this->data['courses'] = $this->admin_model->get_data_by_attr_id('course','admin_id',$admin_user->id);
		}
		$this->data['users'] = $this->admin_model->get_data('users');
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin() && !$this->ion_auth->in_group(5))
		{
			redirect('auth', 'refresh');
		}

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		if(!$this->ion_auth->is_admin() && ($this->input->post('role') == 1 || $this->input->post('role') == 5)) {
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
		if ($identity_column !== 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
		}
		else
		{
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim|required|numeric|exact_length[10]');
		$this->form_validation->set_rules('role', 'User Role is', 'trim|required');
		if($this->input->post('role')) {
			if($this->input->post('role') == 5) {
				$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
				$this->form_validation->set_rules('date', 'Subscription date' , 'trim|required');
			} elseif($this->input->post('role') == 2) {
				$this->form_validation->set_rules('course[]', 'Select Course' , 'trim');
				$this->form_validation->set_rules('subject[]', 'Subject' , 'trim');
			} elseif($this->input->post('role') == 3) {
				$this->form_validation->set_rules('course', 'Select Course', 'trim');
			}
		}
		if($this->input->post('password')) {
			$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
		} 
		if ($this->form_validation->run() === TRUE)
		{
			$toemail = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $toemail : $this->input->post('identity');
			if($this->input->post('password')) {
				$password = $this->input->post('password');
			} else {
				$password = $this->randomPassword(10,1,"lower_case,upper_case,numbers,special_symbols");
			}
			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'phone' => $this->input->post('phone'),
			];
			if($this->input->post('role') == 2 || $this->input->post('role') == 3) {
				$additional_data['reference_id'] = $_SESSION['user_id'];
			}	
			if($this->input->post('role') == 5) {
				$additional_data['company'] = $this->input->post('company') ;
				$additional_data['subscription'] =  $this->input->post('date') ;
			}
			$group = array( strval($this->input->post('role')) );
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $toemail, $additional_data,$group))
		{
			// check to see if we are creating the user
			// sending mail to the user after creating account
			$this->load->library('email'); 
			$config = array(
				'mailtype'    => 'html',
				'charset'    => 'iso-8859-1',
				'crlf'        => "\r\n",
				'newline'    => "\r\n",
				'protocol'    => 'smtp',
				'smtp_host'    => 'smtp.mailgun.org',
				'smtp_port'    => '587',
				'smtp_crypto'    => 'tls',
				'smtp_user'    => 'softtask@mg.krossideas.com',
				'smtp_pass'    => 'c0abefe9da6843473897ec7aa952d67a-3e51f8d2-150e2499',
			);
			$this->email->initialize($config);
			$this->email->from($_SESSION['email'] , $admin_user->company);
			$this->email->to($toemail ,$this->input->post('first_name').$this->input->post('last_name'));
			$this->email->subject('Account Created');
			$this->email->message('Username : '.$toemail.' , password : '.$password); 
			try {
				//$this->email->send();
				echo 'Message has been sent.';
			} catch(Exception $e) {
				echo $e->getMessage();
				die;
			}
			// inserting data in faculty and student table
			$user = $this->admin_model->get_data_by_2attr_id('users','email','phone',$toemail,$this->input->post('phone'))->row();
			if($this->ion_auth->in_group(2,$user->id) && $this->input->post('subject')) {
				foreach ( $this->input->post('subject') as $subject ):
					$faculty_data=array(
						'user_id' => $user->id,
						'subject_id' => $subject,
					);
					$this->admin_model->insert('faculty',$faculty_data);
				endforeach;
			} elseif($this->ion_auth->in_group(3,$user->id) && $this->input->post('course') ) {
					$student_data=array(
						'user_id' => $user->id,
						'course_id' => $this->input->post('course'),
					);
					$this->admin_model->insert('student',$student_data);
			}
			// redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			if($this->ion_auth->is_admin()) {
				redirect('super_admin/user');
			} else {
				redirect('admin/user');
			}
		}
		else
		{
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['first_name'] = [
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'class' => 'form-control has-feedback-left',
				'placeholder' => 'First Name',
				'value' => $this->form_validation->set_value('first_name'),
			];
			$this->data['last_name'] = [
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'class' => 'form-control',
				'placeholder' => 'Last Name',
				'value' => $this->form_validation->set_value('last_name'),
			];
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			];
			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'class' => 'form-control has-feedback-left',
				'placeholder' => 'Email',
				'value' => $this->form_validation->set_value('email'),
			];
			
			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'tel',
				'class' => 'form-control',
				'data-inputmask' => "'mask' : '(999) 999-9999'",
				'placeholder' => 'Phone Number',
				'value' => $this->form_validation->set_value('phone'),
			];
			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'class' => 'form-control',
				'placeholder' => 'Password',
				'value' => $this->form_validation->set_value('password'),
			];
			$this->data['password_confirm'] = [
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'class' => 'form-control',
				'placeholder' => 'Confirm Password',
				'value' => $this->form_validation->set_value('password_confirm'),
			];
			if($this->ion_auth->is_admin()) {
				$this->data['role_options'] = array(
					'1' => 'Super Admin',
					'5' => 'Admin',
				);
				$this->data['date'] = [
					'name' => 'date',
					'id' => 'subscription',
					'type' => 'text',
					'min' => date('Y-m-d'),
					'class' => 'date-picker form-control',
					'onfocus' => "this.type='date'" ,
					'onmouseover' => "this.type='date'" ,
					'onclick' => "this.type='date'" ,
					'onblur' => "this.type='text'" ,
					'onmouseout' => "timeFunctionLong(this)",
					'placeholder' => 'Subscribe Till',
					'value' => $this->form_validation->set_value('date'),
				];
				$this->data['company'] = [
					'name' => 'company',
					'id' => 'company',
					'type' => 'text',
					'class' => 'form-control has-feedback-left',
					'placeholder' => 'Institution Name',
					'value' => $this->form_validation->set_value('company'),
				];
			} else {
				$this->data['role_options'] = array(
					'3' => 'Student' , 
					'2' => 'Faculty',
				);
				$course_options = array();
				foreach($this->data['courses'] as $course):
					$course_options[$course->id] = $course->name;
				endforeach;
				$this->data['course_options'] = $course_options;
				$this->data['selected_course'] = $this->input->post('course') ;
				$this->data['course'] = [
					'class' => "form-control has-feedback-left" ,
					'id' => "course" ,
					'onchange' => 'get_sub()',
				];
				$subject_options = array();
				if($this->input->post('course') && $this->input->post('role') == 2) {
					foreach($this->input->post('course') as $item) {
						$subjects = $this->admin_model->get_data_by_attr_id('subject', 'course_id' , $item);
						foreach ($subjects as $subject) {
							$subject_options[$subject->id] = $subject->name;
						}
					}
				} 
				else {
					foreach($this->data['courses'] as $course):
						$subjects = $this->admin_model->get_data_by_attr_id('subject', 'course_id' , $course->id);
						foreach ($subjects as $subject) {
							$subject_options[$subject->id] = $subject->name;
						}
						break;
					endforeach;
				}
				$this->data['subject_options'] = $subject_options;
				$this->data['selected_subject'] = $this->input->post('subject');
				$this->data['subject'] = [
					'class' => 'form-control has-feedback-left',
					'id' => 'subject',
				];
			}
			$this->data['selected_role'] = $this->form_validation->set_value('role');
			$this->data['role'] = [
				'class' => "form-control has-feedback-left" ,
				'id' => "role" ,
				'onchange' => "check_role()"
			];
			$this->load->view('templates/header');
			if($this->ion_auth->is_admin()) {
				$this->load->view('super_admin/templates/sidebar');
			} else {
				$this->load->view('admin/templates/sidebar');
			}
            $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'create_user', $this->data);
            $this->load->view('templates/footer');
		}
	}
	
     public function randomPassword($length,$count, $characters) {
 
	// $length - the length of the generated password
	// $count - number of passwords to be generated
	// $characters - types of characters to be used in the password
	 
	// define variables used within the function    
		$symbols = array();
		$passwords = array();
		$used_symbols = '';
		$pass = '';
	 
	// an array of different character types    
		$symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
		$symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$symbols["numbers"] = '1234567890';
		// $symbols["special_symbols"] = '!?~@#-_+<>[]{}';
		$symbols["special_symbols"] = '!@#_';
		$characters = explode(",",$characters); // get characters types to be used for the passsword
		foreach ($characters as $key=>$value) {
			$used_symbols .= $symbols[$value]; // build a string with all characters
		}
		$symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1
		 
		//for ($p = 0; $p < $count; $p++) {
			$pass = '';
			for ($i = 0; $i < $length; $i++) {
				$n = rand(0, $symbols_length); // get a random character from the string with all characters
				$pass .= $used_symbols[$n]; // add the character to the password string
			}
			$passwords = $pass;
		//}
		 
		return $passwords; // return the generated password
	}
	/**
	* Redirect a user checking if is admin
	*/
	public function redirectUser(){
		if ($this->ion_auth->is_admin()){
			redirect('auth', 'refresh');
		}
		redirect('/', 'refresh');
	}

	/**
	 * Edit a user
	 *
	 * @param int|string $id
	 */
	public function edit_user($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');
		$this->data['roles'] = $this->admin_model->get_data('groups');
		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group(5) && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		if($this->ion_auth->in_group(3,$user->id) ) {
			$this->data['courses'] = $this->admin_model->get_data_by_attr_id('course','admin_id',$_SESSION['user_id']);
			$this->data['student'] = $this->admin_model->get_data_by_attr_id('student','user_id',$id);
		} 
		if ($this->ion_auth->in_group(2,$user->id)){
			$this->data['courses'] = $this->admin_model->get_data_by_attr_id('course','admin_id',$_SESSION['user_id']);
			$this->data['teachers'] = $this->admin_model->get_data_by_attr_id('faculty','user_id',$user->id);
			$selected_courses = array();
			$subjects = array();
			foreach($this->data['teachers'] as $teacher):
				$selected_subject = $this->admin_model->get_data_by_id('subject',$teacher->subject_id);
				if(array_search($selected_subject->course_id,$selected_courses) === FALSE){
					$selected_courses[] = $selected_subject->course_id;
				}
			endforeach;	
			foreach($selected_courses as $course):
				$subjects[]=$this->admin_model->get_data_by_attr_id('subject','course_id',$course) ;
			endforeach;
			$this->data['selected_courses'] = $selected_courses;
			$this->data['subjects'] = $subjects;
		}
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result_array();
			
		//USAGE NOTE - you can do more complicated queries like this
		//$groups = $this->ion_auth->where(['field' => 'value'])->groups()->result_array();
	

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim|required|numeric|exact_length[10]');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim');
		$this->form_validation->set_rules('date', 'Please Mention Subscription date' , 'trim');
		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			//if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			if ($id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = [
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'phone' => $this->input->post('phone'),
				];

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}

				if ($this->ion_auth->in_group(5,$user->id)) { 
					$data['company'] = $this->input->post('company') ;
					$data['subscription'] = $this->input->post('date') ;
				}

				// Only allow updating groups if user is admin
				// if ($this->ion_auth->is_admin())
				// {
				// 	// Update the groups user belongs to
				// 	$this->ion_auth->remove_from_group('', $id);
					
				// 	$groupData = $this->input->post('groups');
				// 	if (isset($groupData) && !empty($groupData))
				// 	{
				// 		foreach ($groupData as $grp)
				// 		{
				// 			$this->ion_auth->add_to_group($grp, $id);
				// 		}

				// 	}

				// }

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data))
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					if ($this->ion_auth->in_group(2,$user->id)) {
						$this->admin_model->del_data_by_attr_id('faculty','user_id',$user->id);
						foreach ( $this->input->post('subject') as $subject ):
							$faculty_data = array(
								'user_id' => $user->id,
								'subject_id' => $subject,
							);
							$this->admin_model->insert('faculty',$faculty_data);
						endforeach;
					} elseif ($this->ion_auth->in_group(3,$user->id)) {
						if(empty($this->admin_model->get_data_by_2attr_id('student','user_id','course_id',$user->id,$this->input->post('course'))->result())){
							$student_data=array(
								'user_id' => $user->id,
								'course_id' => $this->input->post('course'),
							);
							$this->admin_model->insert('student',$student_data);
						} else {
							$student_data=array(
								'course_id' => $this->input->post('course'),
							);
							$this->admin_model->update_by_attr_id('student','user_id',$user->id,$student_data);
						}
					} else {
					}
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					$this->redirectUser();

				}
				else
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					$this->redirectUser();

				}

			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = [
			'name'  => 'first_name',
			'id'    => 'first_name',
			'class' => 'form-control has-feedback-left',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		];
		$this->data['last_name'] = [
			'name'  => 'last_name',
			'id'    => 'last_name',
			'class' => 'form-control',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		];
		$this->data['phone'] = [
			'name'  => 'phone',
			'id'    => 'phone',
			'class' => 'form-control',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		];
		$this->data['password'] = [
			'name' => 'password',
			'id'   => 'password',
			'class' => 'form-control',
			'type' => 'password',
			'placeholder' => 'Password(if required)',
			'value' => $this->form_validation->set_value('password'),
		];
		$this->data['password_confirm'] = [
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'class' => 'form-control',
			'type' => 'password',
			'placeholder' => 'Confirm Password(if required)',
			'value' => $this->form_validation->set_value('password_confirm'),
		];
		if($this->ion_auth->is_admin()) {
			$this->data['date'] = [
				'name' => 'date',
				'id' => 'subscription',
				'type' => 'text',
				'min' => date('Y-m-d'),
				'class' => 'date-picker form-control',
				'onfocus' => "this.type='date'" ,
				'onmouseover' => "this.type='date'" ,
				'onclick' => "this.type='date'" ,
				'onblur' => "this.type='text'" ,
				'onmouseout' => "timeFunctionLong(this)",
				'placeholder' => 'Subscribe Till',
				'value' => $user->subscription,
			];
			$this->data['company'] = [
				'name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'class' => 'form-control has-feedback-left',
				'placeholder' => 'Institution Name',
				'value' => $user->company,
			];
		} else {
			
		}
		$this->load->view('templates/header');
		if($this->ion_auth->is_admin()) {
			$this->load->view('super_admin/templates/sidebar');
		} else {
			$this->load->view('admin/templates/sidebar');
		}
		$this->_render_page('auth/edit_user', $this->data);
		//$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'create_user', $this->data);
		$this->load->view('templates/footer');	
	}

	/**
	 * Create a new group
	 */
	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'trim|required|alpha_dash');

		if ($this->form_validation->run() === TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if ($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
			else
            		{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
            		}			
		}
			
		// display the create group form
		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$this->data['group_name'] = [
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name'),
		];
		$this->data['description'] = [
			'name'  => 'description',
			'id'    => 'description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('description'),
		];

		$this->_render_page('auth/create_group', $this->data);
		
	}

	/**
	 * Edit a group
	 *
	 * @param int|string $id
	 */
	public function edit_group($id)
	{
		// bail if no group id given
		if (!$id || empty($id))
		{
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'trim|required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], array(
					'description' => $_POST['group_description']
				));

				if ($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
					redirect("auth", 'refresh');
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}				
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$this->data['group_name'] = [
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
		];
		if ($this->config->item('admin_group', 'ion_auth') === $group->name) {
			$this->data['group_name']['readonly'] = 'readonly';
		}
		
		$this->data['group_description'] = [
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		];

		$this->_render_page('auth' . DIRECTORY_SEPARATOR . 'edit_group', $this->data);
	}

	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return [$key => $value];
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce(){
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
			return FALSE;
	}

	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{

		$viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}

}

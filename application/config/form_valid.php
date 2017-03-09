<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['error_prefix'] = '<div class="error_msg">';
$config['error_suffix'] = '</div>';
$config = array(
        'signup' => array(
                array(
                        'field' => 'mail_id',
                        'label' => 'User name',
                        'rules' => 'required|valid_email|is_unique[users.mail_id]',
						'errors' => 'This Mail id was already registered in our database'
                ),
                array(
                        'field' => 'first_name',
                        'label' => 'first_name',
                        'rules' => 'alpha|required|trim|min_length[2]|max_length[30]'
                ),
                array(
                        'field' => 'last_name',
                        'label' => 'last_name',
                        'rules' => 'alpha|required|trim|max_length[30]'
                ),
                array(
                        'field' => 'gender',
                        'label' => 'gender',
                        'rules' => 'required|in_list[Male,Female,Transgender]'
                ),
				array(
                        'field' => 'dob',
                        'label' => 'date of birth',
                        'rules' => 'required|callback_validate_date'
                ),
                array(
                        'field' => 'phone',
                        'label' => 'phone number',
                        'rules' => 'required|exact_length[10]'
                )
        ),
        'login' => array(
                array(
                        'field' => 'userid',
                        'label' => 'Mail ID',
                        'rules' => 'required|valid_email'
                ),
                array(
                        'field' => 'password',
                        'label' => 'password',
                        'rules' => 'required|trim|min_length[4]|max_length[30]'
                )
        ),
		'new_test' => array(
                array(
                        'field' => 'title',
                        'label' => 'Test Title',
                        'rules' => 'required'						
                ),
                array(
                        'field' => 'category',
                        'label' => 'Category',
                        'rules' => 'required'
                ),
                array(
                        'field' => 'start_date',
                        'label' => 'Start Date',
                        'rules' => 'required|callback_validate_date'
                ),
				array(
                        'field' => 'end_date',
                        'label' => 'End Date',
                        'rules' => 'required|callback_validate_date'
                ),
                array(
                        'field' => 'duration',
                        'label' => 'Test Duration',
                        'rules' => 'required|numeric'
                ),
				array(
                        'field' => 'no_of_questions',
                        'label' => 'no_of_questions',
                        'rules' => 'required|numeric'
                )
        ),
		'new_questions' => array(
                array(
                        'field' => 'question[]',
                        'label' => 'Question',
                        'rules' => 'required'						
                ),
                array(
                        'field' => 'option_a[]',
                        'label' => 'Option A',
                        'rules' => 'required'
                ),
                array(
                        'field' => 'option_b[]',
                        'label' => 'Option B',
                        'rules' => 'required'
                ),
				array(
                        'field' => 'option_c[]',
                        'label' => 'Option C',
                        'rules' => 'required'
                ),
                array(
                        'field' => 'option_d[]',
                        'label' => 'Option D',
                        'rules' => 'required'
                ),
				array(
                        'field' => 'answer[]',
                        'label' => 'Answer',
                        'rules' => 'required'
                )
        )
);

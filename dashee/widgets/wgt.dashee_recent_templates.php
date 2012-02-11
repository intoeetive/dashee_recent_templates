<?php

/*
=====================================================
 Recent templates widget
-----------------------------------------------------
 http://www.intoeetive.com/
-----------------------------------------------------
 Copyright (c) 2012 Yuri Salimovskiy
=====================================================
 This software is intended for usage with
 ExpressionEngine CMS, version 2.0 or higher
=====================================================
 File: wgt.dashee_recent_templates.php
-----------------------------------------------------
 Purpose: Displays 10 templates you recently edited
=====================================================
*/


class Wgt_dashee_recent_templates
{
	public $title;		// title displayed at top of widget
	public $settings;	// array of widget settings (required for dynamic widgets only)
	public $wclass;		// class name for additional styling capabilities
	
	private $_EE;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{	
		$this->_EE 		=& get_instance();
		$this->_EE->lang->loadfile('modules');
		$this->_EE->lang->loadfile('dashee_recent_templates');  
		
		$this->title  	= lang('wgt_dashee_recent_templates_name');
		$this->wclass 	= 'contentMenu';	
		
		// define default widget settings
		$this->settings = array();
		
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Permissions Function
	 * Defines permissions needed for user to be able to add widget.
	 *
	 * @return 	bool
	 */
	public function permissions()
	{
		// add any additional custom permission checking here and 
		// return FALSE if user doesn't have permission
	
		return TRUE;
	}

	/**
	 * Index Function
	 *
	 * @return 	string
	 */
	public function index()
	{

		$this->_EE->db->select('template_id, group_name, template_name')
			->from('exp_templates')
			->join('exp_template_groups', 'exp_templates.group_id=exp_template_groups.group_id', 'left')
			->where('exp_templates.site_id', $this->_EE->config->item('site_id'))
			->where('last_author_id', $this->_EE->session->userdata('member_id'))
			->order_by('edit_date', 'desc')
			->limit(10);
		
		$query = $this->_EE->db->get();

		$display = '';
		foreach ($query->result_array() as $row)
		{
			$display .= '
				<tr class="'.alternator('odd','even').'">
					<td><a href="'.BASE.AMP.'C=design'.AMP.'M=edit_template'.AMP.'id='.$row['template_id'].'">'.$row['group_name'].'/<strong>'.$row['template_name'].'</strong></a></td>
				</tr>';
		}
		
		return '
			<table>
				<tbody>'.$display.'</tbody>
			</table>
		';
	}
	

}
/* End of file wgt.biolerplate.php */
/* Location: /system/expressionengine/third_party/dashee/widgets/wgt.biolerplate.php */
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends Admin_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("subject_m");
		$this->load->model("subjectattendance_m");
		$this->load->model("student_info_m");
		$this->load->model("parents_info_m");
		$this->load->model('section_m');
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model("student_m");
		$this->load->model("invoice_m");
		$this->load->model("sattendance_m");
		$this->load->model("payment_m");
		$this->load->model("mark_m");
		$this->load->model("transport_m");
		$this->load->model("hostel_m");
		$this->load->model("hmember_m");
		$this->load->model("tmember_m");
		$this->load->model("grade_m");
		$this->load->model("exam_m");
		$this->load->model("routine_m");
		$this->load->model("schoolyear_m");
		$this->load->model("studentrelation_m");
		$this->load->model("mailandsmstemplatetag_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('report', $language);
	}

	public function student()
	{
		$this->data["subview"] = "report/Student";
		$this->load->view('_layout_main', $this->data);
	}

	public function classreport()
	{
		$this->data['classes'] = $this->classes_m->get_classes();
		$this->data["subview"] = "report/class/ClassReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getClassReport()
	{
		$classID 		= $this->input->post('classID');
		$sectionID 		= $this->input->post('sectionID');
		$schoolyearID 	= $this->data['siteinfos']->school_year;

		$classes 	= $this->classes_m->get_classes($classID);

		$subjects = $this->subject_m->get_order_by_subject(array( 'classesID' => $classID));
		$teachers = pluck($this->teacher_m->get_teacher(), 'obj', 'teacherID');

		if($sectionID == 0) {
			$students = pluck($this->student_m->get_order_by_student(array( 'classesID' => $classID, 'schoolyearID' => $schoolyearID)), 'obj', 'studentID');
			$sections = pluck($this->section_m->get_order_by_section(array( 'classesID' => $classID)), 'obj', 'sectionID');

		} else {
			$students = pluck($this->student_m->get_order_by_student(array( 'classesID' => $classID, 'sectionID' => $sectionID, 'schoolyearID' => $schoolyearID)), 'obj', 'studentID');
			$sections 	= pluck($this->section_m->get_order_by_section(array( 'classesID' => $classID, 'sectionID' => $sectionID)), 'obj', 'sectionID');

		}

		$invoices = $this->invoice_m->get_order_by_invoice(array('classesID' => $classID, 'schoolyearID' => $schoolyearID));
		$payments = pluck($this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID)), 'obj', 'invoiceID');
		$studentInvoices = array();
		$feetypes = array();
		$collectionAmount = 0;
		$totalInvoiceAmount = 0;
		foreach ($invoices as $invoice ) {
			if(!isset($students[$invoice->studentID])) continue;

			if(!isset($feetypes[$invoice->feetype])) {
				$feetypes[$invoice->feetype] = 0;
			}

			if(!isset($studentInvoices[$invoice->studentID]['amount'])) {
				$studentInvoices[$invoice->studentID]['amount'] = 0;
			}

			if(isset($payments[$invoice->invoiceID])) {
				if(!isset($studentInvoices[$payments[$invoice->invoiceID]->studentID]['payment'])) {
					$studentInvoices[$payments[$invoice->invoiceID]->studentID]['payment'] = 0;
				}
				$collectionAmount += $payments[$invoice->invoiceID]->paymentamount;
				$feetypes[$invoice->feetype] += $payments[$invoice->invoiceID]->paymentamount;
				$studentInvoices[$payments[$invoice->invoiceID]->studentID]['payment'] += $payments[$invoice->invoiceID]->paymentamount;
			}

			$totalInvoiceAmount += $invoice->amount;
			$studentInvoices[$invoice->studentID]['amount'] += $invoice->amount;
		}

		$dueAmount = $totalInvoiceAmount - $collectionAmount;

		$this->data['class'] = $classes;
		$this->data['subjects'] = $subjects;
		$this->data['teachers'] = $teachers;
		$this->data['students'] = $students;
		$this->data['sections'] = $sections;
		if(isset($sections[$sectionID])) {
			$this->data['sectionName'] = $this->lang->line("report_section")." ".$sections[$sectionID]->section;
		} elseif ($sectionID == 0) {
			$this->data['sectionName'] = $this->lang->line("report_select_all_section");
		}
		$this->data['collectionAmount'] = $collectionAmount;
		$this->data['dueAmount'] = $dueAmount;
		$this->data['studentInvoices'] = $studentInvoices;
		$this->data['feetypes'] = $feetypes;


		echo $this->load->view('report/class/ClassReport', $this->data, true);
	}

	public function attendancereport()
	{
		$this->data['classes'] = $this->classes_m->get_classes();
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css',
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
			)
		);
		$attendacewise 	= $this->data['siteinfos']->attendance;
		if($attendacewise == 'subject') {
			$this->data['subjectWise'] = 1;
		} else {
			$this->data['subjectWise'] = 0;
		}

		$this->data["subview"] = "report/attendance/AttendanceReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getAttendacneReport()
	{
		$classID 		= $this->input->post('classID');
		$sectionID 		= $this->input->post('sectionID');
		$type 			= $this->input->post('type');
		$date 			= explode('-', $this->input->post('date'));
		$schoolyearID 	= $this->data['siteinfos']->school_year;
		$classes 		= $this->classes_m->get_classes($classID);

		$day = 'a'.(int)$date[0];
		$monthyear = $date[1].'-'.$date[2];

		if($sectionID == 0) {
			$students = pluck($this->student_m->get_order_by_student(array( 'classesID' => $classID, 'schoolyearID' => $schoolyearID)), 'obj', 'studentID');
			$sections = pluck($this->section_m->get_order_by_section(array( 'classesID' => $classID)), 'obj', 'sectionID');
			if($this->input->post('subjectID')) {
				$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classID, 'subjectID' => $this->input->post('subjectID'), 'monthyear' => $monthyear));
			} else {
				$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classID, 'monthyear' => $monthyear));
			}

		} else {
			$students = pluck($this->student_m->get_order_by_student(array( 'classesID' => $classID, 'sectionID' => $sectionID, 'schoolyearID' => $schoolyearID)), 'obj', 'studentID');
			$sections 	= pluck($this->section_m->get_order_by_section(array( 'classesID' => $classID, 'sectionID' => $sectionID)), 'obj', 'sectionID');
			if($this->input->post('subjectID')) {
				$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classID, 'sectionID' => $sectionID, 'subjectID' => $this->input->post('subjectID'), 'monthyear' => $monthyear));
			} else {
				$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classID, 'sectionID' => $sectionID, 'monthyear' => $monthyear));
			}

		}

		$attendances = pluck($attendances, 'obj', 'studentID');

		$this->data['attendances'] = $attendances;
		$this->data['students'] = $students;
		$this->data['class'] = $classes;
		$this->data['typeSortForm'] = $type;
		$this->data['day'] = $day;
		$this->data['date'] = $this->input->post('date');
		if(isset($sections[$sectionID])) {
			$this->data['sectionName'] = $this->lang->line("report_section")." ".$sections[$sectionID]->section;
		} elseif ($sectionID == 0) {
			$this->data['sectionName'] = $this->lang->line("report_select_all_section");
		}

		if($type == 'A') {
			$this->data['type'] = $this->lang->line("report_absent");
		} else {
			$this->data['type'] = $this->lang->line("report_present");
		}

		echo $this->load->view('report/attendance/AttendanceReport', $this->data, true);

	}

	public function studentreport()
	{
		$this->data['classes'] = $this->classes_m->get_classes();
		$this->data['transports'] = $this->transport_m->get_transport();
		$this->data['hostels'] = $this->hostel_m->get_hostel();
		// $this->data['headerassets'] = array(
		// 	'css' => array(
		// 		'assets/datepicker/datepicker.css',
		// 	),
		// 	'js' => array(
		// 		'assets/datepicker/datepicker.js',
		// 	)
		// );
		// $attendacewise 	= $this->data['siteinfos']->attendance;
		// if($attendacewise == 'subject') {
		// 	$this->data['subjectWise'] = 1;
		// } else {
		// 	$this->data['subjectWise'] = 0;
		// }

		$this->data["subview"] = "report/student/StudentReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getStudentReport()
	{
		$schoolorclass = $this->input->post('schoolorclass');
		$reportfor = $this->input->post('reportfor');
		$schoolyearID 	= $this->data['siteinfos']->school_year;
		$students = array();
		$where = array();
		$reportTitle = '';

		if($reportfor == 'blood') {
			$reportfor .= ' Group';
			$reportTitle = $this->input->post('value');
			$where = $this->getCondition('bloodgroup');
		} elseif ($reportfor == 'country') {
			$reportTitle = $this->data['allcountry'][$this->input->post('value')];
			$where = $this->getCondition('country');
		} elseif($reportfor == 'gender') {
			$reportTitle = $this->input->post('value');
			$where = $this->getCondition('sex');
		} elseif ($reportfor == 'transport') {
			$route = $this->input->post('value');
			$reportTitle = $this->transport_m->get_single_transport(array('transportID' => $route))->route;

			$transports = $this->tmember_m->get_order_by_tmember(array('transportID' => $route));

			$allStudents = pluck($this->student_m->get_order_by_student($this->getCondition()), 'obj', 'studentID');
			foreach ($transports as $transport) {
				if(isset($allStudents[$transport->studentID])) {
					$students[$transport->studentID] = $allStudents[$transport->studentID];
				}
			}
		} elseif ($reportfor == 'hostel') {
			$hostelID = $this->input->post('value');
			$reportTitle = $this->hostel_m->get_single_hostel(array('hostelID' => $hostelID))->name;

			$hostels = $this->hmember_m->get_order_by_hmember(array('hostelID' => $hostelID));

			$allStudents = pluck($this->student_m->get_order_by_student($this->getCondition()), 'obj', 'studentID');
			foreach ($hostels as $hostel) {
				if(isset($allStudents[$hostel->studentID])) {
					$students[$hostel->studentID] = $allStudents[$hostel->studentID];
				}
			}
		}

		if(count($where)) {
			$students = $this->student_m->get_order_by_student($where);
		}

		$this->data['students'] = $students;
		$this->data['schoolorclass'] = $schoolorclass;
		$this->data['reportfor'] = $reportfor;
		$this->data['reportTitle'] = $reportTitle;
		echo $this->load->view('report/student/StudentReport', $this->data, true);
	}

	public function getCondition($field=NULL)
	{
		$schoolorclass 	= $this->input->post('schoolorclass');
		$schoolyearID 	= $this->data['siteinfos']->school_year;

		if($field != NULL) {
			$data 			= $this->input->post('value');
			$where = array(
				'schoolyearID' => $schoolyearID,
				$field => $data
			);
		} else {
			$where = array(
				'schoolyearID' => $schoolyearID
			);
		}

		if($schoolorclass == 'class') {

			$classID 		= $this->input->post('classID');
			$sectionID 		= $this->input->post('sectionID');

			$this->data['class'] 	= $this->classes_m->get_classes($classID);
			$this->data['classSections'] = pluck($this->section_m->get_order_by_section(array( 'classesID' => $classID)), 'obj', 'sectionID');

			if($sectionID == 0) {
				$where['classesID'] = $classID;
			} else {
				$where['classesID'] = $classID;
				$where['sectionID'] = $sectionID;
			}
		} else {
			$this->data['classes'] 	= pluck($this->classes_m->get_classes(), 'obj' , 'classesID');
			$this->data['sections'] = pluck($this->section_m->get_section(), 'obj', 'sectionID');
		}

		return $where;
	}

	public function getSection()
	{
		$id = $this->input->post('id');
		if((int)$id) {
			$allSection = $this->section_m->get_order_by_section(array('classesID' => $id));

			echo "<option value='0'>", $this->lang->line("report_select_all_section"),"</option>";

			foreach ($allSection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}

		}
	}

	public function getSubject()
	{
		$classID = $this->input->post('classID');
		if((int)$classID) {
			$allSubject = $this->subject_m->get_order_by_subject(array('classesID' => $classID));

			echo "<option value=''>", $this->lang->line("report_select_subject"),"</option>";

			foreach ($allSubject as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}

		}
	}



	public function certificate() 
	{
		$this->data['schoolyears'] = $this->schoolyear_m->get_order_by_schoolyear(array('schooltype' => $this->data["siteinfos"]->school_type));
		
		$this->data['classes'] = $this->classes_m->get_classes();
		
		$this->data['templates'] = $this->certificate_template_m->get_certificate_template();
		$this->data["subview"] = "report/certificate/CertificateReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getStudentList() 
	{
		$schoolyearID 	= $this->input->post('schoolyearID');
		$classID 		= $this->input->post('classID');
		$sectionID 		= $this->input->post('sectionID');
		$templateID 	= $this->input->post('templateID');

		$sections = pluck($this->section_m->get_section(), 'section', 'sectionID');
		$classes = pluck($this->classes_m->get_classes(), 'classes', 'classesID');


		if($sectionID == 0) {
			$students = $this->studentrelation_m->get_studentrelation_join_student(array('srclassesID' => $classID, 'srschoolyearID' => $schoolyearID));
			$section = $this->lang->line('report_select_all_section');
		} else {
			$students = $this->studentrelation_m->get_studentrelation_join_student(array('srclassesID' => $classID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
			$section = $sections[$sectionID];
		}

		$this->data['students']		= $students;
		$this->data['classes'] 		= $classes;
		$this->data['sections'] 	= $sections;
		$this->data['class']		= $classes[$classID];
		$this->data['section']		= $section;
		$this->data['templateID'] 	= $templateID;
		echo $this->load->view('report/certificate/CertificateReport', $this->data, true);
	}

	public function generate_certificate() 
	{

		$this->data['headerassets'] = array(
            'css' => array(
                // 'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/CircleType/dist/circletype.min.js'
            )
        );

		$tagArray = array();
		$this->data['themeArray'] = array(
            // '1' => 'default',
            '1' => 'theme1',
            '2' => 'theme2'
        );

		$userID 		= htmlentities(escapeString($this->uri->segment(3)));
		$usertypeID 	= htmlentities(escapeString($this->uri->segment(4)));
		$templateID 	= htmlentities(escapeString($this->uri->segment(5)));
		$schoolyearID 	= htmlentities(escapeString($this->uri->segment(6)));
		$classID 		= htmlentities(escapeString($this->uri->segment(7)));

		if((int)$userID && (int)$usertypeID && (int)$templateID && (int)$schoolyearID && (int)$classID) {
			$student = $this->studentrelation_m->get_studentrelation_join_student(array('srstudentID' => $userID), TRUE);

			$usertype = $this->usertype_m->get_single_usertype(array('usertypeID' => $usertypeID));

			$template = $this->certificate_template_m->get_single_certificate_template(array('certificate_templateID' => $templateID));

			$schoolyear = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $schoolyearID));

			$class = $this->classes_m->get_single_classes(array('classesID' => $classID));


			if(count($student) && count($usertype) && count($template) && count($schoolyear) && count($class)) {
				$this->data['certificate_template'] = $template;
				$tagClasses = $this->classes_m->get_single_classes(array('classesID' => $student->srclassesID));
				
				$tagSection = $this->section_m->get_single_section(array('sectionID' => $student->srsectionID));

				$country = $this->getAllCountry();


				$tagArray['[name]'] 	= $student->name;
				$tagArray['[roll]']	 	= $student->srroll;
				$tagArray['[dob]'] 		= isset($student->dob) ? date("d M Y", strtotime($student->dob)) : '';
				$tagArray['[gender]'] 	= $student->sex;
				$tagArray['[religion]'] = $student->religion;
				$tagArray['[email]'] 	= $student->email;
				$tagArray['[phone]'] 	= $student->phone;
				$tagArray['[username]'] = $student->username;


				$tagArray['[class/department]'] = count($tagClasses) ? $tagClasses->classes : '';
				$tagArray['[section]'] = count($tagSection) ? $tagSection->section : '';
				$tagArray['[country]'] = isset($country[$student->country]) ? $country[$student->country] : '';
				$tagArray['[register_no]'] = $student->srregisterNO;
				$tagArray['[state]'] = $student->state;
				

				$this->data['template'] = $this->tagConvertForTemplate($template->template, 3, $tagArray);

				$this->data['top_heading_title'] = $this->tagConvertForTemplate($template->top_heading_title, 3, $tagArray, FALSE);


				$this->data['top_heading_left'] = $this->tagConvertForTemplate($template->top_heading_left, 3, $tagArray, FALSE);

				$this->data['top_heading_middle'] = $this->tagConvertForTemplate($template->top_heading_middle, 3, $tagArray, FALSE);

				$this->data['top_heading_right'] = $this->tagConvertForTemplate($template->top_heading_right, 3, $tagArray, FALSE);


				$this->data['main_middle_text'] = $this->tagConvertForTemplate($template->main_middle_text, 3, $tagArray, FALSE);

				$this->data['footer_left_text'] = $this->tagConvertForTemplate($template->footer_left_text, 3, $tagArray, FALSE);

				$this->data['footer_middle_text'] = $this->tagConvertForTemplate($template->footer_middle_text, 3, $tagArray, FALSE);

				$this->data['footer_right_text'] = $this->tagConvertForTemplate($template->footer_right_text, 3, $tagArray, FALSE);

				$this->data['theme'] = $this->data['themeArray'][$template->theme];
				$this->load->view('report/certificate/CertificateReportLayout', $this->data);		
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}


	function tagConvertForTemplate($message, $usertypeID=1, $convertArray, $design = TRUE) {
        if($message) {
        	if($usertypeID == 3) {
	            $userTags = pluck($this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 3)), 'tagname');

	            if(count($userTags)) {
	                unset($userTags[10]);
	                foreach ($userTags as $key => $userTag) {
	                    if(array_key_exists($userTag, $convertArray)) {
	                        $length = strlen($convertArray[$userTag]);
	                        $width = (20*$length);
	                        if($design) {
	                        	$message = str_replace($userTag, '<span style="width:'.$width.'px;" class="dots widthcss" data-hover="'.$convertArray[$userTag].'"></span>' , $message);
	                        } else {
	                        	$message = str_replace($userTag, $convertArray[$userTag], $message);
	                        }

	                    }
	                }
	            }
        	}
        }
        return $message;
    }

}

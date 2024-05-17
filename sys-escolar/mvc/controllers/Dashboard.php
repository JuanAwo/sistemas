<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {


	function __construct() {
		parent::__construct();
		$this->load->model('systemadmin_m');
		$this->load->model("dashboard_m");
		$this->load->model("automation_shudulu_m");
		$this->load->model("automation_rec_m");
		$this->load->model("setting_m");
		$this->load->model("notice_m");
		$this->load->model("user_m");
		$this->load->model("student_m");
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model("parents_m");
		$this->load->model("sattendance_m");
		$this->load->model("subjectattendance_m");
		$this->load->model("subject_m");
		$this->load->model("feetypes_m");
		$this->load->model("invoice_m");
		$this->load->model("expense_m");
		$this->load->model("payment_m");
		$this->load->model("lmember_m");
		$this->load->model("book_m");
		$this->load->model("issue_m");
		$this->load->model("student_info_m");
		$this->load->model('hmember_m');
		$this->load->model('tmember_m');
		$this->load->model('event_m');
		$this->load->model('holiday_m');
	
		$language = $this->session->userdata('lang');
		$this->lang->load('dashboard', $language);

		
		if($this->data['siteinfos']->auto_invoice_generate == 1) {
			$cnt = 0;
			$date = date('Y-m-d');
			$day = date('d');
			$month = date('m');
			$year = date('Y');
			$setting = $this->setting_m->get_setting(1);

			if($day >= $setting->automation) {
				$automation_shudulus = $this->automation_shudulu_m->get_automation_shudulu();

				if(count($automation_shudulus)) {
					foreach ($automation_shudulus as $automation_shudulu) {
						if($automation_shudulu->month == $month && $automation_shudulu->year == $year) {
							$cnt = 1;
						}
					}


					if($cnt === 0) {
						$alltotalamount = 0;
						$alltotalamounttransport = 0;
						$alltotalamounthostel = 0;

						$automationStudents = $this->student_m->get_order_by_student(array('schoolyearID' => $this->data['siteinfos']->school_year));
						$automationLMember = pluck($this->lmember_m->get_lmember(), 'lbalance', 'studentID');
						$automationTMember = pluck($this->tmember_m->get_tmember(), 'tbalance', 'studentID');
						$automationHMember = pluck($this->hmember_m->get_hmember(), 'hbalance', 'studentID');
						$allRecord = $this->getAllRec($this->automation_rec_m->get_automation_rec());
						$superAdmin = $this->systemadmin_m->get_systemadmin(1);

						if(count($automationStudents)) {
							foreach ($automationStudents as $aTstudentkey => $aTstudent) {
								if(count($automationLMember)) {
									if(isset($automationLMember[$aTstudent->studentID])) {
										if($automationLMember[$aTstudent->studentID] > 0) {
											if(!isset($allRecord[5427279][$aTstudent->studentID][$month][$year])) {

												$array = array(
													'schoolyearID' => $this->data['siteinfos']->school_year,
													'classesID' => $aTstudent->classesID,
													'studentID' => $aTstudent->studentID,
													'feetype' => $this->lang->line('dashboard_libraryfee'),
													'amount' => (int)$automationLMember[$aTstudent->studentID],
													'discount' => 0,
													'paidstatus' => 0,
													'userID' => 1,
													'usertypeID' => 1,
													'uname' => $superAdmin->name,
													'date' => date("Y-m-d"),
													'create_date' => date('Y-m-d'),
													'day' => date('d'),
													'month' => date('m'),
													'year' => date('Y'),
													'deleted_at' => 1
												);
												$this->invoice_m->insert_invoice($array);


												$this->automation_rec_m->insert_automation_rec(array(
													'studentID' => $aTstudent->studentID,
													'date' => $date,
													'day' => $day,
													'month' => $month,
													'year' => $year,
													'nofmodule' => 5427279
												));

											}
										}

									}
								}

								if(count($automationTMember)) {
									if(isset($automationTMember[$aTstudent->studentID])) {
										if($automationTMember[$aTstudent->studentID] > 0) {
											if(!isset($allRecord[872677678][$aTstudent->studentID][$month][$year])) {
												$array = array(
													'schoolyearID' => $this->data['siteinfos']->school_year,
													'classesID' => $aTstudent->classesID,
													'studentID' => $aTstudent->studentID,
													'feetype' => $this->lang->line('dashboard_transportfee'),
													'amount' => (int)$automationTMember[$aTstudent->studentID],
													'discount' => 0,
													'paidstatus' => 0,
													'userID' => 1,
													'usertypeID' => 1,
													'uname' => $superAdmin->name,
													'date' => date("Y-m-d"),
													'create_date' => date('Y-m-d'),
													'day' => date('d'),
													'month' => date('m'),
													'year' => date('Y'),
													'deleted_at' => 1
												);
												$this->invoice_m->insert_invoice($array);

												$this->automation_rec_m->insert_automation_rec(array(
													'studentID' => $aTstudent->studentID,
													'date' => $date,
													'day' => $day,
													'month' => $month,
													'year' => $year,
													'nofmodule' => 872677678
												));
											}

										}
									}
								}


								if(count($automationHMember)) {
									if(isset($automationHMember[$aTstudent->studentID])) {
										if($automationHMember[$aTstudent->studentID] > 0) {
											if(!isset($allRecord[467835][$aTstudent->studentID][$month][$year])) {
												$array = array(
													'schoolyearID' => $this->data['siteinfos']->school_year,
													'classesID' => $aTstudent->classesID,
													'studentID' => $aTstudent->studentID,
													'feetype' => $this->lang->line('dashboard_hostelfee'),
													'amount' => (int)$automationHMember[$aTstudent->studentID],
													'discount' => 0,
													'paidstatus' => 0,
													'userID' => 1,
													'usertypeID' => 1,
													'uname' => $superAdmin->name,
													'date' => date("Y-m-d"),
													'create_date' => date('Y-m-d'),
													'day' => date('d'),
													'month' => date('m'),
													'year' => date('Y'),
													'deleted_at' => 1
												);

												$this->invoice_m->insert_invoice($array);

												$this->automation_rec_m->insert_automation_rec(array(
													'studentID' => $aTstudent->studentID,
													'date' => $date,
													'day' => $day,
													'month' => $month,
													'year' => $year,
													'nofmodule' => 467835
												));
											}
										}
									}
								}
							}
						}

						$this->automation_shudulu_m->insert_automation_shudulu(array(
							'date' => $date,
							'day' => $day,
							'month' => $month,
							'year' => $year
						));
					}
				} else {
					$this->automation_shudulu_m->insert_automation_shudulu(array(
						'date' => $date,
						'day' => $day,
						'month' => $month,
						'year' => $year
					));
				}
			}
		}
		
	}

	public function index() {
		$this->data['headerassets'] = array(
			'js' => array(
				'assets/highcharts/highcharts.js',
				'assets/highcharts/highcharts-more.js',
				'assets/highcharts/data.js',
				'assets/highcharts/drilldown.js',
				'assets/highcharts/exporting.js'
			)
		);


		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		$students 	= $this->student_m->get_order_by_student(array('schoolyearID' => $schoolyearID));
		$classes	= pluck($this->classes_m->get_classes(), 'obj', 'classesID');
		$teachers	= $this->teacher_m->get_teacher();
		$parents	= $this->parents_m->get_parents();
		$books		= $this->book_m->get_book();
		$feetypes	= $this->feetypes_m->get_feetypes();
		$lmembers	= $this->lmember_m->get_lmember();
		$events		= $this->event_m->get_event();
		$holidays	= $this->holiday_m->get_order_by_holiday(array('schoolyearID' => $schoolyearID));
		$allmenu 	= pluck($this->menu_m->get_order_by_menu(), 'icon', 'link');
		$allmenulang = pluck($this->menu_m->get_order_by_menu(), 'menuName', 'link');

		if($this->session->userdata('usertypeID') == 3) {
			$getLoginStudent = $this->student_m->get_single_student(array('username' => $this->session->userdata('username')));
			if(count($getLoginStudent)) {
				$subjects	= $this->subject_m->get_order_by_subject(array('classesID' => $getLoginStudent->classesID));
				$invoices	= $this->invoice_m->get_order_by_invoice(array('studentID' => $getLoginStudent->studentID));
				$lmember = $this->lmember_m->get_single_lmember(array('studentID' => $getLoginStudent->studentID));
				if(count($lmember)) {
					$issues = $this->issue_m->get_order_by_issue(array("lID" => $lmember->lID, 'return_date' => NULL));
				} else {
					$issues = array();
				}
			} else {
				$invoices = array();
				$subjects = array();
				$issues = array();
			}
		} else {
			$invoices	= $this->invoice_m->get_invoice();
			$subjects	= $this->subject_m->get_subject();
			$issues		= $this->issue_m->get_order_by_issue(array('return_date' => NULL));
		}

		$deshboardTopWidgetUserTypeOrder = $this->session->userdata('master_permission_set');

		$this->data['dashboardWidget']['students'] 	= count($students);
		$this->data['dashboardWidget']['classes']  	= count($classes);
		$this->data['dashboardWidget']['teachers'] 	= count($teachers);
		$this->data['dashboardWidget']['parents'] 	= count($parents);
		$this->data['dashboardWidget']['subjects'] 	= count($subjects);
		$this->data['dashboardWidget']['books'] 	= count($books);
		$this->data['dashboardWidget']['feetypes'] 	= count($feetypes);
		$this->data['dashboardWidget']['lmembers'] 	= count($lmembers);
		$this->data['dashboardWidget']['events'] 	= count($events);
		$this->data['dashboardWidget']['issues'] 	= count($issues);
		$this->data['dashboardWidget']['holidays'] 	= count($holidays);
		$this->data['dashboardWidget']['invoices'] 	= count($invoices);
		$this->data['dashboardWidget']['allmenu'] 	= $allmenu;
		$this->data['dashboardWidget']['allmenulang'] 	= $allmenulang;
		
		$attendanceSystem = $this->data['siteinfos']->attendance;
		$this->data['attendanceSystem'] = $attendanceSystem;

		if($attendanceSystem != 'subject') {
			$attendances = $this->sattendance_m->get_order_by_attendance(array('schoolyearID' => $schoolyearID, 'monthyear' => date('m-Y')));

			$classWiseAttendance = array();
			foreach ($attendances as $attendance ) {

				for($i=1;$i<=31;$i++) {

					if($i > date('d')) break;

					$date = 'a'.$i;

					if(!isset($classWiseAttendance[$attendance->classesID][$i]['P'])) {
						$classWiseAttendance[$attendance->classesID][$i]['P'] = 0;
					}

					if(!isset($classWiseAttendance[$attendance->classesID][$i]['A'])) {
						$classWiseAttendance[$attendance->classesID][$i]['A'] = 0;
					}

					if($attendance->$date == 'P' || $attendance->$date == 'L') {
						$classWiseAttendance[$attendance->classesID][$i]['P']++;
					} else {
						$classWiseAttendance[$attendance->classesID][$i]['A']++;
					}

				}

			}

			// dd($classWiseAttendance);
			$todaysAttendance = array();
			foreach ($classWiseAttendance as $key => $value) {
				$todaysAttendance[$key] = $value[(int)date('d')];
			}

			$this->data['classes'] = $classes;
			// dd(implode(',', pluck($classes, 'classes')));
			$this->data['classWiseAttendance'] = $classWiseAttendance;
			$this->data['todaysAttendance'] = $todaysAttendance;
		} else {
			$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array('schoolyearID' => $schoolyearID, 'monthyear' => date('m-Y')));

			$subjectWiseAttendance = array();
			// dd($attendances);
			foreach ($attendances as $attendance ) {

				for($i=1;$i<=31;$i++) {

					if($i > date('d')) break;

					$date = 'a'.$i;

					if(!isset($subjectWiseAttendance[$attendance->classesID][$attendance->subjectID][$i]['P'])) {
						$subjectWiseAttendance[$attendance->classesID][$attendance->subjectID][$i]['P'] = 0;
					}

					if(!isset($subjectWiseAttendance[$attendance->classesID][$attendance->subjectID][$i]['A'])) {
						$subjectWiseAttendance[$attendance->classesID][$attendance->subjectID][$i]['A'] = 0;
					}

					if($attendance->$date == 'P' || $attendance->$date == 'L') {
						$subjectWiseAttendance[$attendance->classesID][$attendance->subjectID][$i]['P']++;
					} else {
						$subjectWiseAttendance[$attendance->classesID][$attendance->subjectID][$i]['A']++;
					}

				}

			}

			// dd($subjectWiseAttendance);
			$todaysSubjectWiseAttendance = array();
			foreach ($subjectWiseAttendance as $class => $subject) {
				foreach ($subject as $key => $value) {
					if(!isset($todaysSubjectWiseAttendance[$class])) {
						$todaysSubjectWiseAttendance[$class]['P'] = 0;
						$todaysSubjectWiseAttendance[$class]['A'] = 0;
					}
					$todaysSubjectWiseAttendance[$class]['P'] += $value[(int)date('d')]['P'];
					$todaysSubjectWiseAttendance[$class]['A'] += $value[(int)date('d')]['A'];
				}
			}
			// dd($todaysSubjectWiseAttendance);

			$this->data['classes'] = $classes;
			// dd(implode(',', pluck($classes, 'classes')));
			$this->data['subjectWiseAttendance'] = $subjectWiseAttendance;
			$this->data['todaysSubjectWiseAttendance'] = $todaysSubjectWiseAttendance;
		}



		$months = array(
		    1 => 'January',
		    'February',
		    'March',
		    'April',
		    'May',
		    'June',
		    'July ',
		    'August',
		    'September',
		    'October',
		    'November',
		    'December',
		);

		$expense = $this->expense_m->get_order_by_expense(array('expenseyear' => date('Y'), 'schoolyearID' => $schoolyearID));
		$income  = $this->payment_m->get_order_by_payment(array('paymentyear' => date('Y'), 'schoolyearID' => $schoolyearID));

		$expenseMonthAndDay = array();
		$expenseMonthTotal  = array();
		foreach ($expense as $key => $value) {
			if(!isset($expenseMonthAndDay[(int)$value->expensemonth][$value->expenseday])) {
				$expenseMonthAndDay[(int)$value->expensemonth][$value->expenseday] = 0;
			}
			$expenseMonthAndDay[(int)$value->expensemonth][$value->expenseday] += $value->amount;

			if(!isset($expenseMonthTotal[(int)$value->expensemonth])) {
				$expenseMonthTotal[(int)$value->expensemonth] = 0;
			}
			$expenseMonthTotal[(int)$value->expensemonth] += $value->amount;
		}

		$incomeMonthAndDay = array();
		$incomeMonthTotal  = array();
		foreach ($income as $key => $value) {
			if(!isset($incomeMonthAndDay[(int)$value->paymentmonth][$value->paymentday])) {
				$incomeMonthAndDay[(int)$value->paymentmonth][$value->paymentday] = 0;
			}

			$incomeMonthAndDay[(int)$value->paymentmonth][$value->paymentday] += $value->paymentamount;

			if(!isset($incomeMonthTotal[(int)$value->paymentmonth])) {
				$incomeMonthTotal[(int)$value->paymentmonth] = 0;
			}
			$incomeMonthTotal[(int)$value->paymentmonth] += $value->paymentamount;
		}

		$this->data['months'] = $months;
		$this->data['incomeMonthAndDay'] = $incomeMonthAndDay;
		$this->data['incomeMonthTotal'] = $incomeMonthTotal;
		$this->data['expenseMonthAndDay'] = $expenseMonthAndDay;
		$this->data['expenseMonthTotal'] = $expenseMonthTotal;

		$currentDate = strtotime(date('Y-m-d H:i:s'));
		$previousSevenDate = strtotime(date('Y-m-d 00:00:00', strtotime('-7 days')));
		// dd(date('Y-m-d 00:00:00', strtotime('-7 days')));


		$visitors = $this->loginlog_m->get_order_by_loginlog(array('login <= ' => $currentDate, 'login >= ' => $previousSevenDate));
		$showChartVisitor = array();
		foreach ($visitors as $visitor) {
			$date = date('j M',$visitor->login);
			if(!isset($showChartVisitor[$date])) {
				$showChartVisitor[$date] = 0;
			}
			$showChartVisitor[$date]++;
		}

		$this->data['showChartVisitor'] = $showChartVisitor;
		
		// dd($incomeMonthAndDay);
		//
		// dd($expenseMonthTotal);


		// dd($classWiseAttendance);
		// dd($todaysAttendance);
		// dd(date('d'));

		// dd($this->session->userdata);
		// dump($students);
		// dd(count($students));


		$userTypeID = $this->session->userdata('usertypeID');
		$userName = $this->session->userdata('username');
		$this->data['usertype'] = $this->session->userdata('usertype');

		if($userTypeID == 1) {
			$this->data['user'] = $this->systemadmin_m->get_single_systemadmin(array('username'  => $userName));
		} elseif($userTypeID == 2) {
			$this->data['user'] = $this->teacher_m->get_single_teacher(array('username'  => $userName));
		}  elseif($userTypeID == 3) {
			$this->data['user'] = $this->student_m->get_single_student(array('username'  => $userName));
		} elseif($userTypeID == 4) {
			$this->data['user'] = $this->parents_m->get_single_parents(array('username'  => $userName));
		} else {
			$this->data['user'] = $this->user_m->get_single_user(array('username'  => $userName));
		}


		$this->data['notices'] = $this->notice_m->get_order_by_notice(array('schoolyearID' => $schoolyearID));
		$this->data['holidays'] = $this->holiday_m->get_order_by_holiday(array('schoolyearID' => $schoolyearID));
		$this->data['events'] = $this->event_m->get_event();


		$this->data["subview"] = "dashboard/index";
		$this->load->view('_layout_main', $this->data);

	}

	public function getDayWiseAttendance()
	{
		$dayWiseAttendance = json_decode($this->input->post('dayWiseAttendance'), true);
		$type = $this->input->post('type');
		$showChartData = array();
		foreach ($dayWiseAttendance as $key => $value) {
			$showChartData[$key] = $value[$type];
		}
		echo json_encode($showChartData);
	}

	public function dayWiseExpenseOrIncome()
	{
		$type = $this->input->post('type');
		$monthID = $this->input->post('monthID');
		$days = cal_days_in_month(CAL_GREGORIAN, $monthID, date('Y'));
		$dayWiseData = json_decode($this->input->post('dayWiseData'), true);

		$showChartData = array();
		for ($i=1; $i <= $days; $i++) {
			if(!isset($dayWiseData[$i])) {
				$showChartData[$i] = 0;
			} else {
				$showChartData[$i] = $dayWiseData[$i];
			}
		}

	    echo json_encode($showChartData);
	}

	public function getSubjectWiseAttendance()
	{
		$subjectWiseAttendance = json_decode($this->input->post('subjectWiseAttendance'), true);
		$classID = $this->input->post('classID');
		$data['subjects'] = pluck($this->subject_m->get_order_by_subject(array('classesID' => $classID)), 'obj', 'subjectID');
		$present = array();
		$absent = array();
		foreach ($subjectWiseAttendance as $subjectID => $days) {
			foreach ($days as $key => $attendance) {
				if(!isset($present[$subjectID])) {
					$present[$subjectID] = 0;
				}

				if(!isset($absent[$subjectID])) {
					$absent[$subjectID] = 0;
				}

				$present[$subjectID] += $attendance['P'];
				$absent[$subjectID] += $attendance['A'];
			}
		}

		$data['present'] = $present;
		$data['absent'] = $absent;
		$data['subjectWiseAttendance'] = $subjectWiseAttendance;
		echo json_encode($data);
	}

	public function getAllRec($arrays) {
		$returnArray = array();
		if(count($arrays)) {
			foreach ($arrays as $key => $array) {
				$returnArray[$array->nofmodule][$array->studentID][$array->month][$array->year] = 'Yes';
			}
		}
		return $returnArray;
	}


}


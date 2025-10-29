<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\User;
use App\Models\MemberDetail;
use App\Models\MemberHealthTrack;
use App\Models\Client;
use App\Models\FeePayment;
use App\Models\FeePaymentSchedule;
use App\Models\Config;
use App\Models\Attendance;
use App\Models\Trainer;
use App\Models\MemberTrainer;
use App\Models\MembershipTimeSchedule;
use App\Models\FeePaymentScheduleHistory;
use App\Models\FeePaymentHistory;
use App\Models\TransferMembership;
use App\Models\MembershipTransferLog;
use App\Models\FeePaymentScheduleArchive;
use App\Models\RenewalHistory;
use App\Models\StaffSalary;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $client_settings = $request->client_settings;
        $allMembers = User::where(['isAdmin' => 0])->select('id','name', 'phone_no')->get();
        $day_events = [];
        $time_schedules = MembershipTimeSchedule::join('memberships', 'membership_time_schedules.membership_id', '=', 'memberships.id')
                            ->select('membership_time_schedules.start_time', 'memberships.type as membership_type')
                            ->get();
        foreach ($time_schedules as $key => $value) {
            $day_events[$key]['membership'] = $value->membership_type;
            $day_events[$key]['start_time'] = date('h:i A', strtotime($value->start_time));
        }
        $today = date('Y-m-d');
        $days = [];
        $current_start_date = date('Y-m-01', strtotime($today));
        $current_end_date = date('Y-m-t', strtotime($today));
        for($i=$current_start_date; $i<=$current_end_date; $i = date('Y-m-d', strtotime($i . ' +1 day'))) {
            $days[] = [
                'date' => Carbon::parse($i),
                'is_current_month' => (date('m', strtotime($i)) == date('m', strtotime($today))),
                'events' => $day_events,
                'is_today' => ($i == $today),
                'day' => date('D', strtotime($i)),
            ];
        }
        return view('admin.dashboard', compact('client_settings', 'allMembers', 'days'));
    }
    public function memberRegistration(Request $request)
    {
        $client_settings = $request->client_settings;
        $memberships = Membership::all();
        $MembershipTimeSchedule = MembershipTimeSchedule::all();
        $allMembers = User::where(['isAdmin' => 0])->select('id','name')->get();
        $recentMembers = User::where(['isAdmin' => 0])
                          ->join('memberdetails', 'users.id', '=', 'memberdetails.user_id')
                          ->select('users.name', 'memberdetails.membership_start_date')
                          ->orderBy('users.created_at', 'desc')
                          ->take(10)
                          ->get();
        return view('admin.member_reg', compact('memberships', 'recentMembers', 'client_settings', 'allMembers', 'MembershipTimeSchedule'));
    }

    public function trainerRegistration(Request $request)
    {
        $client_settings = $request->client_settings;
        return view('admin.trainer_reg', compact('client_settings'));
    }

    public function registerTrainer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:10',
            'dob' => 'required|date',
            'gender' => 'required|string|in:m,f,o',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'specialization' => 'nullable|string|max:500',
            'certificate_no' => 'nullable|string|max:255'
        ]);

        // If validation passes, create the trainer
        $trainer = Trainer::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'height' => $request->height,
            'weight' => $request->weight,
            'specialization' => $request->specialization,
            'certificate_no' => $request->certificate_no,
        ]);
        return response()->json(['message' => 'Trainer registered successfully', 'status' => 'success'], 200);
    }

    public function memberAllotTrainer(Request $request)
    {
        $client_settings = $request->client_settings;
        $allMembers = User::where(['isAdmin' => 0])->select('id','name', 'phone_no')->get();
        $allTrainers = Trainer::all();
        return view('admin.allot_trainer', compact('client_settings', 'allMembers', 'allTrainers'));
    }

    public function allotTrainer(Request $request) {
        $member = User::find($request->member_id);
        if(!$member) {
            return response()->json(['message' => 'Member not found', 'status' => 'error'], 404);
        }

        $trainers = Trainer::whereIn('id', (array)$request->trainer_ids)->get();
        if($trainers->isEmpty()) {
            return response()->json(['message' => 'Trainers not found', 'status' => 'error'], 404);
        }
        
        $existing = MemberTrainer::where('member_id', $member->id)->get();
        foreach ($existing as $key => $value) {
            if(!in_array($value->trainer_id, (array)$request->trainer_ids)) {
                // Remove the trainer allotment if not in the new list
                MemberTrainer::where('member_id', $member->id)->where('trainer_id', $value->trainer_id)->delete();
            }
        }

        // Allot the trainers to the member
        foreach ((array)$request->trainer_ids as $trainer_id) {
            MemberTrainer::firstOrCreate([
                'member_id' => $member->id,
                'trainer_id' => $trainer_id,
                'allotment_date' => Carbon::now()->toDateString()
            ]);
        }
        return response()->json(['message' => 'Trainers allotted successfully', 'status' => 'success'], 200);
    }

    public function getTrainers(Request $request) {
        $memberId = $request->member_id;
        $member_trainers = MemberTrainer::where('member_id', $memberId)->pluck('trainer_id')->toArray();
        return response()->json(['message' => 'Trainers fetched successfully', 'data' => $member_trainers, 'status' => 'success'], 200);
    }

    public function trainerAllotments(Request $request) {
        // Get the number of members allotted to each trainer
        $trainerAllotments = MemberTrainer::rightJoin('trainers', 'member_trainer.trainer_id', '=', 'trainers.id')
                            ->select('trainers.id as trainer_id', 'trainers.name as trainer_name', \DB::raw('COUNT(member_trainer.member_id) as member_count'))
                            ->groupBy('trainers.id', 'trainers.name')
                            ->get();
        
        // Store the result in an array format
        $trainers = $allotments = [];
        $i=0;
        foreach ($trainerAllotments as $allotment) {
            $trainers[] = $allotment->trainer_name;
            $allotments[] = $allotment->member_count;
        }
        $data = [
            'trainers' => $trainers,
            'allotments' => $allotments
        ];
        return response()->json($data);
    }

    public function registrations() {
        $today = Carbon::now();
        $currentMonth = $today->format('M'); // Get short month name like "Oct"
        $lastMonth = $today->subMonth()->format('M'); // Get short month name for last month
        $last2ndmonth = $today->subMonth()->format('M'); // Get short month name for 2nd last month
        $currentMonthRegistrationCount = User::where('isAdmin', 0)
                                        ->whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->count();
        $lastMonthRegistrationCount = User::where('isAdmin', 0)
                                        ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                        ->whereYear('created_at', Carbon::now()->subMonth()->year)
                                        ->count();
        $last2ndmonthRegistrationCount = User::where('isAdmin', 0)
                                        ->whereMonth('created_at', Carbon::now()->subMonths(2)->month)
                                        ->whereYear('created_at', Carbon::now()->subMonths(2)->year)
                                        ->count();

        $data = [
            'months' => [$last2ndmonth, $lastMonth, $currentMonth],
            'registrations' => [$last2ndmonthRegistrationCount, $lastMonthRegistrationCount, $currentMonthRegistrationCount]
        ];
        
        return response()->json($data);
    }

    public function recentRegistrations(Request $request) {
        $selectedMonths = $request->input('months', 3); // Default to last 3 months if not provided
        $registrations = [];
        $months = [];
        for ($i = $selectedMonths - 1; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthName = $monthDate->format('M');
            $yearName = $monthDate->format('y');
            $year = $monthDate->year;
            $monthCount = User::where('isAdmin', 0)
                            ->whereMonth('created_at', $monthDate->month)
                            ->whereYear('created_at', $year)
                            ->count();
            $months[] = $monthName." ".$yearName;
            $registrations[] = $monthCount;
        }
        $data = [
            'months' => $months,
            'registrations' => $registrations
        ];
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function currentMonthFeeCollection() {
        $currentMonth = Carbon::now()->format('M'); // Get short month name like "Oct"
        $currentYear = Carbon::now()->year;
        $currentMonthYear = $currentMonth . " " . $currentYear; // e.g., "Oct 2023"
        $totalFees = FeePaymentSchedule::where('for_month', $currentMonthYear)->count('id');
        $paidFees = FeePaymentSchedule::where('for_month', $currentMonthYear)->where('is_paid', 1)->count('id');
        $unpaidFees = FeePaymentSchedule::where('for_month', $currentMonthYear)->where('is_paid', 0)->count('id');
        if($totalFees == 0) {
            $totalFees = 1; // To avoid division by zero
        }
        $paidPercentage = ($paidFees / $totalFees) * 100;
        $unpaidPercentage = ($unpaidFees / $totalFees) * 100;
        $data = [
            'months' => ['Paid', 'Unpaid'],
            'fee_collections' => [$paidPercentage, $unpaidPercentage]
        ];
        return response()->json($data);
    }

    public function recentFeeCollections(Request $request) {
        $selectedMonth = $request->input('month');
        $totalFees = FeePaymentSchedule::where('for_month', $selectedMonth)->count('id');
        $paidFees = FeePaymentSchedule::where('for_month', $selectedMonth)->where('is_paid', 1)->count('id');
        $unpaidFees = FeePaymentSchedule::where('for_month', $selectedMonth)->where('is_paid', 0)->count('id');
        if($totalFees == 0) {
            $totalFees = 1; // To avoid division by zero
        }
        $paidPercentage = ($paidFees / $totalFees) * 100;
        $unpaidPercentage = ($unpaidFees / $totalFees) * 100;
        $data = [
            'months' => ['Paid', 'Unpaid'],
            'fee_collections' => [$paidPercentage, $unpaidPercentage]
        ];
        return response()->json($data);
    }

    public function registerMember(Request $request) {
        // Logic to create user and member profile goes here
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt('password'); // Set a default password or send email
        $user->address = $request->address;
        $user->phone_no = $request->phone;
        $user->save();
        if($user->id){
            // Get the membership to calculate end date
            $membership = Membership::find($request->membership_type);
            $startDate = Carbon::parse($request->membership_start_date);
            $membership_end_date = $startDate->addMonths($membership->duration_months);

            // Calculate age based on DOB
            $dob = Carbon::parse($request->dob);
            $age = $dob->diffInYears(Carbon::now());

            // Get Schedule time from DB
            $time_of_schedule = MembershipTimeSchedule::where('id', $request->time_schedule)->first();

            $memberDetail = new MemberDetail();
            $memberDetail->user_id = $user->id;
            $memberDetail->dob = $request->dob;
            $memberDetail->age = $age;
            $memberDetail->gender = $request->gender;
            $memberDetail->membership_type = $request->membership_type;
            $memberDetail->membership_start_date = $request->membership_start_date;
            $memberDetail->membership_end_date = $membership_end_date;
            $memberDetail->emergency_contact_name = $request->emergency_contact_name;
            $memberDetail->emergency_contact_phone = $request->emergency_contact_phone;
            $memberDetail->fitness_goals = $request->fitness_goals ?? '';
            $memberDetail->medical_conditions = $request->medical_conditions ?? '';
            $memberDetail->membership_schedule_time = $time_of_schedule['start_time'] ?? null;
            $memberDetail->save();

            $membership_info = Membership::where(['id' => $request->membership_type])->first();
            $total_months = $membership_info->duration_months;
            $payment_type = $membership_info->payment_type;
            $admission_fee = $membership_info->admission_fee;
            $monthly_fee = $membership_info->monthly_fee;
            $one_time_fee = $membership_info->one_time_fee;
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            // Insert first record in payment schedule table
            $current_month = Carbon::parse($request->membership_start_date);
            $current_fee_day = $current_month->day;
            $current_fee_month = $current_month->month;
            $current_fee_year = $current_month->year;
            $current_month = Carbon::create($current_fee_year, $current_fee_month, $current_fee_day)->toDateString();
            $feeSchedule = new FeePaymentSchedule();
            $feeSchedule->member_id = $user->id;
            $feeSchedule->membership_type = $membership_info->id;
            $feeSchedule->for_month = $months[$current_fee_month - 1]." ".$current_fee_year;
            $feeSchedule->due_date = $current_month;
            $feeSchedule->amount = $monthly_fee;
            $feeSchedule->is_paid = $payment_type == 'single' ? true : false;
            $feeSchedule->save();

            if($total_months > 0) {
                for($month = 1; $month <= $total_months - 1; $month++) {
                    $next_month = Carbon::parse($request->membership_start_date)->addMonths($month);
                    $next_fee_day = $next_month->day;
                    $next_fee_month = $next_month->month;
                    $next_fee_year = $next_month->year;
                    $next_month = Carbon::create($next_fee_year, $next_fee_month, $next_fee_day)->toDateString();
                    $feeSchedule = new FeePaymentSchedule();
                    $feeSchedule->member_id = $user->id;
                    $feeSchedule->membership_type = $membership_info->id;
                    $feeSchedule->for_month = $months[$next_fee_month - 1]." ".$next_fee_year;
                    $feeSchedule->due_date = $next_month;
                    $feeSchedule->amount = $monthly_fee;
                    $feeSchedule->is_paid = $payment_type == 'single' ? true : false;
                    $feeSchedule->save();
                }
            }

            if($payment_type == 'single') {
                // If one time payment, mark all as paid
                $fee_schedule = FeePaymentSchedule::where(['member_id' => $user->id, 'membership_type' => $membership_info->id])->get();
                $payment_ids = [];
                foreach ($fee_schedule as $key => $fee) {
                    $payment_ids[] = $fee->id;
                }
                // Create FeePayment record
                $feePayment = new FeePayment();
                $feePayment->member_id = $user->id;
                $feePayment->fee_type = 'one_time';
                $feePayment->pay_for_month = implode(',', $payment_ids);
                $feePayment->amount = $membership_info->one_time_fee;
                $feePayment->payment_date = date('Y-m-d');
                $feePayment->payment_method = $request->payment_method ?? 'cash';
                $feePayment->transaction_id = $request->transaction_id ?? '';
                $feePayment->notes = '';
                $feePayment->save();

                // Update the payment schedule records as paid
                foreach ($fee_schedule as $key => $fee) {
                    $fee->is_paid = true;
                    $fee->fee_payment_id = $feePayment->id;
                    $fee->save();
                }
            }        
            return response()->json(['message' => 'Member registered successfully.'], 200);
        }
        return response()->json(['message' => 'Member additional info could not be saved. Got to member update page to update.'], 500);
    }

    public function getClient(Request $request)
    {
        $client_settings = $request->client_settings;
        $client=Client::where(['id'=>1])->get();
        if(count($client) > 0){
            $client=$client[0];
        }
        return view('admin.client', compact('client', 'client_settings'));
    }

    public function addUpdateClient(Request $request) {
        $inputs=$request->all();
        $client=Client::where(['id'=>1])->get();
        if(count($client) > 0){
            $data = [];
            $data['name'] = $inputs['name'];
            $data['email'] = $inputs['email'];
            $data['address'] = $inputs['address'];
            $data['phone_no'] = $inputs['phone_no'];
            $data['date_of_icorporation'] = $inputs['date_of_icorporation'];
            $data['contact_person'] = $inputs['contact_person'];
            $data['gst_id'] = $inputs['gst_id'];
            $data['business_type'] = $inputs['business_type'];
            Client::where(['id'=>1])->update($data);
            return response()->json(['message' => 'Client updated successfully.', 'data' => $client[0], 'status' => 'updated'], 200);
        } else {
            $client = new Client();
            $client->name = $inputs['name'];
            $client->email = $inputs['email'];
            $client->address = $inputs['address'];
            $client->phone_no = $inputs['phone_no'];
            $client->date_of_icorporation = $inputs['date_of_icorporation'];
            $client->contact_person = $inputs['contact_person'];
            $client->gst_id = $inputs['gst_id'];
            $client->business_type = $inputs['business_type'];
            $client->save();
            return response()->json(['message' => 'Client added successfully.', 'data' => $client, 'status' => 'added'], 200);
        }
    }

    public function getMemberships(Request $request) {
        $client_settings = $request->client_settings;
        $memberships = Membership::join('membership_time_schedules', 'memberships.id', '=', 'membership_time_schedules.membership_id', 'left')
                        ->select('memberships.*', \DB::raw('GROUP_CONCAT(membership_time_schedules.start_time) as time_schedules'))
                        ->groupBy('memberships.id')
                        ->get();
        \Log::info($memberships);
        return view('admin.membership', compact('memberships', 'client_settings'));
    }

    public function addMembership(Request $request) {
        $membership = new Membership();
        $membership->type = $request->type;
        $membership->duration_months = $request->duration_months;
        $membership->payment_type = $request->payment_type;
        $membership->admission_fee = $request->payment_type == 'recurring' ? $request->adm_fee : 0;
        $membership->monthly_fee = $request->payment_type == 'recurring' ? $request->monthly_fee : 0;
        $membership->one_time_fee = $request->payment_type == 'single' ? $request->one_time_fee : 0;
        $membership->benefits = $request->benefits;
        $membership->description = $request->description;
        $membership->is_active = $request->is_active ? true : false;
        $membership->is_transferable = $request->is_transferable ? true : false;
        $membership->save();

        if($request->is_time_schedule_required) {
            $time_schedules = (array)$request->time_schedules;
            $time_schedules = array_filter($time_schedules); // Remove empty values
            if(!empty($time_schedules)) {
                MembershipTimeSchedule::where('membership_id', $membership->id)->delete(); // Clear existing schedules if any
                foreach ($time_schedules as $time) {
                    $schedule = new MembershipTimeSchedule();
                    $schedule->membership_id = $membership->id;
                    $schedule->start_time = $time;
                    $schedule->save();
                }
            }
        }
        return response()->json(['message' => 'Membership plan added successfully.', 'data' => $membership], 200);
    }

    public function editMemberships(Request $request) {
        $membership = Membership::find($request->membership_id);
        $membership->type = $request->type;
        $membership->duration_months = $request->duration_months;
        $membership->payment_type = $request->edit_payment_type;
        $membership->admission_fee = $request->edit_payment_type == 'recurring' ? $request->edit_adm_fee : 0;
        $membership->monthly_fee = $request->edit_payment_type == 'recurring' ? $request->edit_monthly_fee : 0;
        $membership->one_time_fee = $request->edit_payment_type == 'single' ? $request->edit_one_time_fee : 0;
        $membership->benefits = $request->benefits;
        $membership->description = $request->description;
        $membership->is_active = $request->is_active ? true : false;
        $membership->is_transferable = $request->is_transferable ? true : false;
        $membership->save();
        if($request->is_edit_time_schedule_required) {
            $time_schedules = (array)$request->time_schedules_edit;
            $time_schedules = array_filter($time_schedules); // Remove empty values
            \Log::info($time_schedules);
            if(!empty($time_schedules)) {
                MembershipTimeSchedule::where('membership_id', $membership->id)->delete(); // Clear existing schedules if any
                foreach ($time_schedules as $time) {
                    $schedule = new MembershipTimeSchedule();
                    $schedule->membership_id = $membership->id;
                    $schedule->start_time = $time;
                    $schedule->save();
                }
            }
        }
        return response()->json(['message' => 'Membership plan updated successfully.', 'data' => $membership], 200);
    }

    public function memberShow($id, Request $request) {
        $client_settings = $request->client_settings;
        $member = User::where(['id' => $id, 'isAdmin' => 0])->first();
        if(!$member) {
            return redirect()->route('member.registration')->with('error', 'Member not found.');
        }
        $memberDetail = MemberDetail::where(['user_id' => $id])
                        ->join('memberships', 'memberdetails.membership_type', '=', 'memberships.id')
                        ->select('memberdetails.*', 'memberships.type as membership_type_name', 'memberships.duration_months', 'memberships.payment_type', 
                                 'memberships.admission_fee', 'memberships.monthly_fee', 'memberships.one_time_fee', 'memberships.benefits', 'memberships.description')
                        ->first();
        $healthTracks = MemberHealthTrack::where(['user_id' => $id])->orderBy('measure_date', 'desc')->get();
        $feeSchedules = FeePaymentSchedule::where(['member_id' => $id])->get();
        $trainers = MemberTrainer::where(['member_id' => $id])
                    ->join('trainers', 'trainers.id', '=', 'member_trainer.trainer_id')
                    ->select('trainers.id', 'trainers.name')
                    ->get();
        return view('admin.member_show', compact('member', 'memberDetail', 'healthTracks', 'client_settings', 'feeSchedules', 'trainers'));
    }

    public function memberProgressTracker(Request $request) {
        $client_settings = $request->client_settings;
        $allMembers = User::where(['isAdmin' => 0])->select('id','name', 'phone_no')->get();
        return view('admin.member_progress_tracker', compact('client_settings', 'allMembers'));
    }

    public function fetchMemberProgress($member_id, $metric) {
        $member = User::where(['id' => $member_id, 'isAdmin' => 0])->first();
        if(!$member) {
            return response()->json(['message' => 'Member not found', 'status' => 'error'], 404);
        }
        $matric_values = [];
        $measure_dates = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $healthTracks = MemberHealthTrack::where(['user_id' => $member_id])->orderBy('measure_date', 'asc')->get();
        foreach ($healthTracks as $track) {
            $month = date('m', strtotime($track->measure_date));
            $year = date('y', strtotime($track->measure_date));
            $measure_dates[] = $months[$month - 1].' '.$year.' ('.date('d-m-y', strtotime($track->measure_date)).')';
            if($metric == 'Weight') {
                $matric_values[] = $track->weight;
            } else if($metric == 'Height') {
                $matric_values[] = $track->height;
            } else if($metric == 'BMI') {
                $matric_values[] = $track->bmi;
            } else if($metric == 'Body Fat Percentage') {
                $matric_values[] = $track->body_fat_percentage;
            } else if($metric == 'Muscle Mass') {
                $matric_values[] = $track->muscle_mass;
            } else if($metric == 'Waist Circumference') {
                $matric_values[] = $track->waist_circumference;
            } else if($metric == 'Hip Circumference') {
                $matric_values[] = $track->hip_circumference;
            } else if($metric == 'Chest Circumference') {
                $matric_values[] = $track->chest_circumference;
            } else if($metric == 'Thigh Circumference') {
                $matric_values[] = $track->thigh_circumference;
            } else if($metric == 'Arm Circumference') {
                $matric_values[] = $track->arm_circumference;
            }
        }
        return response()->json([
            'message' => 'Member progress data fetched successfully',
            'data' => [
                'measure_dates' => $measure_dates,
                'measurement_values' => $matric_values,
            ],
            'status' => 'success'
        ], 200);
    }

    public function addHealthRecord(Request $request) {
        $inputs = $request->all();
        $new_health_record = new MemberHealthTrack();
        $new_health_record->user_id = $inputs['membership_id'];
        $new_health_record->measure_date = date('Y-m-d');
        $new_health_record->weight = $inputs['weight'];
        $new_health_record->height = $inputs['height'];
        $new_health_record->bmi = $inputs['bmi'];
        $new_health_record->body_fat_percentage = $inputs['body_fat'];
        $new_health_record->muscle_mass = $inputs['muscle_mass'];
        $new_health_record->waist_circumference = $inputs['west_cir'];
        $new_health_record->hip_circumference = $inputs['hip_cir'];
        $new_health_record->chest_circumference = $inputs['chest_cir'];
        $new_health_record->thigh_circumference = $inputs['thigh_cir'];
        $new_health_record->arm_circumference = $inputs['arm_cir'];
        $new_health_record->notes = $inputs['note'];
        $new_health_record->save();
        return response()->json(['message' => 'Health record added'], 200);
    }

    public function feeCollection(Request $request) {
        $client_settings = $request->client_settings;
        $allMembers = User::where(['isAdmin' => 0])->select('id','name', 'phone_no')->get();
        $memberships = Membership::all();
        $members_fee = User::where(['isAdmin' => 0])
                      ->join('fee_payment_schedule', 'users.id', '=', 'fee_payment_schedule.member_id')
                      ->join(\DB::raw('(SELECT member_id, MIN(due_date) as min_due_date FROM fee_payment_schedule WHERE is_paid = 0 GROUP BY member_id) as oldest_fees'), function($join) {
                          $join->on('fee_payment_schedule.member_id', '=', 'oldest_fees.member_id')
                               ->on('fee_payment_schedule.due_date', '=', 'oldest_fees.min_due_date');
                      })
                      ->where('fee_payment_schedule.is_paid', '=', 0)
                      ->select('users.id','users.name', 'users.phone_no', 'fee_payment_schedule.id as fee_schedule_id', 
                               'fee_payment_schedule.for_month', 'fee_payment_schedule.due_date', 'fee_payment_schedule.amount', 'fee_payment_schedule.is_paid')
                      ->get();
        return view('admin.fee_collection', compact('client_settings', 'allMembers', 'memberships', 'members_fee'));    
    }

    public function feeCollections(Request $request) {
        $client_settings = $request->client_settings;
        $today = Carbon::now();
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $current_month = $months[$today->month - 1]." ".$today->year;
        $total_collections = FeePayment::whereMonth('payment_date', $today->month)
                            ->whereYear('payment_date', $today->year)
                            ->sum('amount');
        $fee_months = [];
        $data = [
            'month' => [],
            'membership' => [],
            'total_amount' => []
        ];
        for($i=0; $i<=5; $i++) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthName = $months[$monthDate->month - 1];
            $yearName = $monthDate->year;
            $fee_months[] = $monthName." ".$yearName;
        }
        $memberships = Membership::all();
        // $fee_payment_schedules = FeePaymentSchedule::where(['is_paid' => 1])->get();
        // \Log::info($fee_payment_schedules);
        // foreach ($fee_payment_schedules as $schedule) {
        //     //\Log::info("Processing schedule for month: ".$schedule->for_month." and membership: ".$schedule->membership_type);
        //     if(isset($data['membership']) && isset($data['for_month']) && in_array($schedule->for_month, $data['for_month']) && in_array($schedule->membership_type, $data['membership'])) {
        //         \Log::info("Updating existing entry for month: ".$schedule->for_month." and membership: ".$schedule->membership_type);
        //         $index = array_search($schedule->for_month, $data['for_month']);
        //         $data['total_amount'][$index] += $schedule->amount;
        //     } else {
        //         //\Log::info("Adding new entry for month: ".$schedule->for_month." and membership: ".$schedule->membership_type);
        //         $data['membership'][] = $schedule->membership_type;
        //         $data['for_month'][] = $schedule->for_month;
        //         $data['total_amount'][] = $schedule->amount;
        //     }
        // }
        // \Log::info($data);
        return view('admin.fee_collections', compact('client_settings','total_collections', 'current_month', 'fee_months', 'memberships'));
    }

    public function fetchFeeCollections(Request $request) {
        $inputs = $request->all();
        $inputs = $inputs['fee_collections_filter'];
        \Log::info($inputs);
        $selectedMonth = $inputs['fee_month'];
        $selectedMembership = $inputs['membership_id'];
        $selectedMonthParts = explode(' ', $selectedMonth);
        if(count($selectedMonthParts) != 2) {
            return response()->json(['message' => 'Invalid month format', 'status' => 'error'], 400);
        }
        // Get the Month Details in format YYYY-MM-DD
        $monthName = $selectedMonthParts[0];
        $year = $selectedMonthParts[1];
        $months = ['Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06', 'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'];
        $start_date = $year . '-' . ($months[$monthName] ?? '01') . '-01';
        $end_date = date('Y-m-t', strtotime($start_date)); // Get last day of the month
        
        // If membership is selected in frontend
        $data = [];
        if($selectedMembership != -1 && $selectedMembership != 0) {
            $result = FeePayment::whereBetween('payment_date', [$start_date, $end_date])
                 ->join('fee_payment_schedule', 'fee_payment.id', '=', 'fee_payment_schedule.fee_payment_id')
                 ->select('fee_payment_schedule.member_id as fee_member_id', 'fee_payment_schedule.amount as schedule_amount', 'fee_payment_schedule.membership_type')
                 ->get();
            $total_amount = 0;
            $membership_details = Membership::where(['id' => $selectedMembership])->first();
            $member_ids = [];
            foreach ($result as $res) {
                if($res->membership_type == $selectedMembership) {
                    if($membership_details->payment_type == 'single') {
                        if(in_array($res->fee_member_id, $member_ids)) {
                            continue;
                        }
                        array_push($member_ids, $res->fee_member_id);
                        $total_amount += $membership_details->one_time_fee;
                    } else {
                        $total_amount += $res->schedule_amount;
                    }
                }
            }
            $data = [
                'label' => [$membership_details->type],
                'values' => [$total_amount],
                'membership' => $membership_details->type
            ];
            \Log::info($data);
        } else if($selectedMembership == -1 || $selectedMembership == 0) {
            $result = FeePayment::whereBetween('payment_date', [$start_date, $end_date])
                 ->join('fee_payment_schedule', 'fee_payment.id', '=', 'fee_payment_schedule.fee_payment_id')
                 ->select('fee_payment_schedule.member_id as fee_member_id', 'fee_payment_schedule.amount as schedule_amount', 'fee_payment_schedule.membership_type')
                 ->get();
            $member_ids = [];
            foreach ($result as $key => $res) {
                $membership_details = Membership::where(['id' => $res->membership_type])->first();
                if($membership_details) {
                    if(isset($data['label']) && in_array($membership_details->type, $data['label'])) {
                        if($membership_details->payment_type == 'single') {
                            if(in_array($res->fee_member_id, $member_ids)) {
                                continue;
                            }
                            array_push($member_ids, $res->fee_member_id);
                            $index = array_search($membership_details->type, $data['label']);
                            $data['values'][$index] += $membership_details->one_time_fee;
                        } else {
                            $index = array_search($membership_details->type, $data['label']);
                            $data['values'][$index] += $res->schedule_amount;
                        }
                    } else {
                        if($membership_details->payment_type == 'single') {
                            array_push($member_ids, $res->fee_member_id);
                            $data['label'][] = $membership_details->type;
                            $data['values'][] = $membership_details->one_time_fee;
                        } else {
                            $data['label'][] = $membership_details->type;
                            $data['values'][] = $res->schedule_amount;
                        }
                    }
                }
            }
            if(!isset($data)) {
                $data = [
                    'label' => [],
                    'values' => []
                ];
            }
        }

        // else if($selectedMembership == 0) {
        //     $cash_fee_collections = FeePayment::whereBetween('payment_date', [$start_date, $end_date])->where(['payment_method' => 'cash'])->sum('amount');
        //     $upi_fee_collections = FeePayment::whereBetween('payment_date', [$start_date, $end_date])->where(['payment_method' => 'upi'])->sum('amount');
        //     $data = [
        //         'label' => ['Cash', 'UPI'],
        //         'values' => [$cash_fee_collections, $upi_fee_collections]
        //     ];
        // }

        // Return the response
        return response()->json($data);
    }

    public function getPaymentSchedule($member_id) {
        $memberDet = MemberDetail::where(['user_id' => $member_id])->first();
        $membership_id = $memberDet->membership_type;
        $membership = Membership::where(['id' => $membership_id])->first();
        $feeSchedules = FeePaymentSchedule::where(['member_id' => $member_id])->get();
        $membername = User::where(['id' => $member_id])->first()->name;
        return response()->json([
            'message' => 'Pending fee details fetched successfully',
            'data' => [
                'membership' => $membership,
                'payment_schedule' => $feeSchedules,
                'membername' => $membername
            ],
            'status' => 'success'
        ], 200);
    }

    public function getConfig(Request $request) {
        $client_settings = $request->client_settings;
        $config = Config::all();
        if(count($config) > 0) {
            $config = $config[0];
        } else {
            $config = null;
        }
        return view('admin.config', compact('client_settings', 'config'));
    }

    public function saveConfig(Request $request) {
        $inputs = $request->all();
        $config = Config::first();
        //dd($config);
        if($config) {
            $config->membership_renewal_reminder = $inputs['membership_renewal_reminder'] ?? 0;
            $config->membership_transfer_limit = $inputs['membership_transfer_limit'] ?? 0;
            $config->attendance_opening_time = $inputs['attendance_opening_time'] ?? null;
            $config->attendance_last_time = $inputs['attendance_last_time'] ?? null;
            $config->save();
            return response()->json(['message' => 'Config updated successfully.', 'data' => $config, 'status' => 'updated'], 200);
        } else {
            $config = new Config();
            $config->membership_renewal_reminder = $inputs['membership_renewal_reminder'] ?? 0;
            $config->membership_transfer_limit = $inputs['membership_transfer_limit'] ?? 0;
            $config->attendance_opening_time = $inputs['attendance_opening_time'] ?? null;
            $config->attendance_last_time = $inputs['attendance_last_time'] ?? null;
            $config->save();
            return response()->json(['message' => 'Config added successfully.', 'data' => $config, 'status' => 'added'], 200);
        }
    }

    public function processFeePayment(Request $request) {
        $inputs = $request->all();
        $payment_ids = explode(',', $inputs['payment_ids']);
        if(count($payment_ids) == 0) {
            return response()->json(['message' => 'No payments selected to process.', 'status' => 'failed'], 400);
        }
        $payment_schedules = FeePaymentSchedule::whereIn('id', $payment_ids)->where('is_paid', false)->get();
        if(count($payment_schedules) == 0) {
            return response()->json(['message' => 'Selected payments are already paid or invalid.', 'status' => 'failed'], 400);
        }
        foreach($payment_schedules as $payment) {
            $feePayment = new FeePayment();
            $feePayment->member_id = $payment->member_id;
            $feePayment->fee_type = 'monthly';
            $feePayment->pay_for_month = $payment->for_month;
            $feePayment->amount = $payment->amount;
            $feePayment->payment_date = date('Y-m-d');
            $feePayment->payment_method = $inputs['payment_method'];
            $feePayment->transaction_id = $inputs['transaction_id'];
            $feePayment->notes = $inputs['note'] ?? '';
            $feePayment->save();
            if($feePayment->id) {
                $payment->is_paid = true;
                $payment->paid_on = date('Y-m-d');
                $payment->fee_payment_id = $feePayment->id;
                $payment->save();
            } else {
                return response()->json(['message' => 'Fee payment could not be processed.', 'status' => 'failed'], 200);
            }
        }
        return response()->json(['message' => 'Fee payment processed successfully.', 'data' => ['fee_payment' => $feePayment], 'status' => 'success'], 200);
    }

    public function processMultipleFeePayment(Request $request) {
        $inputs = $request->all();
        \Log::info($inputs);
        $payment_ids = $inputs['payment_member_ids'];
        $type = $inputs['type'];
        $payment_methods = $inputs['payment_method'];
        $transaction_ids = $inputs['transaction_id'];
        $notes = $inputs['note'];
        if(count($payment_ids) == 0) {
            return response()->json(['message' => 'No users selected to process.', 'status' => 'failed'], 400);
        }
        // Create FeePayment record
        foreach ($payment_ids as $key => $member_id) {
            $members_pending_fee = FeePaymentSchedule::where(['member_id' => $member_id, 'is_paid' => false])->first();
            $feePayment = new FeePayment();
            $feePayment->member_id = $member_id;
            $feePayment->fee_type = $type[$key] ?? '';
            $feePayment->pay_for_month = $members_pending_fee->for_month;
            $feePayment->amount = $members_pending_fee->amount;
            $feePayment->payment_date = date('Y-m-d');
            $feePayment->payment_method = $payment_methods[$key] ?? '';
            $feePayment->transaction_id = $transaction_ids[$key] ?? '';
            $feePayment->notes = $notes[$key] ?? '';
            $feePayment->save();
            if($feePayment->id) {
                $payment = FeePaymentSchedule::where(['id' => $members_pending_fee->id, 'is_paid' => false])->first();
                if($payment) {
                    $payment->is_paid = true;
                    $payment->paid_on = date('Y-m-d');
                    $payment->fee_payment_id = $feePayment->id;
                    $payment->save();
                }
            }
        }
        return response()->json(['message' => 'Multiple Fee payment processed successfully.', 'status' => 'success'], 200);
    }

    public function downloadReceipt(Request $request, $id, $membershipId) {
        // Find the fee payment schedule record
        $paymentSchedule = FeePaymentSchedule::with(['member', 'feePayment'])->find($id);
        
        if (!$paymentSchedule || !$paymentSchedule->is_paid) {
            //return redirect()->back()->with('error', 'Receipt not found or payment not completed.');
            return response()->json(['message' => 'Receipt not found or payment not completed.', 'status' => 'failed'], 404);
        }

        // Get member and payment details
        $member = $paymentSchedule->member;
        $feePayment = $paymentSchedule->feePayment;
        
        if (!$member || !$feePayment) {
            //return redirect()->back()->with('error', 'Invalid receipt data.');
            return response()->json(['message' => 'Invalid receipt data.', 'status' => 'failed'], 404);
        }

        $membership = Membership::where(['id' => $membershipId])->first();

        // Get client settings
        $client_settings = $request->client_settings ?? Client::first();

        // Generate receipt content (you can customize this as needed)
        $receiptData = [
            'receipt_id' => 'REC-' . str_pad($paymentSchedule->id, 6, '0', STR_PAD_LEFT),
            'member_name' => $member->name,
            'member_id' => $member->id,
            'membership_plan' => $membership->type,
            'payment_date' => $feePayment->payment_date,
            'amount' => $paymentSchedule->amount,
            'for_month' => $paymentSchedule->for_month,
            'payment_method' => $feePayment->payment_method,
            'transaction_id' => $feePayment->transaction_id,
        ];

        // Return the receipt view in a new tab/window
        return response()->view('admin.receipt', compact('receiptData', 'paymentSchedule', 'member', 'feePayment', 'client_settings'))->header('Content-Type', 'text/html');
    }

    public function downloadOneTimeReceipt(Request $request, $id, $membershipId) {
        // Get client settings
        $client_settings = $request->client_settings ?? Client::first();
        $member = User::where(['id' => $id, 'isAdmin' => 0])->first();
        $membership = Membership::where(['id' => $membershipId])->first();
        $fee_payment_schedule = FeePaymentSchedule::where(['member_id' => $id, 'membership_type' => $membershipId])->get();
        $first_month = $fee_payment_schedule[0]->for_month ?? '';
        $last_month = $fee_payment_schedule[count($fee_payment_schedule)-1]->for_month ?? '';
        $amount = 0;
        if($membership->payment_type == 'single') {
            $amount = $membership->one_time_fee;
        } else {
            $amount = $membership->admission_fee + $membership->monthly_fee * $membership->duration_months;
        }

        // Generate receipt content (you can customize this as needed)
        $receiptData = [
            // 'receipt_id' => 'REC-' . str_pad($member->id, 6, '0', STR_PAD_LEFT),
            'member_name' => $member->name,
            'member_id' => $member->id,
            'membership_plan' => $membership->type,
            'payment_date' => date('Y-m-d', strtotime($member->created_at)),
            'amount' => $amount,
            'for_month' => $first_month . ' to ' . $last_month,
            'payment_method' => '',
            'transaction_id' => '',
        ];

        // Return the receipt view in a new tab/window
        return response()->view('admin.receipt', compact('receiptData', 'client_settings'))->header('Content-Type', 'text/html');
    }

    public function getAttendance(Request $request) {
        $client_settings = $request->client_settings;
        $allMembers = User::where(['isAdmin' => 0])->select('id','name', 'phone_no')->get();
        $today = date('Y-m-d');
        $member_attendances = User::where(['isAdmin' => 0])->leftJoin('attendance', function($join) use ($today) {
                       $join->on('users.id', '=', 'attendance.member_id')
                            ->where('attendance.attendance_date', '=', $today);
                       })
                       ->select('users.id', 'users.name', 'users.phone_no', 'attendance.check_in_time', 'attendance.check_out_time', 
                       'attendance.status', 'attendance.shift')
                       ->get();
        return view('admin.attendance', compact('client_settings', 'allMembers', 'member_attendances'));
    }

    public function saveAttendance(Request $request, $member_id) {
        $inputs = $request->all();
        $config = Config::first();
        $today = date('Y-m-d');
        $attendance_opening_time = $config->attendance_opening_time;
        $attendance_last_time = $config->attendance_last_time;
        if(date('H:i') < $attendance_opening_time) {
            return response()->json(['message' => 'Attendance cannot be marked before '.date('h:i A', strtotime($attendance_opening_time)) . '.', 'status' => 'failed'], 200);
        }
        if(date('H:i') > $attendance_last_time) {
            return response()->json(['message' => 'Attendance cannot be marked after '.date('h:i A', strtotime($attendance_last_time)) . '.', 'status' => 'failed'], 200);
        }
        $attendance = Attendance::where(['member_id' => $member_id, 'attendance_date' => $today])->first();
        if($attendance) {
                return response()->json(['message' => 'Member already checked in today.', 'status' => 'failed'], 400);
        } else {
            if(date('H') >= 6 && date('H') < 12) {
                $inputs['shift'] = 'morning';
            } else if(date('H') >= 12 && date('H') < 18) {
                $inputs['shift'] = 'afternoon';
            } else {
                $inputs['shift'] = 'evening';
            }
            $attendance = new Attendance();
            $attendance->member_id = $member_id;
            $attendance->attendance_date = $today;
            $attendance->check_in_time = date('H:i');
            $attendance->status = 'present';
            $attendance->shift = $inputs['shift'];
            $attendance->save();
            return response()->json(['message' => 'Member checked in successfully.', 'status' => 'success', 
                                    'check_in_time' => date('h:i A', strtotime($attendance->check_in_time)), 'shift' => $attendance->shift], 200);
        }
    }

    public function attendanceReport(Request $request) {
        $client_settings = $request->client_settings;
        $allMembers = User::where(['isAdmin' => 0])->select('id','name', 'phone_no')->get();
        return view('admin.attendance_report', compact('client_settings', 'allMembers'));
    }

    public function attendanceReportData(Request $request) {
        $inputs = $request->all();
        if(!isset($inputs['from_date']) || !isset($inputs['to_date'])) {
            return response()->json(['message' => 'Please select from date and to date', 'status' => 'failed']);
        }
        $attendances_individual = $attendances_all = $member_details = [];
        if($inputs['memberId'] == "" || $inputs['memberId'] == 'all') {
            \Log::info('All members attendance');
            $from_date = date('Y-m-d', strtotime($inputs['from_date']));
            $to_date = date('Y-m-d', strtotime($inputs['to_date']));
            $members = User::where(['isAdmin' => 0])->orderBy('name','asc')->select('id', 'name')->get();
            foreach ($members as $member) {
                for($cdate=$from_date; $cdate <= $to_date; $cdate = date('Y-m-d', strtotime($cdate . ' +1 day'))) {
                    $attendances = Attendance::where(['member_id' => $member->id])
                                ->where('attendance_date', '=', $cdate)
                                ->orderBy('attendance_date', 'desc')
                                ->get();
                    $attendances_all[$member->id][$cdate]['id'] = $member->id;
                    $attendances_all[$member->id][$cdate]['name'] = $member->name;
                    if(count($attendances) > 0) {
                        $attendances_all[$member->id][$cdate]['status'] = 'Present';
                    } else {
                        if($cdate > date('Y-m-d')) {
                            // Future date
                            $attendances_all[$member->id][$cdate]['status'] = 'NA';    
                        } else {
                            $attendances_all[$member->id][$cdate]['status'] = 'Absent';    
                        }
                        //$attendances_all[$member->id][$cdate]['status'] = 'Absent';    
                    }
                }
            }
            return response()->json(['message' => 'Attendance data fetched successfully.', 'data' => $attendances_all, 'status' => 'success', 'members' => $members], 200);
        } else {
            $from_date = date('Y-m-d', strtotime($inputs['from_date']));
            $to_date = date('Y-m-d', strtotime($inputs['to_date']));
            $member = User::where(['isAdmin' => 0, 'id' => $inputs['memberId']])->first();
            for($cdate=$from_date; $cdate <= $to_date; $cdate = date('Y-m-d', strtotime($cdate . ' +1 day'))) {
                $attendances = Attendance::where(['member_id' => $inputs['memberId']])
                            ->where('attendance_date', '=', $cdate)
                            ->orderBy('attendance_date', 'desc')
                            ->get();
                $attendances_individual[$cdate]['date'] = $cdate;
                if(count($attendances) > 0) {
                    $attendances_individual[$cdate]['status'] = 'Present';
                    $attendances_individual[$cdate]['check_in_time'] = date('h:i A', strtotime($attendances[0]->check_in_time));
                    $attendances_individual[$cdate]['shift'] = $attendances[0]->shift;
                } else {
                    if($cdate > date('Y-m-d')) {
                        // Future date
                        $attendances_individual[$cdate]['status'] = 'NA';    
                        $attendances_individual[$cdate]['check_in_time'] = 'NA';
                        $attendances_individual[$cdate]['shift'] = 'NA';
                    } else {
                        $attendances_individual[$cdate]['status'] = 'Absent';    
                        $attendances_individual[$cdate]['check_in_time'] = 'NA';
                        $attendances_individual[$cdate]['shift'] = 'NA';
                    }
                }
            }
            return response()->json(['message' => 'Attendance data fetched successfully.', 'data' => $attendances_individual, 'status' => 'success', 'member' => $member], 200);
        }
    }

    public function downloadAttendanceReport(Request $request) {
        $inputs = $request->all();
        
        if(!isset($inputs['from_date']) || !isset($inputs['to_date'])) {
            return response()->json(['message' => 'Please select from date and to date', 'status' => 'failed']);
        }
        
        $from_date = date('Y-m-d', strtotime($inputs['from_date']));
        $to_date = date('Y-m-d', strtotime($inputs['to_date']));
        $dateRange = $from_date . ' to ' . $to_date;
        
        if($inputs['memberId'] == "" || $inputs['memberId'] == 'all') {
            // All members attendance export
            $members = User::where(['isAdmin' => 0])->orderBy('name','asc')->select('id', 'name')->get();
            $attendances_all = [];
            
            foreach ($members as $member) {
                for($cdate=$from_date; $cdate <= $to_date; $cdate = date('Y-m-d', strtotime($cdate . ' +1 day'))) {
                    $attendances = Attendance::where(['member_id' => $member->id])
                                ->where('attendance_date', '=', $cdate)
                                ->orderBy('attendance_date', 'desc')
                                ->get();
                    $attendances_all[$member->id][$cdate]['id'] = $member->id;
                    $attendances_all[$member->id][$cdate]['name'] = $member->name;
                    if(count($attendances) > 0) {
                        $attendances_all[$member->id][$cdate]['status'] = 'Present';
                    } else {
                        $attendances_all[$member->id][$cdate]['status'] = 'Absent';    
                    }
                }
            }
            
            $filename = 'attendance_report_all_members_' . $from_date . '_to_' . $to_date . '.xlsx';
            return Excel::download(new \App\Exports\ReportsExport($attendances_all, 'attendance_all', null, $members, $dateRange), $filename);
            
        } else {
            // Individual member attendance export
            $member = User::where(['isAdmin' => 0, 'id' => $inputs['memberId']])->first();
            $attendances_individual = [];
            
            for($cdate=$from_date; $cdate <= $to_date; $cdate = date('Y-m-d', strtotime($cdate . ' +1 day'))) {
                $attendances = Attendance::where(['member_id' => $inputs['memberId']])
                            ->where('attendance_date', '=', $cdate)
                            ->orderBy('attendance_date', 'desc')
                            ->get();
                $attendances_individual[$cdate]['date'] = $cdate;
                if(count($attendances) > 0) {
                    $attendances_individual[$cdate]['status'] = 'Present';
                    $attendances_individual[$cdate]['check_in_time'] = date('h:i A', strtotime($attendances[0]->check_in_time));
                    $attendances_individual[$cdate]['shift'] = $attendances[0]->shift;
                } else {
                    $attendances_individual[$cdate]['status'] = 'Absent';    
                    $attendances_individual[$cdate]['check_in_time'] = 'NA';
                    $attendances_individual[$cdate]['shift'] = 'NA';
                }
            }
            
            $filename = 'attendance_report_' . str_replace(' ', '_', $member->name) . '_' . $from_date . '_to_' . $to_date . '.xlsx';
            return Excel::download(new \App\Exports\ReportsExport($attendances_individual, 'attendance_individual', $member, null, $dateRange), $filename);
        }
    }

    public function membersWithPendingFees(Request $request) {
        $client_settings = $request->client_settings;
        $result = [];
        $pending_list = User::where(['isAdmin' => 0])
                        ->join('fee_payment_schedule', 'users.id', '=', 'fee_payment_schedule.member_id')
                        ->where('fee_payment_schedule.is_paid', 0)
                        ->where('fee_payment_schedule.due_date', '<', date('Y-m-d'))
                        ->select('users.id', 'users.name', 'users.phone_no', 'fee_payment_schedule.for_month', 'fee_payment_schedule.due_date', 'fee_payment_schedule.amount')
                        ->orderBy('users.name', 'asc')
                        ->get();
        $allMembers = User::where(['isAdmin' => 0])->select('id', 'name')->get();
        foreach ($pending_list as $key => $member) {
            if(key_exists($member->id, $result)) {
                $result[$member->id]['pending_fees'][] = [
                    'for_month' => $member->for_month
                ];
            } else {
                $result[$member->id] = [
                    'name' => $member->name,
                    'pending_fees' => [[
                        'for_month' => $member->for_month
                    ]]
                ];
            }
        }
        return response()->json(['message' => 'Members with pending fees fetched successfully.', 'data' => $result, 'status' => 'success'], 200);
    }

    public function transferMembership(Request $request) {
        $client_settings = $request->client_settings;
        $members=User::where(['isAdmin' => 0])
                    ->join('memberdetails', 'users.id', '=', 'memberdetails.user_id')
                    ->join('memberships', 'memberdetails.membership_type', '=', 'memberships.id')
                    ->select('users.id', 'users.name', 'users.phone_no', 'memberdetails.membership_type', 'memberdetails.membership_start_date', 
                             'memberships.type as membership_name', 'memberships.is_transferable as is_transferable', 
                             'memberships.payment_type as payment_type')
                    ->get();
        $transferable_memberships=Membership::where(['is_transferable' => 1, 'is_active' => 1])->get();
        return view('admin.transfer_membership', compact('client_settings', 'members', 'transferable_memberships'));
    }

    public function transferMembershipProcess(Request $request) {
        $inputs = $request->all();
        $config = Config::first();
        $membership_transfer_log = MembershipTransferLog::where(['member_id' => $inputs['member_id']])->get();
        if(count($membership_transfer_log) >= $config->membership_transfer_limit) {
            return response()->json(['message' => 'Membership transfer limit reached.', 'status' => 'failed'], 200);
        }
        $memberdetail = MemberDetail::where(['user_id' => $inputs['member_id'], 'membership_type' => $inputs['current_membership_id']])->first();
        if(!$memberdetail) {
            return response()->json(['message' => 'Member details not found.', 'status' => 'failed'], 400);
        }
        if($inputs['start_date'] >= $memberdetail->membership_end_date) {
            return response()->json(['message' => 'New membership start date cannot be after current membership end date.', 'status' => 'failed'], 400);
        }
        $old_membership_id = $inputs['current_membership_id'];
        $old_membership_start_date = $memberdetail->membership_start_date;
        $old_membership_end_date = $memberdetail->membership_end_date;

        $fee_payment_schedules = FeePaymentSchedule::where(['member_id' => $inputs['member_id'], 'membership_type' => $inputs['current_membership_id']])->get();
        $fee_payment_schedule_history = FeePaymentScheduleHistory::where(['member_id' => $inputs['member_id']])->get();
        $fee_payment_ids = [];

        foreach ($fee_payment_schedules as $schedule) {
            if($schedule->is_paid == 1 && ($schedule->fee_payment_id != null || $schedule->fee_payment_id != '')) {
                $fee_payment_ids[] = $schedule->fee_payment_id;
            }
        }
        $fee_payments = FeePayment::whereIn('id', $fee_payment_ids)->get();

        if(count($fee_payment_schedule_history) > 0) {
            foreach ($fee_payment_schedule_history as $schedule_history) {
                $schedule_history->latest = 0;
                $schedule_history->save();
            }
        }

        $membership = Membership::find($inputs['transfer_to_membership_id']);
        $startDate = Carbon::parse($inputs['start_date']);
        $membership_end_date = $startDate->addMonths($membership->duration_months);

        // Backup old member details
        TransferMembership::create([
            'member_id' => $inputs['member_id'],
            'old_membership_id' => $old_membership_id,
            'new_membership_id' => $inputs['transfer_to_membership_id'],
            'old_membership_start_date' => $old_membership_start_date,
            'old_membership_end_date' => $old_membership_end_date,
            'new_membership_start_date' => $startDate,
            'new_membership_end_date' => $membership_end_date,
            'transfer_date' => date('Y-m-d')
        ]);
        //$time_of_schedule = MembershipTimeSchedule::where('id', $request->time_schedule)->first();
        $time_of_schedule['start_time'] = null;
        $memberdetail_update['membership_type'] = $inputs['transfer_to_membership_id'];
        $memberdetail_update['membership_start_date'] = $inputs['start_date'];
        $memberdetail_update['membership_end_date'] = $membership_end_date;
        $memberdetail_update['membership_schedule_time'] = $time_of_schedule['start_time'] ?? null;
        MemberDetail::where(['user_id' => $inputs['member_id'], 'membership_type' => $inputs['current_membership_id']])->update($memberdetail_update);
        // Backup old payment schedules and payments
        foreach ($fee_payment_schedules as $schedule) {
            FeePaymentScheduleHistory::create([
                'member_id' => $schedule->member_id,
                'membership_type' => $schedule->membership_type,
                'for_month' => $schedule->for_month,
                'due_date' => $schedule->due_date,
                'amount' => $schedule->amount,
                'is_paid' => $schedule->is_paid,
                'paid_on' => $schedule->paid_on,
                'fee_payment_id' => $schedule->fee_payment_id,
                'latest' => 1
            ]);
        }
        foreach ($fee_payments as $payment) {
            FeePaymentHistory::create([
                'id_from_fee_payments' => $payment->id,
                'member_id' => $payment->member_id,
                'fee_type' => $payment->fee_type,
                'pay_for_month' => $payment->pay_for_month,
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date,
                'payment_method' => $payment->payment_method,
                'transaction_id' => $payment->transaction_id,
                'notes' => $payment->notes
            ]);
        }
        // Transfer to new membership
        $membership_info = Membership::where(['id' => $inputs['transfer_to_membership_id']])->first();
        $total_months = $membership_info->duration_months;
        $payment_type = $membership_info->payment_type;
        $admission_fee = $membership_info->admission_fee;
        $monthly_fee = $membership_info->monthly_fee;
        $one_time_fee = $membership_info->one_time_fee;
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        // Insert first record in payment schedule table
        $current_month = Carbon::parse($inputs['start_date']);
        $current_fee_day = $current_month->day;
        $current_fee_month = $current_month->month;
        $current_fee_year = $current_month->year;
        $current_month = Carbon::create($current_fee_year, $current_fee_month, $current_fee_day)->toDateString();
        $feeSchedule = new FeePaymentSchedule();
        $feeSchedule->member_id = $inputs['member_id'];
        $feeSchedule->membership_type = $membership_info->id;
        $feeSchedule->for_month = $months[$current_fee_month - 1]." ".$current_fee_year;
        $feeSchedule->due_date = $current_month;
        $feeSchedule->amount = $monthly_fee;
        $feeSchedule->is_paid = $payment_type == 'single' ? true : false;
        $feeSchedule->save();
        if($total_months > 0) {
            for($month = 1; $month <= $total_months - 1; $month++) {
                $next_month = Carbon::parse($inputs['start_date'])->addMonths($month);
                $next_fee_day = $next_month->day;
                $next_fee_month = $next_month->month;
                $next_fee_year = $next_month->year;
                $next_month = Carbon::create($next_fee_year, $next_fee_month, $next_fee_day)->toDateString();
                $feeSchedule = new FeePaymentSchedule();
                $feeSchedule->member_id = $inputs['member_id'];
                $feeSchedule->membership_type = $membership_info->id;
                $feeSchedule->for_month = $months[$next_fee_month - 1]." ".$next_fee_year;
                $feeSchedule->due_date = $next_month;
                $feeSchedule->amount = $monthly_fee;
                $feeSchedule->is_paid = $payment_type == 'single' ? true : false;
                $feeSchedule->save();
            }
        }
        if($payment_type == 'single') {
            // If one time payment, mark all as paid
            $fee_schedule = FeePaymentSchedule::where(['member_id' => $inputs['member_id'], 'membership_type' => $membership_info->id])->get();
            $payment_ids = [];
            foreach ($fee_schedule as $key => $fee) {
                $payment_ids[] = $fee->id;
            }
            // Create FeePayment record
            $feePayment = new FeePayment();
            $feePayment->member_id = $user->id;
            $feePayment->fee_type = 'one_time';
            $feePayment->pay_for_month = implode(',', $payment_ids);
            $feePayment->amount = $membership_info->one_time_fee;
            $feePayment->payment_date = date('Y-m-d');
            $feePayment->payment_method = $request->payment_method ?? 'cash';
            $feePayment->transaction_id = $request->transaction_id ?? '';
            $feePayment->notes = '';
            $feePayment->save();
            // Update the payment schedule records as paid
            foreach ($fee_schedule as $key => $fee) {
                $fee->is_paid = true;
                $fee->fee_payment_id = $feePayment->id;
                $fee->save();
            }
        }
        // Remove old fee schedule records after backup
        foreach ($fee_payment_schedules as $schedule) {
            $schedule->delete();
        }
        // Set payment status in new fee schedule as per old payment status if adjust payment option selected
        if($inputs['isAdjustPaymentChecked'] == 'true') {
            $fee_pay_schedule = FeePaymentSchedule::where(['member_id' => $inputs['member_id'], 'membership_type' => $membership_info->id])->get();
            foreach ($fee_pay_schedule as $sch) {
                $old_fee_payment_schedule = FeePaymentScheduleHistory::where([
                    'member_id' => $sch->member_id,
                    'membership_type' => $old_membership_id,
                    'for_month' => $sch->for_month,
                    'latest' => 1
                ])->first();
                if($old_fee_payment_schedule) {
                    $sch->is_paid = $old_fee_payment_schedule->is_paid;
                    $sch->paid_on = $old_fee_payment_schedule->paid_on;
                    $sch->fee_payment_id = $old_fee_payment_schedule->fee_payment_id;
                    $sch->save();
                }
            }
        }
        // Add entry in membership transfer log
        MembershipTransferLog::create([
            'member_id' => $inputs['member_id'],
            'from_membership_id' => $old_membership_id,
            'to_membership_id' => $inputs['transfer_to_membership_id'],
            'transfer_date' => date('Y-m-d')
        ]);
        return response()->json(['message' => 'Membership transferred successfully.', 'status' => 'success'], 200);
    }

    public function getAttendanceReport($member_id, Request $request) {
        $inputs = $request->all();
        $attendances_individual = [];
        $from_date = date('Y-m-d', strtotime($inputs['from_date']));
        $to_date = date('Y-m-d', strtotime($inputs['to_date']));
        $member = User::where(['isAdmin' => 0, 'id' => $member_id])->first();
        for($cdate=$from_date; $cdate <= $to_date; $cdate = date('Y-m-d', strtotime($cdate . ' +1 day'))) {
            $attendances = Attendance::where(['member_id' => $member_id])->where('attendance_date', '=', $cdate)->get();
            $attendances_individual[$cdate]['date'] = $cdate;
            if(count($attendances) > 0) {
                $attendances_individual[$cdate]['status'] = 'Present';
                $attendances_individual[$cdate]['check_in_time'] = date('h:i A', strtotime($attendances[0]->check_in_time));
                $attendances_individual[$cdate]['shift'] = ucfirst($attendances[0]->shift);
            } else {
                if($cdate > date('Y-m-d')) {
                    // Future date
                    $attendances_individual[$cdate]['status'] = 'NA';    
                    $attendances_individual[$cdate]['check_in_time'] = 'NA';
                    $attendances_individual[$cdate]['shift'] = 'NA';
                    continue;
                } else {
                    $attendances_individual[$cdate]['status'] = 'Absent';    
                    $attendances_individual[$cdate]['check_in_time'] = 'NA';
                    $attendances_individual[$cdate]['shift'] = 'NA';
                }
            }
        }
        return response()->json(['data' => $attendances_individual, 'status' => 'success'], 200);
    }

    public function todayAttendance() {
        $today = date('Y-m-d');
        $total_members = User::where(['isAdmin' => 0])->count();
        $checked_in_members = User::where(['isAdmin' => 0])
            ->join('attendance', function($join) use ($today) {
                $join->on('users.id', '=', 'attendance.member_id')
                     ->where('attendance.attendance_date', '=', $today)
                     ->where('attendance.status', '=', 'present');
            })->count();
        $data = [
            'status' => ['Checked In', 'Not Checked In'],
            'attendance' => [$checked_in_members, $total_members - $checked_in_members]
        ];
        return response()->json($data);
    }

    public function membershipRenewalAlert() {
        $upcoming_renewals = [];
        $config = Config::first();
        $next_n_days = Carbon::now()->addDays($config->membership_renewal_reminder)->toDateString();
        $members_to_renew = MemberDetail::where('membership_end_date', '<=', $next_n_days)
                            ->where('membership_end_date', '>=', date('Y-m-d'))
                            ->get();
        foreach ($members_to_renew as $member_detail) {
            $member = User::where(['id' => $member_detail->user_id, 'isAdmin' => 0])->first();
            $membership = Membership::where(['id' => $member_detail->membership_type])->first();
            if($member && $membership) {
                $upcoming_renewals[] = [
                    'member_name' => $member->name,
                    'member_id' => $member->id,
                    'membership_id' => $membership->id,
                    'membership_type' => $membership->type,
                    'membership_end_date' => $member_detail->membership_end_date
                ];
            }
        }
        return response()->json(['data' => $upcoming_renewals, 'status' => 'success'], 200);
    }

    public function memberRenewMembership(Request $request) {
        $client_settings = $request->client_settings;
        $next_n_days = Carbon::now()->addDays($client_settings->membership_renewal_reminder)->toDateString();
        $members=User::where(['isAdmin' => 0])
                    ->join('memberdetails', 'users.id', '=', 'memberdetails.user_id')
                    ->join('memberships', 'memberdetails.membership_type', '=', 'memberships.id')
                    ->select('users.id', 'users.name', 'users.phone_no', 'memberdetails.membership_type', 'memberdetails.membership_start_date', 
                             'memberdetails.membership_end_date', 'memberships.type as membership_name')
                    ->get();
        $member_details = [];
        foreach ($members as $key => $member) {
            $renew_flag = 0;
            if($member->membership_end_date < date('Y-m-d')) {
                $renew_flag = 1;
            }
            $member_details[] = [
                'member_id' => $member->id,
                'member_name' => $member->name,
                'phone_no' => $member->phone_no,
                'membership_id' => $member->membership_type,
                'membership_name' => $member->membership_name,
                'membership_start_date' => $member->membership_start_date,
                'membership_end_date' => $member->membership_end_date,
                'renew_action' => $renew_flag
            ];
        }
        return view('admin.membership_renewal', compact('client_settings', 'member_details'));
    }

    public function renewMembership(Request $request) {
        $inputs = $request->all();
        $member_id = $inputs['member_id'];
        $membership_id = $inputs['membership_id'];
        $memberdetail = MemberDetail::where(['user_id' => $member_id, 'membership_type' => $membership_id])->first();
        if(!$memberdetail) {
            return response()->json(['message' => 'Member details not found.', 'status' => 'failed'], 200);
        }
        $membership = Membership::where(['id' => $membership_id])->first();
        if(!$membership) {
            return response()->json(['message' => 'Membership not found.', 'status' => 'failed'], 200);
        }
        $startDate = date('Y-m-d', strtotime($memberdetail->membership_end_date) + 86400);
        $membership_end_date = date('Y-m-d', strtotime($startDate . ' + ' . $membership->duration_months . ' months'));
        $memberdetail_update['membership_start_date'] = $startDate;
        $memberdetail_update['membership_end_date'] = $membership_end_date;
        $fee_schedule = FeePaymentSchedule::where(['member_id' => $member_id, 'membership_type' => $membership_id])->get();
        foreach ($fee_schedule as $sch) {
            $fee_schedule_archive = new FeePaymentScheduleArchive();
            $fee_schedule_archive->member_id = $sch->member_id;
            $fee_schedule_archive->membership_type = $sch->membership_type;
            $fee_schedule_archive->for_month = $sch->for_month;
            $fee_schedule_archive->due_date = $sch->due_date;
            $fee_schedule_archive->amount = $sch->amount;
            $fee_schedule_archive->is_paid = $sch->is_paid;
            $fee_schedule_archive->paid_on = $sch->paid_on;
            $fee_schedule_archive->fee_payment_id = $sch->fee_payment_id;
            $fee_schedule_archive->save();
            $sch->delete();
        }
        // Add new fee schedule records for renewed membership
        $total_months = $membership->duration_months;
        $payment_type = $membership->payment_type;
        $monthly_fee = $membership->monthly_fee;
        $one_time_fee = $membership->one_time_fee;
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        // Insert first record in payment schedule table
        $current_month = $startDate;
        $current_fee_day = date('d', strtotime($current_month));
        $current_fee_month = date('m', strtotime($current_month));
        $current_fee_year = date('Y', strtotime($current_month));
        $current_month = Carbon::create($current_fee_year, $current_fee_month, $current_fee_day)->toDateString();
        $feeSchedule = new FeePaymentSchedule();
        $feeSchedule->member_id = $member_id;
        $feeSchedule->membership_type = $membership->id;
        $feeSchedule->for_month = $months[$current_fee_month - 1]." ".$current_fee_year;
        $feeSchedule->due_date = $current_month;
        $feeSchedule->amount = $monthly_fee;
        $feeSchedule->is_paid = $payment_type == 'single' ? true : false;
        $feeSchedule->save();
        if($total_months > 0) {
            for($month = 1; $month <= $total_months - 1; $month++) {
                $next_month = date('Y-m-d', strtotime($startDate . ' + ' . $month . ' months'));
                $next_fee_day = date('d', strtotime($next_month));
                $next_fee_month = date('m', strtotime($next_month));
                $next_fee_year = date('Y', strtotime($next_month));
                $feeSchedule = new FeePaymentSchedule();
                $feeSchedule->member_id = $member_id;
                $feeSchedule->membership_type = $membership->id;
                $feeSchedule->for_month = $months[$next_fee_month - 1]." ".$next_fee_year;
                $feeSchedule->due_date = $next_month;
                $feeSchedule->amount = $monthly_fee;
                $feeSchedule->is_paid = $payment_type == 'single' ? true : false;
                $feeSchedule->save();
            }
        }
        MemberDetail::where(['user_id' => $member_id, 'membership_type' => $membership_id])->update($memberdetail_update);
        RenewalHistory::create([
            'member_id' => $member_id,
            'old_membership_id' => $membership_id,
            'new_membership_id' => $membership->id,
            'renewal_date' => now()
        ]);
        return response()->json(['message' => 'Membership renewed successfully.', 'status' => 'success'], 200); 
    }

    public function getSalary(Request $request) {
        $client_settings = $request->client_settings;
        $salary = StaffSalary::all();
        return view('admin.staff_salary', compact('salary', 'client_settings'));
    }

    public function addSalary(Request $request) {
        $inputs = $request->all();
        $salary = new StaffSalary();
        $salary->staff_name = $inputs['staff_name'];
        $salary->staff_type = $inputs['staff_type'];
        $salary->amount = $inputs['salary'];
        $salary->save();
        if($salary->id) {
            return response()->json(['message' => 'Staff salary added successfully.', 'status' => 'success']);
        } else {
            return response()->json(['message' => 'Failed to add staff salary.', 'status' => 'failed']);
        }
    }

    public function editSalary(Request $request) {
        $inputs = $request->all();
        $salary = StaffSalary::find($inputs['salary_id']);
        if(!$salary) {
            return response()->json(['message' => 'Staff salary record not found.', 'status' => 'failed']);
        }
        $salary->staff_name = $inputs['staff_name'];
        //$salary->staff_type = $inputs['staff_type'];
        $salary->amount = $inputs['salary'];
        $salary->save();
        return response()->json(['message' => 'Staff salary updated successfully.', 'status' => 'success']);
    }

    public function payoutStatistics(Request $request) {
        $inputs = $request->all();
        $current_month = date('m', strtotime($inputs['month']));
        $current_year = date('Y', strtotime($inputs['month']));
        $start_date = Carbon::create($current_year, $current_month, 1)->toDateString();
        $end_date = Carbon::create($current_year, $current_month, Carbon::create($current_year, $current_month, 1)->daysInMonth)->toDateString();
        $total_payouts = StaffSalary::sum('amount');
        $total_amount = FeePaymentSchedule::where('for_month', $inputs['month'])->sum('amount');
        $paid_amount = FeePaymentSchedule::where(['for_month' => $inputs['month'], 'is_paid' => 1])->sum('amount');
        $single_time_payments = FeePayment::where('fee_type', 'one_time')
                                    ->whereBetween('payment_date', [$start_date, $end_date])
                                    ->sum('amount');
        $paid_amount += $single_time_payments;
        $total_amount += $single_time_payments;
        $data = [
            'matrics' => ['Expected', 'Received', 'Payout'],
            'amounts' => [$total_amount, $paid_amount, $total_payouts]
        ];
        return response()->json(['data' => $data, 'status' => 'success'], 200);
    }
    
    public function getCalendarData(Request $request) {
        $inputs = $request->all();
        $current_month = $inputs['month'];
        $current_year = $inputs['year'];
        //dd($current_month, $current_year);
        $current_start_date = Carbon::create($current_year, $current_month, 1)->toDateString();
        $current_end_date = Carbon::create($current_year, $current_month, Carbon::create($current_year, $current_month, 1)->daysInMonth)->toDateString();
        //dd($current_start_date, $current_end_date);

        $day_events = [];
        $time_schedules = MembershipTimeSchedule::join('memberships', 'membership_time_schedules.membership_id', '=', 'memberships.id')
                            ->select('membership_time_schedules.start_time', 'memberships.type as membership_type')
                            ->get();
        foreach ($time_schedules as $key => $value) {
            $day_events[$key]['membership'] = $value->membership_type;
            $day_events[$key]['start_time'] = date('h:i A', strtotime($value->start_time));
        }
        $n_p_days = [];
        for($i=$current_start_date; $i<=$current_end_date; $i = date('Y-m-d', strtotime($i . ' +1 day'))) {
            \Log::info('Processing date: ' . $i);
            $n_p_days[] = [
                'date' => Carbon::parse($i),
                'is_current_month' => '',
                'events' => $day_events,
                'is_today' => ($i == date('Y-m-d')) ? true : false,
                'day' => date('D', strtotime($i)),
            ];
        }
        \Log::info('Days array: ' . print_r($n_p_days, true));
        return response()->json(['data' => $n_p_days, 'status' => 'success'], 200);
    }
}

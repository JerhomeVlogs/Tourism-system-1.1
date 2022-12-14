<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book_data;
use App\Models\Book_request;
use App\Models\Approve;
use App\Models\Weekly_count;
use App\Models\Map_location;
use App\Models\staff_notification;
use App\Models\User_notification;
use App\Models\Admin_notification;
use App\Models\Group_approve;
use App\Models\Reset_analytic;
use App\Models\staff_alert;
use App\Models\Daily_reset;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // udr if you deleting on public 
use Illuminate\Support\Facades\Storage; // use this if you make delete on storage

class UserController extends Controller
{

    function profile ()
    {
        $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];

        return view('user.profile', $data);
        
    }

    function profile_update (Request $req)
    {

        $profile_update = User::where('id','=', session('LoggedUser'))->first();

        if ($profile_update->email != $req->email) {

            $req->validate([
                'first_name' => 'required | min: 2',
                'last_name' => 'required | min: 2',
                'phone' => 'required | min: 11',
                'email' => 'required | email | unique:users',
                'profile' => 'image|mimes:jpg,png',
                'gender' => 'required'
            ]);

            // profile info and file storation

            if ($req->file('profile') == null )
            {
                
                $profile_update->first_name = $req->first_name;
                $profile_update->last_name = $req->last_name;
                $profile_update->email = $req->email;
                $profile_update->phone = $req->phone;
                $profile_update->address = $req->address;
                $profile_update->gender = $req->gender;
                $profile_update->save();

                return redirect()->route('profileUser.view')->with('success','Update Successfully');
            }
            else
            {
                $size = $req->file('profile')->getSize();
                $name = $req->file('profile')->getClientOriginalName();
                $req->file('profile')->storeAs('public/img/', $name);
                
                $profile_update->first_name = $req->first_name;
                $profile_update->last_name = $req->last_name;
                $profile_update->email = $req->email;
                $profile_update->phone = $req->phone;
                $profile_update->address = $req->address;
                $profile_update->img_name = $name;
                $profile_update->img_size = $size;
                $profile_update->gender = $req->gender;
                $profile_update->save();

                return redirect()->route('profileUser.view')->with('success','Update Successfully');
            }
 
        }
        else 
        {
            $req->validate([
                'first_name' => 'required | min: 2',
                'last_name' => 'required | min: 2',
                'phone' => 'required | min: 11',
                'profile' => 'image|mimes:jpg,png',
            ]);


            // profile info and file storation

            if ($req->file('profile') != null)
            {
                $size = $req->file('profile')->getSize();
                $name = $req->file('profile')->getClientOriginalName();
                $req->file('profile')->storeAs('public/img/', $name);

                if ($profile_update->img_name != 'default-profile.png')
                {
                    // delete profile
                    if (Storage::exists('public/img/'.$profile_update->img_name))
                    {
                        Storage::delete('public/img/'.$profile_update->img_name);
                        $profile_update->first_name = $req->first_name;
                        $profile_update->last_name = $req->last_name;
                        $profile_update->email = $req->email;
                        $profile_update->phone = $req->phone;
                        $profile_update->address = $req->address;
                        $profile_update->img_name = $name;
                        $profile_update->img_size = $size;
                        $profile_update->gender = $req->gender;
                        $profile_update->save();

                        return redirect()->route('profileUser.view')->with('success','Update Successfully');
                    }
                    else
                    {
                        return back();
                    }
                }
                else
                {
                    $profile_update->first_name = $req->first_name;
                    $profile_update->last_name = $req->last_name;
                    $profile_update->email = $req->email;
                    $profile_update->phone = $req->phone;
                    $profile_update->address = $req->address;
                    $profile_update->img_name = $name;
                    $profile_update->img_size = $size;
                    $profile_update->gender = $req->gender;
                    $profile_update->save();

                    return redirect()->route('profileUser.view')->with('success','Update Successfully');
                }
            }
            else
            {
                $profile_update->first_name = $req->first_name;
                $profile_update->last_name = $req->last_name;
                $profile_update->email = $req->email;
                $profile_update->phone = $req->phone;
                $profile_update->address = $req->address;
                $profile_update->gender = $req->gender;
                $profile_update->save();

                return redirect()->route('profileUser.view')->with('success','Update Successfully');
            }
        }
    }

    function delete_profile ()
    {
        $profile_delete = User::where('id','=', session('LoggedUser'))->first();
        
        if ($profile_delete->img_name != 'default-profile.png')
        {
            // delete profile
            if (Storage::exists('public/img/'.$profile_delete->img_name))
            {
                Storage::delete('public/img/'.$profile_delete->img_name);
                $profile_delete->img_name = 'default-profile.png';
                $profile_delete->save();

                return redirect()->route('profileUser.view')->with('success','Profile Updated');
            }
            else
            {
                return back();
            }
        }
        else
        {
            return back();
        }
    }


    function dashboard ()
    {
        date_default_timezone_set('Asia/Manila');
        $end = "18";
        $now = date('H');
        $day = date('l');
        $date  = date('F j, Y');

        $dd = Daily_reset::where('user_id', session('LoggedUser'))->count();

        if ($dd == 0)
        {
            $reset = new Daily_reset;
            $reset->user_id = session('LoggedUser');
            $reset->today = date("Y-m-d", strtotime("today"));
            $reset->tomorrow = date("Y-m-d", strtotime("tomorrow"));
            $reset->save();

            $check_date = Daily_reset::where('user_id',session('LoggedUser'))->first();

            if (($check_date->today == date("Y-m-d", strtotime("today"))) && ($check_date->tomorrow == date("Y-m-d", strtotime("tomorrow"))))
            {
                $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];

                $data['location'] = Map_location::where('type','=',"1")->get(['name','visit_count']);
                $data['count'] = Map_location::where('type','=',"1")->get(['name','visit_count'])->count();;

                // analytics resets
                $dateTime = new DateTime('now');
                $monday = clone $dateTime->modify(('Sunday' == $dateTime->format('l')) ? 'Monday last week' : 'Monday this week');
                $sunday = clone $dateTime->modify('Sunday this week');
                $now = date('Y-m-d');
                $start = $monday->format('Y-m-d');
                $end = $sunday->format('Y-m-d');
                // for modification date
                // $end = strtotime("2022-11-01");

                $check_date = Reset_analytic::where('staff', session('LoggedUser'))->first();

                if ($check_date != null)
                {
                    if (strtotime($check_date->start) == strtotime($start))
                    {
                        if (strtotime($now) > strtotime($check_date->end))
                        {
                            //reset analytics
                            DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                            return view('user.dashboard', $data);
                        }
                        else
                        {
                            return view('user.dashboard', $data);
                        }
                    }
                    else 
                    {
                        //update start end date
                        $up_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                        $up_date->start = $start;
                        $up_date->end = $end;
                        $up_date->save();

                        //reset analytics
                        DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                        return view('user.dashboard', $data);
                    }
                }
                else
                {
                    $td_start_end = new Reset_analytic;
                    $td_start_end->staff = session('LoggedUser');
                    $td_start_end->start = $start;
                    $td_start_end->end = $end;
                    $td_start_end->save();

                    $check_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                
                    if (strtotime($check_date->start) == $start)
                    {
                        if (strtotime($now) > strtotime($check_date->end))
                        {
                            //reset analytics
                            DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                            return view('user.dashboard', $data);
                        }
                        else
                        {
                            return view('user.dashboard', $data);
                        }
                    }
                    else 
                    {
                        //update start end date
                        $up_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                        $up_date->start = $start;
                        $up_date->end = $end;
                        $up_date->save();

                        //reset analytics
                        DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                        return view('user.dashboard', $data);
                    }
        }

            }
            else 
            {
                $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];
                // resetting count
                DB::table('map_locations')->update(['visit_count'=>'0','total_visit'=>'0']);
                DB::table('book_requests')->delete();
    
                $reset = Daily_reset::where('user_id',session('LoggedUser'))->first();
                $reset->today = date("Y-m-d", strtotime("today"));
                $reset->tomorrow = date("Y-m-d", strtotime("tomorrow"));
                $reset->save();

                $data['location'] = Map_location::where('type','=',"1")->get(['name','visit_count']);
                $data['count'] = Map_location::where('type','=',"1")->get(['name','visit_count'])->count();;

                return $data['location'];
                // analytics resets
                $dateTime = new DateTime('now');
                $monday = clone $dateTime->modify(('Sunday' == $dateTime->format('l')) ? 'Monday last week' : 'Monday this week');
                $sunday = clone $dateTime->modify('Sunday this week');
                $now = date('Y-m-d');
                $start = $monday->format('Y-m-d');
                $end = $sunday->format('Y-m-d');
                // for modification date
                // $end = strtotime("2022-11-01");

                $check_date = Reset_analytic::where('staff', session('LoggedUser'))->first();

                if ($check_date != null)
                {
                    if (strtotime($check_date->start) == strtotime($start))
                    {
                        if (strtotime($now) > strtotime($check_date->end))
                        {
                            //reset analytics
                            DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                            return view('user.dashboard', $data);
                        }
                        else
                        {
                            return view('user.dashboard', $data);
                        }
                    }
                    else 
                    {
                        //update start end date
                        $up_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                        $up_date->start = $start;
                        $up_date->end = $end;
                        $up_date->save();

                        //reset analytics
                        DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                        return view('user.dashboard', $data);
                    }
                }
                else
                {
                    $td_start_end = new Reset_analytic;
                    $td_start_end->staff = session('LoggedUser');
                    $td_start_end->start = $start;
                    $td_start_end->end = $end;
                    $td_start_end->save();

                    $check_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                
                    if (strtotime($check_date->start) == $start)
                    {
                        if (strtotime($now) > strtotime($check_date->end))
                        {
                            //reset analytics
                            DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                            return view('user.dashboard', $data);
                        }
                        else
                        {
                            return view('user.dashboard', $data);
                        }
                    }
                    else 
                    {
                        //update start end date
                        $up_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                        $up_date->start = $start;
                        $up_date->end = $end;
                        $up_date->save();

                        //reset analytics
                        DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                        return view('user.dashboard', $data);
                    }
                }
            }
        }
        else 
        {
            
            $check_date = Daily_reset::where('user_id',session('LoggedUser'))->first();

            if (($check_date->today == date("Y-m-d", strtotime("today"))) && ($check_date->tomorrow == date("Y-m-d", strtotime("tomorrow"))))
            {
                $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];

                $data['location'] = Map_location::where('type','=',"1")->get(['name','visit_count']);
                $data['count'] = Map_location::where('type','=',"1")->get(['name','visit_count'])->count();;

                // analytics resets
                $dateTime = new DateTime('now');
                $monday = clone $dateTime->modify(('Sunday' == $dateTime->format('l')) ? 'Monday last week' : 'Monday this week');
                $sunday = clone $dateTime->modify('Sunday this week');
                $now = date('Y-m-d');
                $start = $monday->format('Y-m-d');
                $end = $sunday->format('Y-m-d');
                // for modification date
                // $end = strtotime("2022-11-01");

                $check_date = Reset_analytic::where('staff', session('LoggedUser'))->first();

                if ($check_date != null)
                {
                    if (strtotime($check_date->start) == strtotime($start))
                    {
                        if (strtotime($now) > strtotime($check_date->end))
                        {
                            //reset analytics
                            DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                            return view('user.dashboard', $data);
                        }
                        else
                        {
                            return view('user.dashboard', $data);
                        }
                    }
                    else 
                    {
                        //update start end date
                        $up_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                        $up_date->start = $start;
                        $up_date->end = $end;
                        $up_date->save();

                        //reset analytics
                        DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                        return view('user.dashboard', $data);
                    }
                }
                else
                {
                    $td_start_end = new Reset_analytic;
                    $td_start_end->staff = session('LoggedUser');
                    $td_start_end->start = $start;
                    $td_start_end->end = $end;
                    $td_start_end->save();

                    $check_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                
                    if (strtotime($check_date->start) == $start)
                    {
                        if (strtotime($now) > strtotime($check_date->end))
                        {
                            //reset analytics
                            DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                            return view('user.dashboard', $data);
                        }
                        else
                        {
                            return view('user.dashboard', $data);
                        }
                    }
                    else 
                    {
                        //update start end date
                        $up_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                        $up_date->start = $start;
                        $up_date->end = $end;
                        $up_date->save();

                        //reset analytics
                        DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                        return view('user.dashboard', $data);
                    }
        }

            }
            else 
            {
                $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];
                // resetting count
                DB::table('map_locations')->update(['visit_count'=>'0','total_visit'=>'0']);
                DB::table('book_requests')->delete();
    
                $reset = Daily_reset::where('user_id',session('LoggedUser'))->first();
                $reset->today = date("Y-m-d", strtotime("today"));
                $reset->tomorrow = date("Y-m-d", strtotime("tomorrow"));
                $reset->save();

                $data['location'] = Map_location::where('type','=',"1")->get(['name','visit_count']);
                $data['count'] = Map_location::where('type','=',"1")->get(['name','visit_count'])->count();

                // analytics resets
                $dateTime = new DateTime('now');
                $monday = clone $dateTime->modify(('Sunday' == $dateTime->format('l')) ? 'Monday last week' : 'Monday this week');
                $sunday = clone $dateTime->modify('Sunday this week');
                $now = date('Y-m-d');
                $start = $monday->format('Y-m-d');
                $end = $sunday->format('Y-m-d');
                // for modification date
                // $end = strtotime("2022-11-01");

                $check_date = Reset_analytic::where('staff', session('LoggedUser'))->first();

                if ($check_date != null)
                {
                    if (strtotime($check_date->start) == strtotime($start))
                    {
                        if (strtotime($now) > strtotime($check_date->end))
                        {
                            //reset analytics
                            DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                            return view('user.dashboard', $data);
                        }
                        else
                        {
                            return view('user.dashboard', $data);
                        }
                    }
                    else 
                    {
                        //update start end date
                        $up_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                        $up_date->start = $start;
                        $up_date->end = $end;
                        $up_date->save();

                        //reset analytics
                        DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                        return view('user.dashboard', $data);
                    }
                }
                else
                {
                    $td_start_end = new Reset_analytic;
                    $td_start_end->staff = session('LoggedUser');
                    $td_start_end->start = $start;
                    $td_start_end->end = $end;
                    $td_start_end->save();

                    $check_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                
                    if (strtotime($check_date->start) == $start)
                    {
                        if (strtotime($now) > strtotime($check_date->end))
                        {
                            //reset analytics
                            DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                            return view('user.dashboard', $data);
                        }
                        else
                        {
                            return view('user.dashboard', $data);
                        }
                    }
                    else 
                    {
                        //update start end date
                        $up_date = Reset_analytic::where('staff', session('LoggedUser'))->first();
                        $up_date->start = $start;
                        $up_date->end = $end;
                        $up_date->save();

                        //reset analytics
                        DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                            
                        return view('user.dashboard', $data);
                    }
                }

            }
        }
    }

    function dashboard_fetch ()
    {
    
        $data = Map_location::where('type','=',"1")->get(['name','visit_count','total_visit']);
        $count = Map_location::where('type','=',"1")->get(['name','visit_count','total_visit'])->count();

        return response()->json([
            'data' => $data,
            'count' => $count,
        ]);

    }

    function notifications ()
    {
        $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];

        return view('user.alert', $data);
    }

    function user_notif_view(Request $req)
    {
        DB::table('user_notifications')->where('id', $req->id)->update(['status' => 'seen']);
        $data = ['message'=> User::where('id','=', $req->id)->first()];

        return back()->with('click',$req->id);
    }

    function user_notif_log ()
    {
        $acc = User::where('id','=', session('LoggedUser'))->first();
        //getting data by new insert
        $data = User_notification::orderBy('created_at','desc')->get();

        return response()->json([
            'notification'=>$data,
        ]);
    }

    function get_notif ()
    {
        $get_notif = User_notification::where('status','=', 'unread')->paginate(3);
        $all = User_notification::where('status','=', 'unread')->get();

        return response()->json([
            'get_notif' => $get_notif,
            'unread' => $all,
        ]);
    }

    function user_delete_notif (Request $req)
    {
        DB::table('user_notifications')->where('id',$req->id)->delete();
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();

        
        return view('user.alert', $data);
    }

    function view_notif (Request $req)
    {
        DB::table('user_notifications')->where('id', $req->id)->update(['status' => 'seen']);
        $data = ['message'=> User::where('id','=', $req->id)->first()];

        return back()->with('click',$req->id);
    }

    function view_data (Request $req)
    {
        $data = User_notification::where('id','=', $req->input('view'))->first();

        return response()->json([
            'view_data' => $data,
        ]);
    }

    function map ()
    {
 
        $falls = Approve::where('destination','=', 'falls')->where('day','=', date('l'))->get();
        $fallsGroup = Book_data::where('destination','=', 'falls')->where('day','=', date('l'))->get();
        $tundol = Approve::where('destination','=', 'tundol')->where('day','=', date('l'))->get();
        $tundolGroup = Book_data::where('destination','=', 'tundol')->where('day','=', date('l'))->get();

        $totalfalls = $falls->count() + $fallsGroup->count();
        $totaltundol = $tundol->count(); + $tundolGroup->count();

        $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];
        $data['location'] = Map_location::get(['name','visit_count','type']);
        $data['count'] = Map_location::get(['name','visit_count'])->count();;
        $data['falls_count'] = $totalfalls;
        $data['tundol_count'] = $totaltundol;

        return view('user.map', $data);
    }

    function map_locations ()
    {
        $locations = Map_location::get();

        return response()->json([
            'locations' => $locations,
        ]);
    }

    function fetch_visit ()
    {
       
        $falls = Approve::where('destination','=', 'falls')->get();
        $fallsGroup = Book_data::where('destination','=', 'falls')->get();
        $tundol = Approve::where('destination','=', 'tundol')->get();
        $tundolGroup = Book_data::where('destination','=', 'tundol')->get();

        return response()->json([
            'falls' => $falls->count() + $fallsGroup->count(),
            'tundol' =>$tundol->count() + $tundolGroup->count(),
            'patar' =>$tundol->count() + $tundolGroup->count(),
        ]);
    }

    function book ()
    {
        // get data and goto book view
        $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];

        return view('user.booking', $data);
    }

    function book2 (Request $req)
    {
        // get data and goto book2 view
        $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];

        if (($req->gender == null) || ($req->first_name == null) || ($req->last_name == null) || ($req->phone == null) || ($req->email == null) || ($req->address == null))
        {
            return back()->with('fails','You need to complete your profile info.');
        } 
        else 
        {
            $book_exist = Book_request::where('user_id','=', session('LoggedUser'))->first();

            if ($book_exist != null)
            {
                if ($book_exist->status == "pending")
                {
                    return back()->with('fails','Already have a request!');
                }
                else if ($book_exist->status == "approve")
                {
                    return back()->with('fails','You are on location!');
                }
                else 
                {
                    $falls = Approve::where('destination','=', 'falls')->get();
                    $fallsGroup = Book_data::where('destination','=', 'falls')->get();
                    $tundol = Approve::where('destination','=', 'tundol')->get();
                    $tundolGroup = Book_data::where('destination','=', 'tundol')->get();
            
                    $totalfalls = $falls->count() + $fallsGroup->count();
                    $totaltundol = $tundol->count(); + $tundolGroup->count();
            
                    $data['falls_count'] = $totalfalls;
                    $data['tundol_count'] = $totaltundol;

                    return view('user.booking2', $data);
                }

            }
            else
            {
                $falls = Approve::where('destination','=', 'falls')->get();
                $fallsGroup = Book_data::where('destination','=', 'falls')->get();
                $tundol = Approve::where('destination','=', 'tundol')->get();
                $tundolGroup = Book_data::where('destination','=', 'tundol')->get();
        
                $totalfalls = $falls->count() + $fallsGroup->count();
                $totaltundol = $tundol->count(); + $tundolGroup->count();
        
                $data['falls_count'] = $totalfalls;
                $data['tundol_count'] = $totaltundol;

                return view('user.booking2', $data);
            }
            
        }
    }

    function book2_count ()
    {
        $location = Map_location::where('type','=',"1")->get();

        return response()->json([
            'locations'=> $location,
           
        ]);
    }

    // insert booker data
    function book_data (Request $req)
    {
                  
        date_default_timezone_set('Asia/Manila');
        $time_date  = date('F j, Y g:i:a  ');

        if ($req->group_book != null)
        {
            $book_number = random_int(100000, 999999);

            $req->session()->put('book_number', $book_number);

            $data = User::where('id','=', session('LoggedUser'))->first();
            $insert_request = new Book_request;
            $insert_request->user_id = $data->id;
            $insert_request->first_name = $data->first_name;
            $insert_request->last_name = $data->last_name;
            $insert_request->phone = $data->phone;
            $insert_request->email = $data->email;
            $insert_request->gender = $data->gender;
            $insert_request->address = $data->address;
            $insert_request->destination = $req->destination;
            $insert_request->time_date = $time_date;
            $insert_request->book_number = $book_number;
            $insert_request->status = "pending";
            $insert_request->save();

            $book_id = Book_request::where('book_number','=', $book_number)->first();


            if ($req->phone == null)
                {
                $booker_id = $book_id->id;
                $first_name = $req->first_name;
                $last_name = $req->last_name;
                $gender = $req->gender;
                $destination = $req->destination;
                $phone = $req->contact;
                $address = $req->address;

                for ($i=0; $i < count($first_name); $i++)
                {
                    $datasave = [
                        'booker_id'   =>$booker_id,
                        'first_name' =>$first_name[$i],
                        'last_name' =>$last_name[$i],
                        'gender' =>$gender[$i],
                        'destination' =>$destination,
                        'address' =>$address[$i],
                        'book_number'=>$book_number,
                        'time_date' =>$time_date,
                    ];

                    DB::table('book_datas')->insert($datasave);
                    DB::table('group_approves')->insert($datasave);
            
                }

                $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];
                $group = DB::table('book_datas')->where('book_number', '=', $book_number)->get();
                $groupCount = $group->count();
                $data['book_number'] = $book_number;
            
                DB::table('book_requests')->where('book_number', $book_number)->update(['groups' => $groupCount]);

                return view('user.book_result', $data)->with('success','Book Successfully.');
            }
            else 
            {
                $booker_id = $book_id->id;
                $first_name = $req->first_name;
                $last_name = $req->last_name;
                $gender = $req->gender;
                $destination = $req->destination;
                $phone = $req->contact;
                $address = $req->address;

                for ($i=0; $i < count($first_name); $i++)
                {
                    $datasave = [
                        'booker_id'   =>$booker_id,
                        'first_name' =>$first_name[$i],
                        'last_name' =>$last_name[$i],
                        'gender' =>$gender[$i],
                        'destination' =>$destination,
                        'contact' =>$phone[$i],
                        'address' =>$address[$i],
                        'book_number'=>$book_number,
                        'time_date' =>$time_date,
                    ];

                    DB::table('book_datas')->insert($datasave);
                    DB::table('group_approves')->insert($datasave);
            
                }

                $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];
                $group = DB::table('book_datas')->where('book_number', '=', $book_number)->get();
                $groupCount = $group->count();
            
                DB::table('book_requests')->where('book_number', $book_number)->update(['groups' => $groupCount]);

                return view('user.book_result', $data,['book'=>$book_number])->with('success','Book Successfully.');
            }

            
        }
        else
        {
            $book_number = random_int(100000, 999999);

            $req->session()->put('book_number', $book_number);

            $data = User::where('id','=', session('LoggedUser'))->first();
            $insert_request = new Book_request;
            $insert_request->user_id = $data->id;
            $insert_request->first_name = $data->first_name;
            $insert_request->last_name = $data->last_name;
            $insert_request->phone = $data->phone;
            $insert_request->email = $data->email;
            $insert_request->gender = $data->gender;
            $insert_request->address = $data->address;
            $insert_request->destination = $req->destination;
            $insert_request->time_date = $time_date;
            $insert_request->book_number = $book_number;
            $insert_request->status = "pending";
            $insert_request->groups = 'solo';
            $insert_request->save();

            $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];
            return view('user.book_result', $data,['book_number'=>$book_number])->with('success','Book Successfully.');
        }

    }

    function book_log ()
    {
        $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];
        $data['list'] = Book_request::where('user_id','=', session('LoggedUser'))->first();
        $data['locations'] = Map_location::where('type','1')->get();

        return view('user.book_log', $data);
        
    }

    // log
    function log_view (Request $req)
    {
        //getting all data with booker
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();
        $data['groups'] = DB::table('book_datas')->where('book_number', '=', $req->id)->get();

        if ($data['groups']->isNotEmpty())
        {
            return view('user.groups-view', $data);
        }
        else
        {
            return back();
        }

    }

    function log_delete (Request $req)
    {
        $delete = DB::table('book_datas')->where('booker_id',$req->id)->delete();
        $delete = DB::table('book_requests')->where('id',$req->id)->delete();

        return back();
    }

    function leave_location (Request $req)
    {

        $data = User::where('id','=', session('LoggedUser'))->first();

        $leave_count = DB::table('book_requests')->where('book_number',$req->id)->get();
        $leave_group = DB::table('book_datas')->where('book_number',$req->id)->get();

        $req_details = Book_request::where('book_number',$req->id)->first();
        $total = $leave_group->count() + $leave_count->count();

        $data2 = Map_location::get(['name']);
        $count = $data2->count();


        for($i = 0; $i < $count; $i++){
            if (strtolower(strtolower($req_details->destination)) == strtolower($data2[$i]->name))
            {
               
                $map_count = Map_location::where('name','=', strtolower($data2[$i]->name))->first();
                $count =(int)$map_count->visit_count - $total ;
                $map_count->visit_count = $count ;
                $map_count->save();

                break;
            }
        }

        $delete = DB::table('book_requests')->where('book_number',$req->id)->delete();
        $delete = DB::table('book_datas')->where('book_number',$req->id)->delete();
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();
        $data['list'] = Book_request::where('user_id','=', session('LoggedUser'))->first();

        return view('user.book_log', $data);
    }

    function records ()
    {
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();
        $data['lists'] = Approve::where('user_id', '=', session('LoggedUser'))->paginate(10);

        return view('user.history', $data);
    }

    function records_group_view (Request $req)
    {
        //getting all data with booker
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();
        $data['groups'] = Group_approve::where('book_number', '=', $req->id)->paginate(10);

        if ($data['groups']->isNotEmpty())
        {
            return view('user.log-view', $data);
        }
        else
        {
            return back();
        }

    }

    // req status update loc
    function location_update (Request $req)
    {

        $details = Book_request::where('user_id','=', session('LoggedUser'))->first();
        $book_number = $details->book_number;
        if ($details->destination != $req->destination)
        {
            
            $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();
    
            // counting and change the count to other location
            $count1 = Book_request::where('book_number',$book_number)->count();
            $count2 = Book_data::where('book_number',$book_number)->count();

            //user info
            $req_details = Book_request::where('book_number',$book_number)->first();
            $total = $count1 + $count2;

            $data2 = Map_location::get(['name']);
            $count = $data2->count();


            // match data and update live count
            for($i = 0; $i < $count; $i++){
                if (strtolower(strtolower($req_details->destination)) == strtolower($data2[$i]->name))
                {
        
                    $map_count = Map_location::where('name','=', strtolower($data2[$i]->name))->first();
                    $count =(int)$map_count->visit_count - $total ;
                    $map_count->visit_count = $count ;
                    $map_count->save();

                    break;
                }
            }

             // update new location
             $result = DB::table('book_requests')->where('book_number','=', $book_number)->update(['destination'=>$req->destination]);
             $result = DB::table('book_datas')->where('book_number','=', $book_number)->update(['destination'=>$req->destination]);

            // change location
            $count1 = Book_request::where('book_number',$book_number)->count();
            $count2 = Book_data::where('book_number',$book_number)->count();

            //user info
            $req_details = Book_request::where('book_number',$book_number)->first();
            $total = $count1 + $count2;

            $data2 = Map_location::get(['name']);
            $count = $data2->count();


            // match data and update live count
            for($i = 0; $i < $count; $i++){
                if (strtolower(strtolower($req_details->destination)) == strtolower($data2[$i]->name))
                {
        
                    $map_count = Map_location::where('name','=', strtolower($data2[$i]->name))->first();
                    $count =(int)$map_count->visit_count + $total ;
                    $map_count->visit_count = $count ;
                    $map_count->save();

                    break;
                }
            }


            //get all data
            $data['list'] = Book_request::where('user_id','=', session('LoggedUser'))->first();
            $data['locations'] = Map_location::where('type','1')->get();
    
            return view('user.book_log', $data);
        }
        else
        {
          
            $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();
    
            //get all data
            $data['list'] = Book_request::where('user_id','=', session('LoggedUser'))->first();
            $data['locations'] = Map_location::where('type','1')->get();
    
            return view('staff.system_entry', $data);
        }
    }


}

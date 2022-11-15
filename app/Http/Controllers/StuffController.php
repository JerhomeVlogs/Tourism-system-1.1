<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book_data;
use App\Models\Book_request;
use App\Models\Approve;
use App\Models\Weekly_count;
use App\Models\Map_location;
use App\Models\Stuff_notification;
use App\Models\User_notification;
use App\Models\Admin_notification;
use App\Models\Group_approve;
use App\Models\Reset_analytic;
use App\Models\Stuff_alert;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // udr if you deleting on public 
use Illuminate\Support\Facades\Storage; // use this if you make delete on storage

class StuffController extends Controller
{
    function get_notif ()
    {
        $get_notif = Stuff_alert::where('status','=', 'unread')->paginate(3);
        $all = Stuff_alert::where('status','=', 'unread')->get();

        return response()->json([
            'get_notif' => $get_notif,
            'unread' => $all,
        ]);
    }

    function view_notif (Request $req)
    {
        DB::table('stuff_alerts')->where('id', $req->id)->update(['status' => 'seen']);
        $data = ['message'=> User::where('id','=', $req->id)->first()];

        return back()->with('stuff_click',$req->id);
    }

    function stuff_notif_view(Request $req)
    {
        DB::table('stuff_alerts')->where('id', $req->id)->update(['status' => 'seen']);
        $data = ['message'=> User::where('id','=', $req->id)->first()];

        return back()->with('stuff_click',$req->id);
    }

    function stuff_notif_log ()
    {
        $acc = User::where('id','=', session('LoggedUser'))->first();
        //getting data by new insert
        $data = Stuff_alert::orderBy('created_at','desc')->get();

        return response()->json([
            'notification'=>$data,
        ]);
    }

    function stuff_delete_notif (Request $req)
    {
        DB::table('stuff_alerts')->where('id',$req->id)->delete();
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();

        
        return view('stuff.notif', $data);
    }

    function notifications ()
    {
        $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];

        return view('stuff.notif', $data);
    }

    function view_data (Request $req)
    {
        $data = Stuff_alert::where('id','=', $req->input('view'))->first();

        return response()->json([
            'stuff_view_data' => $data,
        ]);
    }



    function profile ()
    {
        $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];

        return view('stuff.profile', $data);
        
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
            ]);

            // profile info and file storation
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
            $profile_update->save();

            return redirect()->route('profileStuff.view')->with('success','Update Successfully');
 
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
                    $profile_update->save();

                    return redirect()->route('profileStuff.view')->with('success','Update Successfully');
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
                $profile_update->save();

                return redirect()->route('profileStuff.view')->with('success','Update Successfully');
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

                return redirect()->route('profileStuff.view')->with('success','Profile Updated');
            }
            else
            {
                $profile_delete->img_name = 'default-profile.png';
                $profile_delete->save();

                return redirect()->route('profileStuff.view')->with('success','Profile Updated');
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

        $data = ['user_data'=>User::where('id','=', session('LoggedUser'))->first()];
        $location = Map_location::get(['name','visit_count','date']);
        $count = Map_location::get(['name','visit_count'])->count();

    
        // resetting count 
        for ($i = 0; $i < $count; $i++)
        {
            if ((strtolower($date) != strtolower($location[$i]->date)) && (strtolower($location[$i]->date != null)))
            {   
                DB::table('map_locations')->update(['visit_count'=>'0']);

            }   
        }

        $data['location'] = Map_location::get(['name','visit_count']);
        $data['count'] = Map_location::get(['name','visit_count'])->count();

        // analytics resets
        $dateTime = new DateTime('now');
        $monday = clone $dateTime->modify(('Sunday' == $dateTime->format('l')) ? 'Monday last week' : 'Monday this week');
        $sunday = clone $dateTime->modify('Sunday this week');
        $now = date('Y-m-d');
        $start = $monday->format('Y-m-d');
        $end = $sunday->format('Y-m-d');
        // for modification date
        // $end = strtotime("2022-11-01");

        $check_date = Reset_analytic::where('stuff', session('LoggedUser'))->first();

        if ($check_date != null)
        {
            if (strtotime($check_date->start) == strtotime($start))
            {
                if (strtotime($now) > strtotime($check_date->end))
                {
                    //reset analytics
                    DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                    
                    return view('stuff.dashboard', $data);
                }
                else
                {
                    return view('stuff.dashboard', $data);
                }
            }
            else 
            {
                //update start end date
                $up_date = Reset_analytic::where('stuff', session('LoggedUser'))->first();
                $up_date->start = $start;
                $up_date->end = $end;
                $up_date->save();

                //reset analytics
                DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                    
                return view('stuff.dashboard', $data);
            }
        }
        else
        {
            $td_start_end = new Reset_analytic;
            $td_start_end->stuff = session('LoggedUser');
            $td_start_end->start = $start;
            $td_start_end->end = $end;
            $td_start_end->save();

            $check_date = Reset_analytic::where('stuff', session('LoggedUser'))->first();
        
            if (strtotime($check_date->start) == $start)
            {
                if (strtotime($now) > strtotime($check_date->end))
                {
                    //reset analytics
                    DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                    
                    return view('stuff.dashboard', $data);
                }
                else
                {
                    return view('stuff.dashboard', $data);
                }
            }
            else 
            {
                  //update start end date
                  $up_date = Reset_analytic::where('stuff', session('LoggedUser'))->first();
                  $up_date->start = $start;
                  $up_date->end = $end;
                  $up_date->save();

                //reset analytics
                DB::table('weekly_counts')->update(['Monday'=>null,'Tuesday'=>null,'Wednesday'=>null,'Thursday'=>null,'Friday'=>null,'Saturday'=>null,'Sunday'=>null]);
                    
                return view('stuff.dashboard', $data);
            }
        }

    }

    function dashboard_fetch ()
    {

        date_default_timezone_set('Asia/Manila');
        $end = "18";
        $now = date('H');
        $day = date('l');

        $data = Map_location::get(['name','visit_count']);
        $count = Map_location::get(['name','visit_count'])->count();

        return response()->json([
            'data' => $data,
            'count' => $count,
        ]);

    }

    function graph_data ()
    {

        date_default_timezone_set('Asia/Manila');
        $end = "18";
        $now = date('H');

        $acc = User::where('id','=', session('LoggedUser'))->first();

        $day = date('l');

        $data = Weekly_count::where('user_id','=', session('LoggedUser'))->where('location','=', $acc->location)->first();

        return response()->json([
            'visit_count'=>$data,
        ]);
 
  
    }

    function alert ()
    {
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();

        
        return view('stuff.alert', $data);
    }

    function notif_log ()
    {
        $acc = User::where('id','=', session('LoggedUser'))->first();
        $data = Stuff_notification::where('creator_id','=', $acc->location)->get();

        return response()->json([
            'notification'=>$data,
        ]);
    }

    function delete_notif (Request $req)
    {
        DB::table('stuff_notifications')->where('id',$req->id)->delete();
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();

        
        return view('stuff.alert', $data);
    }

    function send_notification (Request $req)
    {
        // validator
        $validate = \Validator::make($req->all(), [
            'type' => 'required',
            'message' => 'required|max:500',
        ]);

        if ($validate->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validate->messages(),
            ]);
        }
        else
        { 
            date_default_timezone_set('Asia/Manila');

            $sender = User::where('id','=', session('LoggedUser'))->first();

            
            $user_notification = new User_notification;
            $user_notification->creator_id = $sender->location;
            $user_notification->message = $req->input('message');
            $user_notification->type = $req->input('type');
            $user_notification->time =  date('g:i:a');
            $user_notification->date =  date('F j, Y');
            $user_notification->status = "unread";
            $user_notification->save();


            $notification = new Stuff_notification;
            $notification->creator_id = $sender->location;
            $notification->message = $req->input('message');
            $notification->type = $req->input('type');
            $notification->time =  date('g:i:a');
            $notification->date =  date('F j, Y');
            $notification->status = "unread";
            $notification->save();
            

            return response()->json([
                'status'=>200,
                'success'=>'Notification sent',
            ]);
        }

      
    }


    function check_point ()
    {
        $acc_data = User::where('id','=', session('LoggedUser'))->first();
        // passing multiple variable to view
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();
        $data['book_list'] =  DB::table('book_requests')->where('destination', '=', $acc_data->location )->where('status','=','pending')->get();
        
        return view('stuff.check-point', $data);

    }

    function fetch_checkpoint ()
    {
        $acc_data = User::where('id','=', session('LoggedUser'))->first();

        $checkpoint_list = DB::table('book_requests')->where('destination', '=', $acc_data->location )->where('status','=','pending')->get()->get();

        return response()->json([
            'book_list'=>$checkpoint_list,
        ]);
    }

    // viewe delete confirm book request
    function br_view (Request $req)
    {
        //getting all data with booker
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();
        $data['groups'] = DB::table('book_datas')->where('book_number', '=', $req->id)->get();

        if ($data['groups'])
        {
            return view('stuff.groups-view', $data);
        }
        else
        {
            return back();
        }

    }

    function br_delete (Request $req)
    {
        $delete = DB::table('book_datas')->where('booker_id',$req->id)->delete();
        $delete = DB::table('book_requests')->where('id',$req->id)->delete();

        return back();
    }

    function br_confirm (Request $req)
    {
        date_default_timezone_set('Asia/Manila');
        $time_date  = date('F j, Y g:i:a  ');

        $datetime = DateTime::createFromFormat('YmdHi', '201308131830'); 

        $confirm = Book_request::where('id','=', $req->id)->first();
        $group = DB::table('book_datas')->where('booker_id', '=', $req->id)->get();
        $groupCount = $group->count();

        // approving request
        $approve = new Approve;
        $approve->booker_id = $confirm->id;
        $approve->user_id = $confirm->user_id;
        $approve->stuff_id = session('LoggedUser');
        $approve->first_name = $confirm->first_name;
        $approve->last_name = $confirm->last_name;
        $approve->destination = $confirm->destination;
        $approve->gender = $confirm->gender;
        $approve->phone = $confirm->phone;
        $approve->email = $confirm->email;
        $approve->address = $confirm->address;
        $approve->book_number = $confirm->book_number;
        $approve->groups = $groupCount;
        $approve->day = strtolower(date('l'));
        $approve->approve_td = $time_date;
        $approve->save();

        $data = User::where('id','=', session('LoggedUser'))->first();

        $visit_count = Approve::where('book_number','=', $confirm->book_number)->where('destination','=', strtolower($data->location))->get();
        $visit_group = Book_data::where('book_number','=', $confirm->book_number)->where('destination','=', strtolower($data->location))->get();
        $total = $visit_count->count() + $visit_group->count();

        $data2 = Map_location::get(['name']);
        $count = $data2->count();
        $date  = date('F j, Y');
        $end = "18";
        $day = date('l');


        for($i = 0; $i < $count; $i++){
            if (strtolower($data->location) == strtolower($data2[$i]->name))
            {

                $map_count = Map_location::where('name','=', strtolower($data2[$i]->name))->first();
                $count = $total + (int)$map_count->visit_count;
                $map_count->visit_count = $count ;
                $map_count->date = $date;
                $rews = $map_count->save();
               
                break;
            }
        }
        

        $acc = User::where('id','=', session('LoggedUser'))->first();

       
        $final = Weekly_count::where('user_id','=', session('LoggedUser'))->first();



        if ($final == null)
        {
         
            $insert = new Weekly_count;
            $insert->user_id = session('LoggedUser');
            $insert->location = $acc->location;
            $insert->save();

            $update = Weekly_count::where('user_id','=', session('LoggedUser'))->first();
            $update->$day = $total + (int)$update->$day;
            $update->save();

            
            $status_update = Book_request::where('id',$req->id)->first();
            $status_update->status = "approve";
            $status_update->save();

            return redirect('/stuff/check/point')->with('success','Approve Successfully');

        }
        else
        {
            $update = Weekly_count::where('user_id','=', session('LoggedUser'))->first();
            $final->$day = $total + (int)$update->$day;
            $final->location = $acc->location;
            $final->save();

            $status_update = Book_request::where('id',$req->id)->first();
            $status_update->status = "approve";
            $status_update->save();

            return redirect('/stuff/check/point')->with('success','Approve Successfully');
    
        }
    }

    //reports generation
    function logs ()
    {
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();
        $data['lists'] = Approve::where('stuff_id',session('LoggedUser'))->paginate(10);

        return view('stuff.history', $data);
    }

    function records_group_view (Request $req)
    {
        //getting all data with booker
        $data['user_data'] = User::where('id','=', session('LoggedUser'))->first();
        $data['groups'] = Group_approve::where('book_number', '=', $req->id)->paginate(10);

        if ($data['groups']->isNotEmpty())
        {
            return view('stuff.log-view', $data);
        }
        else
        {
            return back();
        }

    }

}
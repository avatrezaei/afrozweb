<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function allUsers()
    {
        $users = User::latest()->paginate(getPaginate());
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'users'=>$users,
            ]
        ]);
    }

    public function activeUsers()
    {
        $users = User::active()->latest()->paginate(getPaginate());
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'users'=>$users,
            ]
        ]);
    }

    public function bannedUsers()
    {
        $users = User::banned()->latest()->paginate(getPaginate());
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'users'=>$users,
            ]
        ]);
    }

    public function emailUnverifiedUsers()
    {
        $users = User::emailUnverified()->latest()->paginate(getPaginate());
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'users'=>$users,
            ]
        ]);
    }

    public function emailVerifiedUsers()
    {
        $users = User::emailVerified()->latest()->paginate(getPaginate());
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'users'=>$users,
            ]
        ]);
    }

    public function smsUnverifiedUsers()
    {
        $users = User::smsUnverified()->latest()->paginate(getPaginate());
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'users'=>$users,
            ]
        ]);
    }

    public function smsVerifiedUsers()
    {
        $users = User::smsVerified()->latest()->paginate(getPaginate());
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'users'=>$users,
            ]
        ]);
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $users = User::where(function ($user) use ($search) {
            $user->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%")
                ->orWhere('firstname', 'like', "%$search%")
                ->orWhere('lastname', 'like', "%$search%");
        });
        switch ($scope) {
            case 'active':
                $users = $users->where('status', 1);
                break;
            case 'banned':
                $users = $users->where('status', 0);
                break;
            case 'emailUnverified':
                $users = $users->where('ev', 0);
                break;
            case 'smsUnverified':
                $users = $users->where('sv', 0);
                break;
        }
        $users = $users->paginate(getPaginate());

        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'users'=>$users,
            ]
        ]);
     }

    public function detail($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'user'=>$user,
            ]
        ]);
       }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'firstname' => 'required|max:60',
            'lastname' => 'required|max:60',
            'email' => 'required|email|max:160|unique:users,email,' . $user->id,
        ]);

        if ($request->email != $user->email && User::whereEmail($request->email)->whereId('!=', $user->id)->count() > 0) {
            $notify[] = ['error', 'Email already exists.'];
            return response()->json([
                'code'=>200,
                'status'=>'ok',
                'message'=>['error'=>$notify],
            ]);
        }
        if ($request->mobile != $user->mobile && User::where('mobile', $request->mobile)->whereId('!=', $user->id)->count() > 0) {
            $notify[] = ['error', 'Phone number already exists.'];
            return response()->json([
                'code'=>200,
                'status'=>'ok',
                'message'=>['error'=>$notify],
            ]);
        }

        $user->update([
            'mobile' => $request->mobile,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'address' => [
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
            ],
            'status' => $request->status ? 1 : 0,
        ]);

        $notify[] = ['success', 'User detail has been updated'];
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'message'=>$notify,
                'user'=>$user,
            ]
        ]);
    }

    public function userLoginHistory($id)
    {
        $user = User::findOrFail($id);
        $page_title = 'User Login History - ' . $user->username;
        $empty_message = 'No users login found.';
        $login_logs = $user->login_logs()->latest()->paginate(getPaginate());
        return view('admin.users.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function referrals($id)
    {
        $user = User::findOrFail($id);
        $page_title = 'User Referrals - ' . $user->username;
        $referrals = User::where('ref_by',$user->id)->orderBy('id','desc')->paginate(getPaginate());
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'user'=>$user,
                'referrals'=>$referrals,
            ]
        ]);
    }

    public function loginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $login_logs = UserLogin::whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            })->latest()->paginate(getPaginate());
            return response()->json([
                'code'=>200,
                'status'=>'ok',
                'logs'=>$login_logs,
            ]);
        }
        $login_logs = UserLogin::latest()->paginate(getPaginate());
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'logs'=>$login_logs,
        ]);
    }

    public function loginIpHistory($ip)
    {
        $page_title = 'Login By - ' . $ip;
        $login_logs = UserLogin::where('user_ip',$ip)->latest()->paginate(getPaginate());
        $empty_message = 'No users login found.';
        return view('admin.users.logins', compact('page_title', 'empty_message', 'login_logs'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $user = User::findOrFail($id);
        //send_general_email($user->email, $request->subject, $request->message, $user->username);
        $notify[] = ['success', $user->username . ' will receive an email shortly.'];
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'message'=>$notify,
        ]);
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

//        foreach (User::where('status', 1)->cursor() as $user) {
//            send_general_email($user->email, $request->subject, $request->message, $user->username);
//        }

        $notify[] = ['success', 'All users will receive an email shortly.'];
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'message'=>$notify,
        ]);
    }

    public function emailLog($id){
        $user = User::findOrFail($id);
        $page_title = 'Email log of '.$user->username;
        $logs = EmailLog::where('user_id',$id)->orderBy('id','desc')->paginate(getPaginate());
        $empty_message = 'No data found';
        return view('admin.users.email_log', compact('page_title','logs','empty_message'));
    }

    public function emailDetails($id){
        $email = EmailLog::findOrFail($id);
        $page_title = 'Email details';
        return view('admin.users.email_details', compact('page_title','email'));
    }

}

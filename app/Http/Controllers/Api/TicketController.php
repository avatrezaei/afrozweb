<?php

namespace App\Http\Controllers\Api;

use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function supportTicket()
    {
        $supports = SupportTicket::where('user_id', Auth::id())->latest()->paginate(15);
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'supports'=>$supports,
            ]
        ]);
    }

    public function storeSupportTicket(Request $request)
    {
        $ticket = new SupportTicket();
        $message = new SupportMessage();

        $imgs = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');


        $this->validate($request, [
            'attachments' => [
                'max:4096',
                function ($attribute, $value, $fail) use ($imgs, $allowedExts) {
                    foreach ($imgs as $img) {
                        $ext = strtolower($img->getClientOriginalExtension());
                        if (($img->getSize() / 1000000) > 2) {
                            return $fail("Images MAX  2MB ALLOW!");
                        }
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, pdf images are allowed");
                        }
                    }
                    if (count($imgs) > 5) {
                        return $fail("Maximum 5 images can be uploaded");
                    }
                },
            ],
            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);


        $ticket->user_id = Auth::id();
        $random = rand(100000, 999999);
        $ticket->ticket = $random;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();


        $path = imagePath()['ticket']['path'];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as  $image) {
                try {
                    SupportAttachment::create([
                        'support_message_id' => $message->id,
                        'image' => uploadImage($image, $path),
                    ]);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload your ' . $image];
                    return back()->withNotify($notify)->withInput();
                }
            }
        }
        $notify[] = ['success', 'ticket created successfully!'];
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'message'=>$notify,
                'ticket'=>$ticket,
            ]
        ]);
    }

    public function viewTicket($ticket)
    {
        $my_ticket = SupportTicket::where('id', $ticket)->latest()->first();
        $messages = SupportMessage::where('supportticket_id', $my_ticket->id)->latest()->get();
        $user = auth()->user();

        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'user'=>$user,
                'messages'=>$messages,
                'ticket'=>$my_ticket,
            ]
        ]);

    }

    public function replyTicket(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $message = new SupportMessage();

        if ($request->replayTicket == 1) {
            $imgs = $request->file('attachments');
            $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');

            $this->validate($request, [
                'attachments' => [
                    'max:4096',
                    function ($fail) use ($imgs, $allowedExts) {
                        foreach ($imgs as $img) {
                            $ext = strtolower($img->getClientOriginalExtension());
                            if (($img->getSize() / 1000000) > 2) {
                                return $fail("Images MAX  2MB ALLOW!");
                            }
                            if (!in_array($ext, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg, pdf images are allowed");
                            }
                        }
                        if (count($imgs) > 5) {
                            return $fail("Maximum 5 images can be uploaded");
                        }
                    },
                ],
                'message' => 'required',
            ]);

            $ticket->status = 2;
            $ticket->last_reply = Carbon::now();
            $ticket->save();

            $message->supportticket_id = $ticket->id;
            $message->message = $request->message;
            $message->save();

            $path = imagePath()['ticket']['path'];

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $image) {
                    try {
                        SupportAttachment::create([
                            'support_message_id' => $message->id,
                            'image' => uploadImage($image, $path),
                        ]);
                    } catch (\Exception $exp) {
                        $notify[] = ['error', 'Could not upload your ' . $image];
                        return response()->json([
                            'code'=>200,
                            'status'=>'ok',
                            'data'=>[
                                'error'=>$notify,
                            ]
                        ]);
                    }
                }
            }

            $notify[] = ['success', 'Support ticket replied successfully!'];
            return response()->json([
                'code'=>200,
                'status'=>'ok',
                'data'=>[
                    'message'=>$notify,
                ]
            ]);
        } else {
            $ticket->status = 3;
            $ticket->last_reply = Carbon::now();
            $ticket->save();
            $notify[] = ['success', 'Support ticket closed successfully!'];
            return response()->json([
                'code'=>200,
                'status'=>'ok',
                'data'=>[
                    'message'=>$notify,
                ]
            ]);
        }

    }

}

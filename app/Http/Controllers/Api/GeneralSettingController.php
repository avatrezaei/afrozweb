<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $general = GeneralSetting::first();
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'general'=>$general,
            ]
        ]);
    }

    public function update(Request $request)
    {
        $validation_rule = [
            'base_color' => ['nullable', 'regex:/^[a-f0-9]{6}$/i'],
            'secondary_color' => ['nullable', 'regex:/^[a-f0-9]{6}$/i']
        ];

        $validator = Validator::make($request->all(), $validation_rule, []);
        $validator->validate();

        $general_setting = GeneralSetting::first();
        $request->merge(['ev' => isset($request->ev) ? 1 : 0]);
        $request->merge(['en' => isset($request->en) ? 1 : 0]);
        $request->merge(['sv' => isset($request->sv) ? 1 : 0]);
        $request->merge(['sn' => isset($request->sn) ? 1 : 0]);
        $request->merge(['b_transfer' => isset($request->b_transfer) ? 1 : 0]);
        $request->merge(['registration' => isset($request->registration) ? 1 : 0]);
        $request->merge(['signup_bonus_control' => isset($request->signup_bonus_control) ? 1 : 0]);

        $general_setting->update($request->only([
            'sitename',
            'cur_text',
            'cur_sym',
            'ev',
            'en',
            'sv',
            'sn',
            'registration',
            'base_color',
            'secondary_color',
            'signup_bonus_amount',
            'signup_bonus_control',
            'b_transfer',
            'f_charge',
            'p_charge',
            'email_template'
        ]));
        $notify[] = ['success', 'General Setting has been updated.'];
        return response()->json([
            'code'=>200,
            'status'=>'ok',
            'data'=>[
                'message'=>$notify,
            ]
        ]);
    }

}

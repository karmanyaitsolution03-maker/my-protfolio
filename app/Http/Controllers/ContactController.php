<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:190',
            'message' => 'required|string|max:5000',
        ]);

        Message::create($data);

        // Optional: also email yourself. Configure MAIL_* in .env, then uncomment:
        // \Illuminate\Support\Facades\Mail::raw(
        //     $data['message'] . "\n\n— {$data['name']} <{$data['email']}>",
        //     fn ($m) => $m->to(config('mail.from.address'))->subject('Portfolio: connection request from ' . $data['name'])
        // );

        return response()->json(['ok' => true]);
    }
}

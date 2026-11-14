<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact');
    }

    public function store(ContactRequest $request)
    {
        ContactMessage::create($request->safe()->except('website'));

        return redirect()->route('contact.create')->with('success', 'Pesan terkirim. Kami akan segera menghubungi Anda.');
    }
}

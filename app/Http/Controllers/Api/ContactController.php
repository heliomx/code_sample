<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{

    public function store(Request $request)
    {
        $contact = new Contact;
        $contact->fill([
            'name' => request()->input('name'),
            'email' => request()->input('email'),
            'subject' => request()->input('subject'),
            'message' => request()->input('message'),
            'annotations' => '',
            'status'  => Contact::STATUS_NEW
        ]);

        $contact->save();

        return response()->json( ['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);
        $contact->fill([
            'annotations' => request()->input('annotations'),
            'status'  => request()->input('status'),
        ]);

        $contact->save();

        return response()->json( ['success' => true]);
    }

    public function show(Request $request, $id)
    {
        $contact = Contact::find($id);

        return response()->json( ['data' => $contact]);
    }

    public function index(Request $request)
    {
        $contacts = Contact::all();

        return response()->json( [ 'data' => $contacts ] );
    }
}
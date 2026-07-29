<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $messages = Contact::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.contacts.index', compact('messages'));
    }

    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function markReplied(Contact $contact)
    {
        $contact->update(['is_replied' => true]);

        return back()->with('success', 'Message marked as replied.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Message deleted successfully.');
    }
}

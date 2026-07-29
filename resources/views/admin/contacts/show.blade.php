@extends('admin.layout')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.contacts.index') }}" class="admin-link">&larr; Back to Messages</a>
</div>

<h1 style="margin-bottom: 1.5rem;">Contact Message #{{ $contact->id }}</h1>

<div class="admin-card" style="margin-bottom: 1.5rem;">
    <p><strong>From:</strong> {{ $contact->name }} &lt;{{ $contact->email }}&gt;</p>
    <p><strong>Subject:</strong> {{ $contact->subject }}</p>
    <p><strong>Received:</strong> {{ $contact->created_at->format('M d, Y h:i A') }}</p>
    <p><strong>Status:</strong>
        @if($contact->is_replied)
            <span style="color: #059669; font-weight: 600;">Replied</span>
        @else
            <span style="color: #D97706; font-weight: 600;">Pending</span>
        @endif
    </p>
</div>

<div class="admin-card" style="margin-bottom: 1.5rem;">
    <h3 style="margin-bottom: 1rem;">Message</h3>
    <p style="line-height: 1.7; white-space: pre-wrap; margin: 0;">{{ $contact->message }}</p>
</div>

<div class="flex gap-4">
    @if(!$contact->is_replied)
        <form action="{{ route('admin.contacts.replied', $contact) }}" method="POST">
            @csrf
            <button type="submit" class="admin-btn-success">Mark as Replied</button>
        </form>
    @endif
    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this message?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-logout" style="background: #64748B;">Delete</button>
    </form>
</div>
@endsection

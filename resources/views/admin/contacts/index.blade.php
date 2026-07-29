@extends('admin.layout')

@section('content')
<div class="flex justify-between items-center" style="margin-bottom: 20px;">
    <h1>Contact Messages</h1>
</div>

<div class="admin-card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead style="background: #0F172A; color: #fff;">
            <tr>
                <th style="padding: 12px; text-align: left;">ID</th>
                <th style="padding: 12px; text-align: left;">Name</th>
                <th style="padding: 12px; text-align: left;">Email</th>
                <th style="padding: 12px; text-align: left;">Subject</th>
                <th style="padding: 12px; text-align: left;">Status</th>
                <th style="padding: 12px; text-align: left;">Date</th>
                <th style="padding: 12px; text-align: left;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $message)
                <tr style="border-bottom: 1px solid #E2E8F0;">
                    <td style="padding: 12px;">{{ $message->id }}</td>
                    <td style="padding: 12px; font-weight: 600;">{{ $message->name }}</td>
                    <td style="padding: 12px;">{{ $message->email }}</td>
                    <td style="padding: 12px;">{{ Str::limit($message->subject, 40) }}</td>
                    <td style="padding: 12px;">
                        @if($message->is_replied)
                            <span style="color: #059669; font-weight: 600;">Replied</span>
                        @else
                            <span style="color: #D97706; font-weight: 600;">Pending</span>
                        @endif
                    </td>
                    <td style="padding: 12px;">{{ $message->created_at->format('M d, Y') }}</td>
                    <td style="padding: 12px;">
                        <a href="{{ route('admin.contacts.show', $message) }}" class="admin-link">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 20px; text-align: center; color: #64748B;">No contact messages yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $messages->links() }}
    </div>
</div>
@endsection

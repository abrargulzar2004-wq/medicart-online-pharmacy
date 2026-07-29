@extends('layouts.app')

@section('title', 'Contact Us - MediCart')

@section('content')
<section style="margin-bottom: 2.5rem;">
    <span class="badge badge-new" style="margin-bottom: 1rem;">Get in Touch</span>
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--dark);">Contact Us</h1>
    <p style="font-size: 1.05rem; color: var(--text-muted); max-width: 640px;">
        Have a question about your order, prescription, or a product? Fill out the form below and our support team will get back to you promptly.
    </p>
</section>

<div class="grid grid-cols-2" style="gap: 2rem; align-items: start;">
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-bottom: 1.5rem;">Send a Message</h2>
        <form method="POST" action="{{ route('contact.submit') }}">
            @csrf
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label" for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}" placeholder="John Doe">
            </div>
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="john@example.com">
            </div>
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label class="form-label" for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="form-control" required value="{{ old('subject') }}" placeholder="Order inquiry, prescription help, etc.">
            </div>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" for="message">Message</label>
                <textarea id="message" name="message" class="form-control" rows="6" required placeholder="How can we help you?">{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 0.875rem 1.75rem; width: 100%;">
                <i class="ph ph-paper-plane-tilt"></i> Send Message
            </button>
        </form>
    </div>

    <div>
        <div class="card" style="padding: 2rem; margin-bottom: 1.5rem;">
            <h3 style="margin-bottom: 1.25rem;">Contact Information</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="display: flex; gap: 1rem; margin-bottom: 1.25rem; align-items: flex-start;">
                    <i class="ph-fill ph-map-pin" style="font-size: 1.5rem; color: var(--primary); margin-top: 0.15rem;"></i>
                    <div>
                        <strong>Address</strong>
                        <p style="color: var(--text-muted); margin: 0.25rem 0 0;">123 Healthcare Avenue, Medical District, NY 10001</p>
                    </div>
                </li>
                <li style="display: flex; gap: 1rem; margin-bottom: 1.25rem; align-items: flex-start;">
                    <i class="ph-fill ph-phone" style="font-size: 1.5rem; color: var(--primary); margin-top: 0.15rem;"></i>
                    <div>
                        <strong>Phone</strong>
                        <p style="color: var(--text-muted); margin: 0.25rem 0 0;">+1 (800) 123-4567</p>
                    </div>
                </li>
                <li style="display: flex; gap: 1rem; margin-bottom: 1.25rem; align-items: flex-start;">
                    <i class="ph-fill ph-envelope-simple" style="font-size: 1.5rem; color: var(--primary); margin-top: 0.15rem;"></i>
                    <div>
                        <strong>Email</strong>
                        <p style="color: var(--text-muted); margin: 0.25rem 0 0;">support@medicart.com</p>
                    </div>
                </li>
                <li style="display: flex; gap: 1rem; align-items: flex-start;">
                    <i class="ph-fill ph-clock" style="font-size: 1.5rem; color: var(--primary); margin-top: 0.15rem;"></i>
                    <div>
                        <strong>Business Hours</strong>
                        <p style="color: var(--text-muted); margin: 0.25rem 0 0;">Mon – Sat: 8:00 AM – 10:00 PM<br>Sunday: 9:00 AM – 6:00 PM</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="card" style="padding: 2rem; background: var(--bg-main);">
            <h4 style="margin-bottom: 0.75rem;"><i class="ph-fill ph-first-aid"></i> Need urgent help?</h4>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0;">
                For medical emergencies, please call your local emergency services immediately. MediCart is an e-commerce platform and does not provide emergency medical care.
            </p>
        </div>
    </div>
</div>
@endsection

@extends('admin.layout')

@section('content')
<div class="flex justify-between items-center" style="margin-bottom: 20px;">
    <h1>Admin Settings</h1>
</div>

<div class="admin-card" style="max-width: 800px;">
    
    <h2 style="margin-top: 0; border-bottom: 1px solid #E2E8F0; padding-bottom: 10px; color: #0F172A;">Appearance Preferences</h2>
    
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <!-- Theme Setting -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 0; border-bottom: 1px solid #E2E8F0;">
            <div>
                <h3 style="margin: 0 0 5px 0;">Dark Mode</h3>
                <p style="margin: 0; color: #64748B; font-size: 14px;">Switch the admin dashboard to a darker color scheme.</p>
            </div>
            <div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 28px;">
                    <input type="checkbox" name="admin_theme" id="themeToggle" value="dark" {{ (isset($settings['admin_theme']) && $settings['admin_theme'] == 'dark') ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" onchange="toggleTheme()">
                    <span class="slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;">
                        <span class="knob" style="position: absolute; content: ''; height: 20px; width: 20px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                    </span>
                </label>
            </div>
        </div>

        <!-- Sidebar Setting -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 0;">
            <div>
                <h3 style="margin: 0 0 5px 0;">Compact Sidebar</h3>
                <p style="margin: 0; color: #64748B; font-size: 14px;">Minimize the sidebar to show only icons and save screen space.</p>
            </div>
            <div>
                <label class="switch" style="position: relative; display: inline-block; width: 50px; height: 28px;">
                    <input type="checkbox" name="admin_sidebar" id="sidebarToggle" value="compact" {{ (isset($settings['admin_sidebar']) && $settings['admin_sidebar'] == 'compact') ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" onchange="toggleSidebar()">
                    <span class="slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;">
                        <span class="knob" style="position: absolute; content: ''; height: 20px; width: 20px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%;"></span>
                    </span>
                </label>
            </div>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <button type="submit" class="admin-btn-primary">Save Settings</button>
        </div>
    </form>

</div>

@push('styles')
<style>
    /* Custom Toggle Switch CSS */
    input:checked + .slider { background-color: #2563EB; }
    input:focus + .slider { box-shadow: 0 0 1px #2563EB; }
    input:checked + .slider .knob { transform: translateX(22px); }
</style>
@endpush

@push('scripts')
<script>
    // Initialize toggles based on current localStorage state
    document.addEventListener("DOMContentLoaded", function() {
        if (localStorage.getItem('admin_theme') === 'dark') {
            document.getElementById('themeToggle').checked = true;
        }
        if (localStorage.getItem('admin_sidebar') === 'compact') {
            document.getElementById('sidebarToggle').checked = true;
        }
    });

    function toggleTheme() {
        const isChecked = document.getElementById('themeToggle').checked;
        if (isChecked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('admin_theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('admin_theme', 'light');
        }
    }

    function toggleSidebar() {
        const isChecked = document.getElementById('sidebarToggle').checked;
        if (isChecked) {
            document.body.classList.add('compact-sidebar');
            localStorage.setItem('admin_sidebar', 'compact');
        } else {
            document.body.classList.remove('compact-sidebar');
            localStorage.setItem('admin_sidebar', 'full');
        }
    }
</script>
@endpush
@endsection

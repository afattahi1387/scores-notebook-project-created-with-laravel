@extends('includes.dashboard_html_structure')

@section('icon', 'danger.jpg')

@section('title', 'بازگردانی سایت به حالت بدون اطلاعات')

@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4" style="direction: rtl;">
                <h3 class="mt-4">بازگردانی سایت به حالت بدون اطلاعات</h3><br>
                <div class="card mb-4" style="direction: rtl;">
                    <div class="card-header">
                        <i class="fas fa-exclamation-triangle"></i>
                        بازگردانی سایت به حالت بدون اطلاعات
                    </div>
                    <div class="card-body">
                        آیا کاملا از بازگرداندن سایت به حالت بدون اطلاعات اطمینان کامل دارید؟ در اینصورت تمامی اطلاعات سایت به صورت کامل حذف خواهند شد.
                        <br><br>
                        <a href="{{ route('restore.project.function') }}" class="btn btn-success">بله، کاملا اطمینان دارم</a>
                        <a href="{{ route('admins.dashboard') }}" class="btn btn-danger">نه، منصرف شدم</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

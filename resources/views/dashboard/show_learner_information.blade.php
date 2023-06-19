@extends('includes.dashboard_html_structure')

@section('title')
نمایش اطلاعات: {{ $learner->name }}
@endsection

@section('content')
    <div id="layoutSidenav_content" style="direction: rtl;">
        <main>
            <div class="container-fluid px-4">
                <h2 class="mt-4">نمایش اطلاعات: {{ $learner->name }}</h2><br>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-graduation-cap"></i>
                        نمایش اطلاعات: {{ $learner->name }}
                    </div>
                    <div class="card-body">
                        @foreach($attendances as $attendance)
                            @if(!empty($attendance->score) && $attendance->relavant_roll_call()->term == 'first')
                                <b>نمره تاریخ {{ $attendance->relavant_roll_call()->date }}:</b> <span class="text-primary">{{ $attendance->score }}</span>
                                <span> | </span>
                                <a href="#" class="text-warning">ویرایش</a><br><br>
                            @endif
                        @endforeach
                        <h5>میانگین نمرات ترم اول: <span class="text-primary">{{ $learner->average_of_first_term_scores() }}</span></h5><br>
                        <h5>نمره پایانی ترم اول: <span class="text-primary">{{ $learner->first_term_final_scores == null ? 'نامشخص' : $learner->first_term_final_scores }}</span></h5>
                        <hr>
                        @foreach($attendances as $attendance)
                            @if(!empty($attendance->score) && $attendance->relavant_roll_call()->term == 'second')
                                <b>نمره تاریخ {{ $attendance->relavant_roll_call()->date }}:</b> <span class="text-primary">{{ $attendance->score }}</span>
                                <span> | </span>
                                <a href="#" class="text-warning">ویرایش</a><br><br>
                            @endif
                        @endforeach
                        <h5>میانگین نمرات ترم دوم: <span class="text-primary">{{ $learner->average_of_second_term_scores() }}</span></h5><br>
                        <h5>نمره پایانی ترم دوم: <span class="text-primary">{{ $learner->second_term_final_scores == null ? 'نامشخص' : $learner->second_term_final_scores }}</span></h5>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

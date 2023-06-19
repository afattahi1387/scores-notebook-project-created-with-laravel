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
                    @if(isset($_GET['edit-score']) && !empty($_GET['edit-score']))
                        <div class="card-header">
                            <i class="fas fa-edit"></i>
                            ویرایش نمره گرفته شده در تاریخ: <b>{{ $score_for_edit->relavant_roll_call()->date }}</b>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('update.score', ['score' => $score_for_edit->id]) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="put">
                                <div class="input-group">
                                    <input type="number" name="score" placeholder="نمره گرفته شده" value="{{ $score_for_edit->score }}" class="form-control" style="margin-left: 3px;">
                                    <button type="submit" class="btn btn-warning" style="margin-left: 3px; color: white; border-radius: 3px;"><i class="fas fa-edit"></i></button>
                                    <a href="{{ route('show.learner.information', ['learner' => $learner->id]) }}" class="btn btn-danger"><i class="fas fa-times"></i></a>
                                </div>
                            </form>
                        </div>
                    @elseif(isset($_GET['edit-term-final-score']) && !empty($_GET['edit-term-final-score']))
                        @if($_GET['edit-term-final-score'] == 'first')
                            @php $term = 'first'; @endphp
                        @elseif($_GET['edit-term-final-score'] == 'second')
                            @php $term = 'second'; @endphp
                        @endif
                        <div class="card-header">
                            <i class="fas fa-edit"></i>
                            @if($term == 'first')
                                ویرایش نمره پایانی ترم اول
                            @elseif($term == 'second')
                                ویرایش نمره پایانی ترم دوم
                            @endif
                        </div>
                        <div class="card-body">
                            <form action="{{ route('update.term.final.score', ['learner' => $learner->id, 'term' => $term]) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="put">
                                <input type="hidden" name="term" value="{{ $term }}">
                                <div class="input-group">
                                    <input type="number" name="term_final_score" placeholder="نمره پایانی" value="{{ $learner[$term . '_term_final_scores'] }}" max="20" min="0" class="form-control" style="margin-left: 3px;" required>
                                    <button type="submit" class="btn btn-warning" style="margin-left: 3px; color: white; border-radius: 3px;"><i class="fas fa-edit"></i></button>
                                    <a href="{{ route('show.learner.information', ['learner' => $learner->id]) }}" class="btn btn-danger"><i class="fas fa-times"></i></a>
                                </div>
                            </form>
                        </div>
                    {{-- TODO: add else and add date for learner. --}}
                    @endif
                </div>
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
                                <a href="{{ route('show.learner.information', ['learner' => $learner->id]) }}?edit-score={{ $attendance->id }}" class="text-warning">ویرایش</a><br><br>
                            @endif
                        @endforeach
                        <h5>میانگین نمرات ترم اول: <span class="text-primary">{{ $learner->average_of_first_term_scores() }}</span></h5><br>
                        <h5>نمره پایانی ترم اول: <span class="text-primary">{{ $learner->first_term_final_scores == null ? 'نامشخص' : $learner->first_term_final_scores }}</span> | <a href="{{ route('show.learner.information', ['learner' => $learner->id]) }}?edit-term-final-score=first" class="text-warning">ویرایش</a></h5>
                        <hr>
                        @foreach($attendances as $attendance)
                            @if(!empty($attendance->score) && $attendance->relavant_roll_call()->term == 'second')
                                <b>نمره تاریخ {{ $attendance->relavant_roll_call()->date }}:</b> <span class="text-primary">{{ $attendance->score }}</span>
                                <span> | </span>
                                <a href="{{ route('show.learner.information', ['learner' => $learner->id]) }}?edit-score={{ $attendance->id }}" class="text-warning">ویرایش</a><br><br>
                            @endif
                        @endforeach
                        <h5>میانگین نمرات ترم دوم: <span class="text-primary">{{ $learner->average_of_second_term_scores() }}</span></h5><br>
                        <h5>نمره پایانی ترم دوم: <span class="text-primary">{{ $learner->second_term_final_scores == null ? 'نامشخص' : $learner->second_term_final_scores }}</span> | <a href="{{ route('show.learner.information', ['learner' => $learner->id]) }}?edit-term-final-score=second" class="text-warning">ویرایش</a></h5>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
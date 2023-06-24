@extends('includes.dashboard_html_structure')

@section('icon', 'students.jpg')

@section('title')
نمایش اطلاعات: {{ $learner->name }}
@endsection

@section('content')
    <div id="layoutSidenav_content" style="direction: rtl;">
        <main>
            <div class="container-fluid px-4">
                <h2 class="mt-4">نمایش اطلاعات: {{ $learner->name }}</h2><br>
                @if(!empty($_GET))
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
                                        <input type="number" name="score" placeholder="نمره گرفته شده" value="{{ $score_for_edit->score }}" class="form-control" style="margin-left: 3px;" required>
                                        <button type="submit" class="btn btn-warning" style="margin-left: 3px; color: white; border-radius: 3px;"><i class="fas fa-edit"></i></button>
                                        <a href="{{ route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]) }}" class="btn btn-danger"><i class="fas fa-times"></i></a>
                                    </div>
                                </form>
                            </div>
                        @elseif(isset($_GET['edit-term-development-score']) && !empty($_GET['edit-term-development-score']))
                            @if($_GET['edit-term-development-score'] == 'first')
                                @php $term = 'first'; @endphp
                            @elseif($_GET['edit-term-development-score'] == 'second')
                                @php $term = 'second'; @endphp
                            @endif
                            <div class="card-header">
                                <i class="fas fa-edit"></i>
                                @if($term == 'first')
                                    ویرایش نمره تکوینی داده شده برای ترم اول
                                @elseif($term == 'second')
                                    ویرایش نمره تکوینی داده شده برای ترم دوم
                                @endif
                            </div>
                            <div class="card-body">
                                <form action="{{ route('update.term.development.score', ['learner_id' => $learner->id, 'relation_ship_id' => $relation_ship->id, 'term' => $term]) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="put">
                                    <div class="input-group">
                                        <input type="number" name="edit_term_development_score" placeholder="نمره تکوینی" value="{{ $TDS_for_edit[$term . '_term_development_score'] }}" class="form-control" style="margin-left: 3px;" required>
                                        <button type="submit" class="btn btn-warning" style="color: white;"><i class="fas fa-edit"></i></button>
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
                                <form action="{{ route('update.term.final.score', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id, 'term' => $term]) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="put">
                                    <input type="hidden" name="term" value="{{ $term }}">
                                    <div class="input-group">
                                        <input type="number" name="term_final_score" placeholder="نمره پایانی" value="{{ $learner->get_p_n_and_final_score()[$term . '_term_final_score'] }}" max="20" min="0" class="form-control" style="margin-left: 3px;" required>
                                        <button type="submit" class="btn btn-warning" style="margin-left: 3px; color: white; border-radius: 3px;"><i class="fas fa-edit"></i></button>
                                        <a href="{{ route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]) }}" class="btn btn-danger"><i class="fas fa-times"></i></a>
                                    </div>
                                </form>
                            </div>
                        {{-- TODO: add else and add date for learner. --}}
                        @endif
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-graduation-cap"></i>
                        نمایش اطلاعات: {{ $learner->name }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('change.pn.number', ['learner' => $learner->id, 'relation_ship_id' => $relation_ship->id]) }}" method="POST">
                            {{ csrf_field() }}
                            <div class="input-group">
                                <div class="form-floating">
                                    <input type="number" name="first_term_PN_number" id="first_term_PN_number" placeholder="نمره مثبت یا منفی ترم اول" value="{{ $learner->get_p_n_and_final_score()->first_term_PN_number }}" class="form-control" required>
                                    <label for="first_term_PN_number">نمره مثبت یا منفی ترم اول</label>
                                </div>
                                <div class="form-floating">
                                    <input type="number" name="second_term_PN_number" id="second_term_PN_number" placeholder="نمره مثبت یا منفی ترم دوم" value="{{ $learner->get_p_n_and_final_score()->second_term_PN_number }}" class="form-control" required>
                                    <label for="second_term_PN_number">نمره مثبت یا منفی ترم دوم</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning" style="color: white;"><i class="fas fa-edit"></i></button>
                        </form>
                        <hr>
                        @foreach($attendances as $attendance)
                            @if(!empty($attendance->score) && $attendance->relavant_roll_call()->term == 'first')
                                <b>نمره تاریخ {{ $attendance->relavant_roll_call()->date }}:</b> <span class="text-primary">{{ $attendance->score }}</span>
                                <span> | </span>
                                <a href="{{ route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]) }}?edit-score={{ $attendance->id }}" class="text-warning">ویرایش</a><br><br>
                            @elseif($attendance->roll_call == 'absent' && $attendance->relavant_roll_call()->term == 'first')
                                <b>نمره تاریخ {{ $attendance->relavant_roll_call()->date }}:</b> <span class="text-primary">غایب بوده است</span>
                                <span> | </span>
                                <a href="{{ route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]) }}?edit-score={{ $attendance->id }}" class="text-warning">ویرایش</a><br><br>
                            @endif
                        @endforeach
                        <h5>نمره تکوینی محاسبه شده برای ترم اول: <span class="text-primary">{{ $learner->calculate_term_development_score($relation_ship->id, 'first') }}</span></h5><br>
                        <h5>نمره تکوینی داده شده توسط دبیر برای ترم اول: <span class="text-primary">{{ $learner->get_p_n_and_final_score()->first_term_development_score == null ? 'نامشخص' : $learner->get_p_n_and_final_score()->first_term_development_score }}</span> | <a href="{{ route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]) }}?edit-term-development-score=first" class="text-warning">ویرایش</a></h5><br>
                        <h5>نمره پایانی ترم اول: <span class="text-primary">{{ $learner->get_p_n_and_final_score()->first_term_final_score == null ? 'نامشخص' : $learner->get_p_n_and_final_score()->first_term_final_score }}</span> | <a href="{{ route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]) }}?edit-term-final-score=first" class="text-warning">ویرایش</a></h5>
                        <hr>
                        @foreach($attendances as $attendance)
                            @if(!empty($attendance->score) && $attendance->relavant_roll_call()->term == 'second')
                                <b>نمره تاریخ {{ $attendance->relavant_roll_call()->date }}:</b> <span class="text-primary">{{ $attendance->score }}</span>
                                <span> | </span>
                                <a href="{{ route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]) }}?edit-score={{ $attendance->id }}" class="text-warning">ویرایش</a><br><br>
                            @elseif($attendance->roll_call == 'absent' && $attendance->relavant_roll_call()->term == 'second')
                                <b>نمره تاریخ {{ $attendance->relavant_roll_call()->date }}:</b> <span class="text-primary">غایب بوده است</span>
                                <span> | </span>
                                <a href="{{ route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]) }}?edit-score={{ $attendance->id }}" class="text-warning">ویرایش</a><br><br>
                            @endif
                        @endforeach
                        <h5>نمره تکوینی محاسبه شده برای ترم دوم: <span class="text-primary">{{ $learner->calculate_term_development_score($relation_ship->id, 'second') }}</span></h5><br>
                        <h5>نمره تکوینی داده شده توسط دبیر برای ترم دوم: <span class="text-primary">{{ $learner->get_p_n_and_final_score()->second_term_development_score == null ? 'نامشخص' : $learner->get_p_n_and_final_score()->second_term_development_score }}</span> | <a href="{{ route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]) }}?edit-term-development-score=second" class="text-warning">ویرایش</a></h5><br>
                        <h5>نمره پایانی ترم دوم: <span class="text-primary">{{ $learner->get_p_n_and_final_score()->second_term_final_score == null ? 'نامشخص' : $learner->get_p_n_and_final_score()->second_term_final_score }}</span> | <a href="{{ route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]) }}?edit-term-final-score=second" class="text-warning">ویرایش</a></h5>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

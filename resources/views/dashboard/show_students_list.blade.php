@extends('includes.dashboard_html_structure')

@section('title')
نمایش لیست دانش آموزان کلاس: {{ $lesson_room->name }}
@endsection

@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h2 class="mt-4">نمایش لیست دانش آموزان کلاس: {{ $lesson_room->name }}</h2><br>
                @if(auth()->user()->type == 'admin')
                    <div class="card mb-4" style="direction: rtl;">
                        <div class="card-header">
                            <i class="fas fa-list"></i>
                            افزودن دانش آموز برای این کلاس
                        </div>
                        <div class="card-body">
                            @if(isset($_GET['edit-learner']) && !empty($_GET['edit-learner']))
                                //
                            @else
                                @if(isset($_GET['number-of-new-learners']) && !empty($_GET['number-of-new-learners']))
                                    <form action="{{ route('insert.learners.for.lesson.room', ['lesson_room' => $lesson_room->id]) }}" method="POST">
                                        {{ csrf_field() }}
                                        @for($i = 1; $i <= $_GET['number-of-new-learners']; $i++)
                                            <div class="input-group">
                                                <div class="form-floating">
                                                    @if(empty($learners[0]))
                                                        @php
                                                            $learner_row = $i;
                                                        @endphp
                                                    @else
                                                        @php
                                                            $learner_row = $learners[count($learners) - 1]['row'] + $i;
                                                        @endphp
                                                    @endif
                                                    <input type="number" name="learner_row_{{ $learner_row }}" id="learner_row" class="form-control" style="margin-left: 3px;" value="{{ $learner_row }}">
                                                    <label for="learner_row">آمار دانش آموز</label>
                                                </div>
                                                <input type="text" name="learner_name_{{ $learner_row }}" placeholder="نام و نام خانوادگی" class="form-control">
                                            </div><br>
                                        @endfor
                                        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> افزودن دانش آموزان</button>
                                    </form>
                                @else
                                    <form action="{{ route('show.students.list.for.admins', ['lesson_room' => $lesson_room->id]) }}" method="GET">
                                        <div class="input-group">
                                            <input type="number" name="number-of-new-learners" placeholder="تعداد دانش آموزان مورد نیاز برای افزودن" class="form-control" style="margin-left: 3px;" required>
                                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-graduation-cap"></i>
                        نمایش لیست دانش آموزان کلاس: {{ $lesson_room->name }}
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>آمار</th>
                                    <th>نام</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($learners as $learner)
                                    <tr>
                                        <td>{{ $learner->row }}</td>
                                        <td>{{ $learner->name }}</td>
                                        <td>
                                            {{-- <a href="{{ route('add.date', ['lesson_room' => $lesson_room['userable_id'], 'lesson' => $lesson_room['lesson_id']]) }}" class="btn btn-sm btn-success">افزودن تاریخ</a>
                                            <a href="{{ route('show.students.list', ['lesson_room' => $lesson_room['userable_id'], 'lesson' => $lesson_room['lesson_id']]) }}" class="btn btn-sm btn-primary">مشاهده لیست دانش آموزان</a> --}}
                                            عملیات
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

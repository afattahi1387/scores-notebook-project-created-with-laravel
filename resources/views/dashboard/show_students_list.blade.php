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
                            @if(isset($_GET['edit-learner']) && !empty($_GET['edit-learner']))
                                <i class="fas fa-edit"></i>
                                ویرایش دانش آموز: {{ $learner_for_edit->name }}
                            @else
                                <i class="fas fa-plus"></i>
                                افزودن دانش آموز برای این کلاس
                            @endif
                        </div>
                        <div class="card-body">
                            @if(isset($_GET['edit-learner']) && !empty($_GET['edit-learner']))
                                <form action="{{ route('update.learner', ['learner' => $_GET['edit-learner']]) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="put">
                                    <div class="form-floating">
                                        <input type="number" name="learner_row" id="learner_row" placeholder="آمار دانش آموز" value="{{ $learner_for_edit->row }}" class="form-control" style="margin-left: 3px;">
                                        <label for="learner_row">آمار دانش آموز</label>
                                    </div><br>
                                    <div class="form-floating">
                                        <input type="text" name="learner_name" id="learner_name" placeholder="نام و نام خانوادگی دانش آموز" value="{{ $learner_for_edit->name }}" class="form-control">
                                        <label for="learner_name">نام و نام خانوادگی دانش آموز</label>
                                    </div><br>
                                    <button type="submit" class="btn btn-warning" style="color: white;"><i class="fas fa-edit"></i> ویرایش دانش آموز</button>
                                    <a href="{{ route('show.students.list.for.admins', ['lesson_room' => $lesson_room->id]) }}" class="btn btn-danger"><i class="fas fa-times"></i> لغو</a>
                                </form>
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
                                            <a href="{{ route('show.learner.information', ['learner' => $learner->id]) }}" class="btn btn-primary">مشاهده اطلاعات دانش آموز</a>
                                            @if(auth()->user()->type == 'admin')
                                                <a href="{{ route('show.students.list.for.admins', ['lesson_room' => $lesson_room->id]) }}?edit-learner={{ $learner->id }}" class="btn btn-warning" style="color: white;"><i class="fas fa-edit"></i></a>
                                            @endif
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

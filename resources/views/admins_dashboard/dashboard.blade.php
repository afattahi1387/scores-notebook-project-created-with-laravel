@extends('includes.dashboard_html_structure')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4" style="direction: rtl;">
                <h1 class="mt-4">داشبورد</h1><br>
                @if($errors->has('name'))
                    <div class="alert alert-danger" style="direction: rtl;">{{ $errors->first('name') }}</div>
                @endif
                <div class="row" style="direction: rtl;">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-list"></i>
                                کلاس ها
                            </div>
                            <div class="card-body" style="direction: rtl;">
                                @if(isset($_GET['edit-lesson-room']) && !empty($_GET['edit-lesson-room']))
                                    <form action="{{ route('update.lesson.room', ['lesson_room' => $_GET['edit-lesson-room']]) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="put">
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control" placeholder="نام کلاس" value="@if(!empty(old('name'))){{ old('name') }}@else{{ $lesson_room_name }}@endif" style="margin-left: 3px;" required>
                                            <button type="submit" class="btn btn-warning" style="border-radius: 3px; color: white;"><i class="fas fa-edit"></i></button>
                                        </div>
                                    </form>
                                @else
                                    <form action="{{ route('insert.lesson.room') }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control" placeholder="نام کلاس" value="{{ old('name') }}" style="margin-left: 3px;" required>
                                            <button type="submit" class="btn btn-success" style="border-radius: 3px;"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </form>
                                @endif
                                <br>
                                <ul>
                                    @foreach($lesson_rooms as $lesson_room)
                                        <li>{{ $lesson_room->name }} | <a href="{{ route('show.students.list.for.admins', ['lesson_room' => $lesson_room->id]) }}" class="text-primary">مشاهده لیست دانش آموزان</a> | <a href="{{ route('admins.dashboard') }}?edit-lesson-room={{ $lesson_room->id }}" class="text-warning">ویرایش</a> | <span class="text-danger" style="cursor: pointer;">حذف</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-book"></i>
                                درس ها
                            </div>
                            <div class="card-body" style="direction: rtl;">
                                @if(isset($_GET['edit-lesson']) && !empty($_GET['edit-lesson']))
                                    <form action="{{ route('update.lesson', ['lesson' => $_GET['edit-lesson']]) }}" method="POST">
                                        <div class="input-group">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="put">
                                            <input type="text" name="name" class="form-control" placeholder="نام درس" value="@if(!empty(old('name'))){{ old('name') }}@else{{ $lesson_name }}@endif" style="margin-left: 3px;" required>
                                            <button type="submit" class="btn btn-warning" style="border-radius: 3px; color: white;"><i class="fas fa-edit"></i></button>
                                        </div>
                                    </form>
                                    @else
                                        <form action="{{ route('insert.lesson') }}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control" placeholder="نام درس" value="{{ old('name') }}" style="margin-left: 3px;" required>
                                                <button type="submit" class="btn btn-success" style="border-radius: 3px;"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </form>
                                    @endif
                                    <br>
                                    <ul>
                                        @foreach($lessons as $lesson)
                                            <li>{{ $lesson->name }} | <a href="{{ route('admins.dashboard') }}?edit-lesson={{ $lesson->id }}" class="text-warning">ویرایش</a> | <span class="text-danger" style="cursor: pointer;" onclick="if(confirm('آیا از حذف این درس مطمئن هستید؟ در اینصورت تمامی کلاس های مربوط به این درس حذف خواهند شد.')){document.getElementById('delete_lesson_{{ $lesson->id }}_form').submit();}">حذف</span></li>
                                            <form action="{{ route('delete.lesson', ['lesson' => $lesson->id]) }}" method="POST" id="delete_lesson_{{ $lesson->id }}_form">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="delete">
                                            </form>
                                        @endforeach
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="direction: rtl;">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-list"></i>
                                دبیران
                            </div>
                            <div class="card-body" style="direction: rtl;">
                                @if(isset($_GET['edit-teacher']) && !empty($_GET['edit-teacher']))
                                    <form action="{{ route('update.teacher', ['teacher' => $_GET['edit-teacher']]) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="put">
                                        <input type="text" name="name" class="form-control" placeholder="نام دبیر" value="{{ $teacher_row->name }}"><br>
                                        <input type="text" name="username" placeholder="نام کاربری" class="form-control" value="{{ $teacher_row->username }}"><br>
                                        <input type="password" name="new_password" placeholder="رمز عبور" class="form-control"><br>
                                        <input type="hidden" name="password" value="{{ $teacher_row->password }}"> {{-- TODO: delete this line --}}
                                        <button type="submit" class="btn btn-warning" style="border-radius: 3px; color: white;"><i class="fas fa-edit"></i> ویرایش دبیر</button>
                                    </form>
                                @else
                                    <form action="{{ route('insert.teacher') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="text" name="name" class="form-control" placeholder="نام دبیر" value="{{ old('name') }}">
                                        @if($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span><br>
                                        @endif
                                        <br>
                                        <input type="text" name="username" placeholder="نام کاربری" value="{{ old('username') }}" class="form-control">
                                        @if($errors->has('username'))
                                            <span class="text-danger">{{ $errors->first('username') }}</span><br>
                                        @endif
                                        <br>
                                        <input type="password" name="password" placeholder="رمز عبور" class="form-control">
                                        @if($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span><br>
                                        @endif
                                        <br>
                                        <button type="submit" class="btn btn-success" style="border-radius: 3px;"><i class="fas fa-plus"></i> افزودن دبیر</button>
                                    </form>
                                @endif
                                <br>
                                <ul>
                                    @foreach($teachers as $teacher)
                                        <li>{{ $teacher->name }} | <a href="{{ route('show.teacher.classes', ['teacher' => $teacher->id]) }}" class="text-primary">مشاهده لیست کلاس ها</a> | <a href="{{ route('admins.dashboard') }}?edit-teacher={{ $teacher->id }}" class="text-warning">ویرایش</a> | <span class="text-danger" style="cursor: pointer;">حذف</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

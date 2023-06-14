@extends('includes.dashboard_html_structure')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4" style="direction: rtl;">
                <h1 class="mt-4">داشبورد</h1><br>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body">Primary Card</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white mb-4">
                            <div class="card-body">Warning Card</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">Success Card</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-danger text-white mb-4">
                            <div class="card-body">Danger Card</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                            <input type="text" name="name" class="form-control" placeholder="نام کلاس" value="{{ $lesson_room_name }}" style="margin-left: 3px;" required>
                                            <button type="submit" class="btn btn-warning" style="border-radius: 3px; color: white;"><i class="fas fa-edit"></i></button>
                                        </div>
                                    </form>
                                @else
                                    <form action="{{ route('insert.lesson.room') }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control" placeholder="نام کلاس" style="margin-left: 3px;" required>
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
                                            <input type="text" name="name" class="form-control" placeholder="نام درس" value="{{ $lesson_name }}" style="margin-left: 3px;" required>
                                            <button type="submit" class="btn btn-warning" style="border-radius: 3px; color: white;"><i class="fas fa-edit"></i></button>
                                        </div>
                                    </form>
                                    @else
                                        <form action="{{ route('insert.lesson') }}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="input-group">
                                                <input type="text" name="name" class="form-control" placeholder="نام درس" style="margin-left: 3px;" required>
                                                <button type="submit" class="btn btn-success" style="border-radius: 3px;"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </form>
                                    @endif
                                    <br>
                                    <ul>
                                        @foreach($lessons as $lesson)
                                            <li>{{ $lesson->name }} | <a href="{{ route('admins.dashboard') }}?edit-lesson={{ $lesson->id }}" class="text-warning">ویرایش</a> | <span class="text-danger" style="cursor: pointer;">حذف</span></li>
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
                                        <input type="text" name="name" class="form-control" placeholder="نام دبیر"><br>
                                        <input type="text" name="username" placeholder="نام کاربری" class="form-control"><br>
                                        <input type="password" name="password" placeholder="رمز عبور" class="form-control"><br>
                                        <button type="submit" class="btn btn-success" style="border-radius: 3px;"><i class="fas fa-plus"></i> افزودن دبیر</button>
                                    </form>
                                @endif
                                <br>
                                <ul>
                                    @foreach($teachers as $teacher)
                                        <li>{{ $teacher->name }} | {{-- <a href="#" class="text-primary">مشاهده لیست دانش آموزان</a> | --}}<a href="{{ route('admins.dashboard') }}?edit-teacher={{ $teacher->id }}" class="text-warning">ویرایش</a> | <span class="text-danger" style="cursor: pointer;">حذف</span></li>
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

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
                                        <li>{{ $lesson_room->name }} | <a href="{{ route('admins.dashboard') }}?edit-lesson-room={{ $lesson_room->id }}" class="text-warning" style="text-decoration: none;">ویرایش</a> | <span class="text-danger" style="text-decoration: none; cursor: pointer;">حذف</span></li>
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
                                            <li>{{ $lesson->name }} | <a href="{{ route('admins.dashboard') }}?edit-lesson={{ $lesson->id }}" class="text-warning" style="text-decoration: none;">ویرایش</a> | <span class="text-danger" style="text-decoration: none; cursor: pointer;">حذف</span></li>
                                        @endforeach
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-list"></i>
                        کلاس ها
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @php $counter = 0; @endphp
                                @foreach($lesson_rooms as $lesson_room)
                                    <tr>
                                        <td>@php echo ++$counter; @endphp</td>
                                        <td>{{ $lesson_room['class_name'] }} - {{ $lesson_room['lesson_name'] }}</td>
                                        <td>
                                            <a href="{{ route('add.date', ['lesson_room' => $lesson_room['userable_id'], 'lesson' => $lesson_room['lesson_id']]) }}" class="btn btn-sm btn-success">افزودن تاریخ</a>
                                            <a href="{{ route('show.students.list', ['lesson_room' => $lesson_room['userable_id'], 'lesson' => $lesson_room['lesson_id']]) }}" class="btn btn-sm btn-primary">مشاهده لیست دانش آموزان</a>
                                        </td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

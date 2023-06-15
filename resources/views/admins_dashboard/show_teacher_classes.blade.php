@extends('includes.dashboard_html_structure')

@section('title')
مشاهده کلاس های: {{ $teacher->name }}
@endsection

@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h2 class="mt-4">مشاهده کلاس های: {{ $teacher->name }}</h2><br>
                @if(isset($_GET['edit-relation-ship']) && !empty($_GET['edit-relation-ship']))
                    <div class="card mb-4" style="direction: rtl;">
                        <div class="card-header">
                            <i class="fas fa-edit"></i>
                            ویرایش کلاس: {{ $relation_ship_information->lesson_room->name }} - {{ $relation_ship_information->lesson->name }}
                        </div>
                        <div class="card-body">
                            <form action="{{ route('update.relation.ship', ['relation_ship' => $_GET['edit-relation-ship']]) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="put">
                                <div class="form-floating">
                                    <select class="form-control" name="lesson_room" id="lesson_room">{{-- TODO: correct it --}}
                                        @foreach($lesson_rooms as $lesson_room)
                                            <option value="{{ $lesson_room->id }}" @if($lesson_room->id == $relation_ship_information->lesson_room->id) selected @endif>{{ $lesson_room->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="lesson_room" style="margin-left: 5px;">کلاس خود را انتخاب کنید</label>
                                </div><br>
                                <div class="form-floating">
                                    <select class="form-control" name="lesson" id="lesson">{{-- TODO: correct it --}}
                                        @foreach($lessons as $lesson)
                                            <option value="{{ $lesson->id }}" @if($lesson->id == $relation_ship_information->lesson->id) selected @endif>{{ $lesson->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="lesson">درس خود را انتخاب کنید</label>
                                </div><br>
                                <button type="submit" class="btn btn-warning" style="color: white;"><i class="fas fa-edit"></i> ویرایش کلاس</button>
                                <a href="{{ route('show.teacher.classes', ['teacher' => $teacher->id]) }}" class="btn btn-danger"><i class="fas fa-times"></i> لغو</a>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="card mb-4" style="direction: rtl;">
                        <div class="card-header">
                            <i class="fas fa-plus"></i>
                            افزودن کلاس برای دبیر
                        </div>
                        <div class="card-body">
                            <form action="{{ route('insert.relation.ship', ['teacher' => $teacher->id]) }}" method="POST">
                                {{ csrf_field() }}
                                <select class="form-control" name="lesson_room">
                                    <option value="">کلاس خود را انتخاب کنید</option>
                                    @foreach($lesson_rooms as $lesson_room)
                                        <option value="{{ $lesson_room->id }}">{{ $lesson_room->name }}</option>
                                    @endforeach
                                </select><br>
                                <select class="form-control" name="lesson">
                                    <option value="">درس خود را انتخاب کنید</option>
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
                                    @endforeach
                                </select><br>
                                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> افزودن کلاس</button>
                            </form>
                        </div>
                    </div>
                @endif
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
                                @php $counter = 0; @endphp
                                @foreach($classes as $class)
                                    <tr>
                                        <td>@php echo ++$counter; @endphp</td>
                                        <td>{{ $class['class_name'] }} - {{ $class['lesson_name'] }}</td>
                                        <td>
                                            <a href="{{ route('show.teacher.classes', ['teacher' => $teacher->id]) }}?edit-relation-ship={{ $class->id }}" class="btn btn-warning" style="color: white;">ویرایش</a>
                                            {{-- TODO: create delete button --}}
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

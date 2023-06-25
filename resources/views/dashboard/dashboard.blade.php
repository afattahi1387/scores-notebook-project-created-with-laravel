@extends('includes.dashboard_html_structure')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">داشبورد</h1><br>
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
                                @foreach($lesson_rooms as $lesson_room)
                                    <tr>
                                        <td>@php echo ++$counter; @endphp</td>
                                        <td>{{ $lesson_room['class_name'] }} - {{ $lesson_room['lesson_name'] }}</td>
                                        <td>
                                            <a href="{{ route('add.date', ['lesson_room' => $lesson_room['userable_id'], 'lesson' => $lesson_room['lesson_id']]) }}" class="btn btn-sm btn-success">افزودن تاریخ</a>
                                            <a href="{{ route('show.students.list', ['lesson_room' => $lesson_room['userable_id'], 'lesson' => $lesson_room['lesson_id']]) }}" class="btn btn-sm btn-primary">مشاهده لیست دانش آموزان</a>
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

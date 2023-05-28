@extends('includes.dashboard_html_structure')

@section('title')
نمایش لیست دانش آموزان کلاس: {{ $lesson_room->name }}
@endsection

@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h2 class="mt-4">نمایش لیست دانش آموزان کلاس: {{ $lesson_room->name }}</h2><br>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-graduation-cap"></i>
                        نمایش لیست دانش آموزان کلاس: {{ $lesson_room->name }}
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

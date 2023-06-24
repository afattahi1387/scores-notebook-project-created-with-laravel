@extends('includes.dashboard_html_structure')

@section('icon', 'add.jpg')

@section('title')
افزودن تاریخ برای: {{ $lesson_room->name }} - {{ $lesson->name }}
@endsection

@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">افزودن تاریخ برای: {{ $lesson_room->name }} - {{ $lesson->name }}</h1><br>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-plus"></i>
                        افزودن تاریخ برای: {{ $lesson_room->name }} - {{ $lesson->name }}
                    </div>
                    <div class="card-body" style="direction: rtl;">
                        <form action="{{ route('insert.date', ['lesson_room' => $lesson_room->id, 'lesson' => $lesson->id]) }}" method="POST">
                            {{ csrf_field() }}
                            <label for="day_number">تاریخ:</label>
                            <input type="number" name="day_number" id="day_number" placeholder="روز">
                            <b>/</b>
                            <input type="number" name="month_number" id="month_number" placeholder="ماه">
                            <b>/</b>
                            <input type="number" name="year_number" id="year_number" placeholder="سال">
                            <br><br>
                            <select name="term" class="form-control">
                                <option value="">ترم خود را وارد کنید</option>
                                <option value="first">نوبت اول</option>
                                <option value="second">نوبت دوم</option>
                            </select>
                            
                            @foreach ($lesson_room_learners as $learner)
                                <hr>
                                <div class="alert alert-primary" role="alert">
                                    {{ $learner->name }}
                                </div>
                                <label><input type="radio" name="roll_call_{{ $learner->id }}" value="present"> حاضر</label><br>
                                <label><input type="radio" name="roll_call_{{ $learner->id }}" id="absent" value="absent"> غایب</label><br><br>
                                <b>در صورت حاضر بودن، می توانید اطلاعات زیر را نیز پر کنید (اختیاری):</b><br><br>
                                <input type="number" name="score_{{ $learner->id }}" placeholder="نمره" class="form-control"><br><br>
                                <input type="number" name="PN_{{ $learner->id }}" placeholder="مثبت یا منفی" class="form-control"><br><br>
                                <textarea name="description_{{ $learner->id }}" id="description_{{ $learner->id }}" rows="10" placeholder="توضیحات" class="form-control"></textarea><br>
                            @endforeach
                            <br><input type="submit" value="افزودن لیست حضور و غیاب" class="btn btn-success">
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    @foreach ($lesson_room_learners as $learner)
        <script>
            tinymce.init({
                selector: '#description_{{ $learner->id }}',
                directionality: 'rtl',
                plugins: [
                'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
                'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
                'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
                ],
                toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
            });
        </script>
    @endforeach
@endsection

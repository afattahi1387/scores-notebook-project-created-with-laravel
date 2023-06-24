@extends('includes.dashboard_html_structure')

@section('icon', 'settings.png')

@section('title', 'تنظیمات')

@section('content')
    <div id="layoutSidenav_content" style="direction: rtl;">
        <main>
            <div class="container-fluid px-4">
                <br>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-cog"></i>
                        <b>تنظیمات</b>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('set.settings') }}" method="POST">
                            {{ csrf_field() }}
                            <label>
                                <input type="checkbox" name="no_LOCP" onclick="if(this.checked == true){document.getElementById('LOCP').style.display = 'none';}else{document.getElementById('LOCP').style.display = 'block';}"> نمره ای برای موارد مثبت اضافه نشود.
                            </label><br><br>
                            <div class="form-floating" id="LOCP">
                                <input type="number" name="LOCP" class="form-control">
                                <label for="LOCP">میزان نمره افزوده برای هر مثبت</label>
                            </div><hr>

                            <label>
                                <input type="checkbox" name="no_LOCN" onclick="if(this.checked == true){document.getElementById('LOCN').style.display = 'none';}else{document.getElementById('LOCN').style.display = 'block';}"> نمره ای برای موارد منفی کم نشود.
                            </label><br><br>
                            <div class="form-floating" id="LOCN">
                                <input type="number" name="LOCN" class="form-control">
                                <label for="LOCN">میزان نمره کاسته برای هر منفی</label>
                            </div><hr>
                            
                            <label>
                                <input type="checkbox" name="no_LOCA" onclick="if(this.checked == true){document.getElementById('LOCA').style.display = 'none';}else{document.getElementById('LOCA').style.display = 'block';}"> نمره ای برای غیبت ها کم نشود.
                            </label><br><br>
                            <div class="form-floating" id="LOCA">
                                <input type="number" name="LOCA" class="form-control">
                                <label for="LOCA">میزان نمره کاسته برای هر غیبت</label>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-warning" style="color: white;"><i class="fas fa-edit"></i>ویرایش موارد تنظیم شده</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection

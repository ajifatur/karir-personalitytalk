@extends('template/admin/main')

@section('content')

<!-- Page Heading -->
<div class="page-heading shadow d-flex justify-content-between align-items-center">
  <h1 class="h3 text-gray-800">Data Hasil Tes</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
    <li class="breadcrumb-item"><a href="/admin/hasil">Hasil Tes</a></li>
    <li class="breadcrumb-item active" aria-current="page">Papikostick</li>
  </ol>
</div>

<div class="row mb-4">
  <div class="col-xl-3 mb-3">
      <div class="card shadow">
          <div class="card-body">
              <div class="row">
                  <div class="col-md-6 col-xl-12">
                      <p><b>Nama:</b><br>{{ $user->nama_user }} {{ $user->role == 6 ? '('.$user->email.')' : '' }}</p>
                  </div>
                  @if($user->role != 6)
                  <div class="col-md-6 col-xl-12">
                      <p><b>Usia:</b><br>{{ $user->role != 6 ? generate_age($user->tanggal_lahir, $hasil->created_at).' tahun' : '-' }}</p>
                  </div>
                  <div class="col-md-6 col-xl-12">
                      <p><b>Jenis Kelamin:</b><br>{{ $user->role != 6 ? $user->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' : '-' }}</p>
                  </div>
                  @endif
                  <div class="col-md-6 col-xl-12">
                      <p><b>Posisi:</b><br>{{ $user->role != 6 ? !empty($user_desc) ? $user_desc->nama_posisi : $role->nama_role : $posisi_magang }}</p>
                  </div>
                  <div class="col-md-6 col-xl-12">
                      <p><b>Role:</b><br>{{ $role->nama_role }}</p>
                  </div>
                  <div class="col-md-6 col-xl-12">
                      <p><b>Tes:</b><br>{{ $hasil->nama_tes }}</p>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="col-xl-9">
    <!-- Card -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <div></div>
        <div>
            <a class="btn btn-sm btn-primary btn-print" href="#">
              <i class="fas fa-print fa-sm fa-fw text-gray-400"></i> Cetak
            </a>
        </div>
      </div>
        <div class="card-body">
          <form id="form" class="d-none" method="post" action="/admin/hasil/print" target="_blank">
              {{ csrf_field() }}
              <input type="hidden" name="id_hasil" value="{{ $hasil->id_hasil }}">
              <input type="hidden" name="nama" value="{{ $user->nama_user }}">
              <input type="hidden" name="usia" value="{{ generate_age($user->tanggal_lahir, $hasil->created_at).' tahun' }}">
              <input type="hidden" name="jenis_kelamin" value="{{ $user->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}">
              <input type="hidden" name="posisi" value="{{ !empty($user_desc) ? $user_desc->nama_posisi.' ('.$role->nama_role.')' : $role->nama_role }}">
              <input type="hidden" name="tes" value="{{ $hasil->nama_tes }}">
              <input type="hidden" name="path" value="{{ $hasil->path }}">
              <input type="hidden" name="image" id="image">
          </form>
          <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="grafik-tab" data-toggle="tab" href="#grafik" role="tab" aria-controls="grafik" aria-selected="true">Grafik</a>
              </li>
              <li class="nav-item" role="presentation">
                  <a class="nav-link" id="deskripsi-tab" data-toggle="tab" href="#deskripsi" role="tab" aria-controls="deskripsi" aria-selected="false">Deskripsi</a>
              </li>
          </ul>
          <div class="tab-content py-4" id="myTabContent">
              <div class="tab-pane fade show active" id="grafik" role="tabpanel" aria-labelledby="grafik-tab">
                <div class="row">
                  <div class="col-auto mx-auto mb-3">
                      <div class="table-responsive">
                        <img id="scream" src="{{ asset('assets/images/tes/papi-kostick.png') }}" style="display: none;">
                        <canvas id="myCanvas" width="576" height="576" style="border:1px solid #bebebe;"></canvas>
                      </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="deskripsi" role="tabpanel" aria-labelledby="deskripsi-tab">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr>
                                <th width="50">No.</th>
                                <th width="80">Huruf</th>
                                <th width="80">Skor</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                          @php
                            $data = array();
                          @endphp
                          
                          @foreach($huruf as $key=>$h)
                            <tr>
                                <td>{{ $key+1 }}.</td>
                                <td>{{ $h }}</td>
                                <td>{{ $hasil->hasil[$h] }}</td>
                                <td style="text-align: left">{{ analisisPapikostick($hasil->hasil[$h], $keterangan->keterangan[$h]) }}</td>
                                @php array_push($data, array('letter' => $h, 'number' => $hasil->hasil[$h])); @endphp
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
              </div>
          </div>
        </div>
    </div>
  </div>
</div>
  
@endsection

@section('css-extra')

<style type="text/css">
  table tr th, table tr td {padding: .25rem .5rem; text-align: center;}
  .table-identity {min-width: 1000px};
  .h6.mt-4 {text-align: justify!important;}
</style>

@endsection

@section('js-extra')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
<script type="text/javascript">

var canvas;

window.onload = function() {
    canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");

    // Get image from <img>
    var img = document.getElementById("scream");

    // Render image to canvas
    ctx.drawImage(img,10,10);

    // Set line width and set coordinates
    ctx.lineWidth = 1.5;
    var coords = [
        {"letter" : "N", "number" : 0, "X" : 275, "Y" : 257},
        {"letter" : "N", "number" : 1, "X" : 271, "Y" : 244},
        {"letter" : "N", "number" : 2, "X" : 267, "Y" : 229},
        {"letter" : "N", "number" : 3, "X" : 263, "Y" : 217},
        {"letter" : "N", "number" : 4, "X" : 258, "Y" : 203},
        {"letter" : "N", "number" : 5, "X" : 254, "Y" : 190},
        {"letter" : "N", "number" : 6, "X" : 250, "Y" : 178},
        {"letter" : "N", "number" : 7, "X" : 246, "Y" : 163},
        {"letter" : "N", "number" : 8, "X" : 242, "Y" : 151},
        {"letter" : "N", "number" : 9, "X" : 239, "Y" : 137},

        {"letter" : "G", "number" : 0, "X" : 285, "Y" : 255},
        {"letter" : "G", "number" : 1, "X" : 285, "Y" : 241},
        {"letter" : "G", "number" : 2, "X" : 286, "Y" : 226},
        {"letter" : "G", "number" : 3, "X" : 285, "Y" : 213},
        {"letter" : "G", "number" : 4, "X" : 286, "Y" : 199},
        {"letter" : "G", "number" : 5, "X" : 287, "Y" : 187},
        {"letter" : "G", "number" : 6, "X" : 287, "Y" : 172},
        {"letter" : "G", "number" : 7, "X" : 287, "Y" : 158},
        {"letter" : "G", "number" : 8, "X" : 286, "Y" : 144},
        {"letter" : "G", "number" : 9, "X" : 287, "Y" : 129},

        {"letter" : "A", "number" : 0, "X" : 297, "Y" : 257},
        {"letter" : "A", "number" : 1, "X" : 301, "Y" : 243},
        {"letter" : "A", "number" : 2, "X" : 306, "Y" : 230},
        {"letter" : "A", "number" : 3, "X" : 311, "Y" : 217},
        {"letter" : "A", "number" : 4, "X" : 315, "Y" : 204},
        {"letter" : "A", "number" : 5, "X" : 320, "Y" : 192},
        {"letter" : "A", "number" : 6, "X" : 325, "Y" : 179},
        {"letter" : "A", "number" : 7, "X" : 330, "Y" : 165},
        {"letter" : "A", "number" : 8, "X" : 334, "Y" : 152},
        {"letter" : "A", "number" : 9, "X" : 338, "Y" : 139},

        {"letter" : "L", "number" : 0, "X" : 306, "Y" : 262},
        {"letter" : "L", "number" : 1, "X" : 315, "Y" : 250},
        {"letter" : "L", "number" : 2, "X" : 323, "Y" : 240},
        {"letter" : "L", "number" : 3, "X" : 332, "Y" : 228},
        {"letter" : "L", "number" : 4, "X" : 340, "Y" : 218},
        {"letter" : "L", "number" : 5, "X" : 347, "Y" : 206},
        {"letter" : "L", "number" : 6, "X" : 356, "Y" : 197},
        {"letter" : "L", "number" : 7, "X" : 367, "Y" : 184},
        {"letter" : "L", "number" : 8, "X" : 374, "Y" : 175},
        {"letter" : "L", "number" : 9, "X" : 383, "Y" : 163},

        {"letter" : "P", "number" : 0, "X" : 314, "Y" : 270},
        {"letter" : "P", "number" : 1, "X" : 325, "Y" : 262},
        {"letter" : "P", "number" : 2, "X" : 336, "Y" : 254},
        {"letter" : "P", "number" : 3, "X" : 348, "Y" : 245},
        {"letter" : "P", "number" : 4, "X" : 359, "Y" : 239},
        {"letter" : "P", "number" : 5, "X" : 371, "Y" : 231},
        {"letter" : "P", "number" : 6, "X" : 382, "Y" : 223},
        {"letter" : "P", "number" : 7, "X" : 393, "Y" : 214},
        {"letter" : "P", "number" : 8, "X" : 405, "Y" : 207},
        {"letter" : "P", "number" : 9, "X" : 417, "Y" : 198},

        {"letter" : "I", "number" : 0, "X" : 319, "Y" : 280},
        {"letter" : "I", "number" : 1, "X" : 331, "Y" : 275},
        {"letter" : "I", "number" : 2, "X" : 344, "Y" : 271},
        {"letter" : "I", "number" : 3, "X" : 358, "Y" : 268},
        {"letter" : "I", "number" : 4, "X" : 371, "Y" : 265},
        {"letter" : "I", "number" : 5, "X" : 385, "Y" : 259},
        {"letter" : "I", "number" : 6, "X" : 398, "Y" : 257},
        {"letter" : "I", "number" : 7, "X" : 411, "Y" : 252},
        {"letter" : "I", "number" : 8, "X" : 424, "Y" : 248},
        {"letter" : "I", "number" : 9, "X" : 438, "Y" : 245},

        {"letter" : "T", "number" : 0, "X" : 320, "Y" : 289},
        {"letter" : "T", "number" : 1, "X" : 333, "Y" : 289},
        {"letter" : "T", "number" : 2, "X" : 347, "Y" : 290},
        {"letter" : "T", "number" : 3, "X" : 363, "Y" : 290},
        {"letter" : "T", "number" : 4, "X" : 375, "Y" : 290},
        {"letter" : "T", "number" : 5, "X" : 389, "Y" : 290},
        {"letter" : "T", "number" : 6, "X" : 401, "Y" : 291},
        {"letter" : "T", "number" : 7, "X" : 417, "Y" : 290},
        {"letter" : "T", "number" : 8, "X" : 431, "Y" : 291},
        {"letter" : "T", "number" : 9, "X" : 445, "Y" : 292},

        {"letter" : "V", "number" : 0, "X" : 318, "Y" : 300},
        {"letter" : "V", "number" : 1, "X" : 330, "Y" : 305},
        {"letter" : "V", "number" : 2, "X" : 343, "Y" : 309},
        {"letter" : "V", "number" : 3, "X" : 357, "Y" : 314},
        {"letter" : "V", "number" : 4, "X" : 369, "Y" : 319},
        {"letter" : "V", "number" : 5, "X" : 383, "Y" : 323},
        {"letter" : "V", "number" : 6, "X" : 395, "Y" : 328},
        {"letter" : "V", "number" : 7, "X" : 410, "Y" : 332},
        {"letter" : "V", "number" : 8, "X" : 422, "Y" : 337},
        {"letter" : "V", "number" : 9, "X" : 436, "Y" : 342},

        {"letter" : "X", "number" : 0, "X" : 312, "Y" : 310},
        {"letter" : "X", "number" : 1, "X" : 323, "Y" : 319},
        {"letter" : "X", "number" : 2, "X" : 334, "Y" : 327},
        {"letter" : "X", "number" : 3, "X" : 347, "Y" : 335},
        {"letter" : "X", "number" : 4, "X" : 357, "Y" : 344},
        {"letter" : "X", "number" : 5, "X" : 369, "Y" : 352},
        {"letter" : "X", "number" : 6, "X" : 378, "Y" : 361},
        {"letter" : "X", "number" : 7, "X" : 390, "Y" : 370},
        {"letter" : "X", "number" : 8, "X" : 401, "Y" : 379},
        {"letter" : "X", "number" : 9, "X" : 413, "Y" : 387},

        {"letter" : "S", "number" : 0, "X" : 305, "Y" : 320},
        {"letter" : "S", "number" : 1, "X" : 313, "Y" : 331},
        {"letter" : "S", "number" : 2, "X" : 321, "Y" : 341},
        {"letter" : "S", "number" : 3, "X" : 329, "Y" : 352},
        {"letter" : "S", "number" : 4, "X" : 337, "Y" : 364},
        {"letter" : "S", "number" : 5, "X" : 346, "Y" : 376},
        {"letter" : "S", "number" : 6, "X" : 352, "Y" : 386},
        {"letter" : "S", "number" : 7, "X" : 361, "Y" : 398},
        {"letter" : "S", "number" : 8, "X" : 369, "Y" : 409},
        {"letter" : "S", "number" : 9, "X" : 375, "Y" : 420},

        {"letter" : "B", "number" : 0, "X" : 295, "Y" : 323},
        {"letter" : "B", "number" : 1, "X" : 299, "Y" : 337},
        {"letter" : "B", "number" : 2, "X" : 303, "Y" : 351},
        {"letter" : "B", "number" : 3, "X" : 307, "Y" : 363},
        {"letter" : "B", "number" : 4, "X" : 311, "Y" : 377},
        {"letter" : "B", "number" : 5, "X" : 315, "Y" : 389},
        {"letter" : "B", "number" : 6, "X" : 318, "Y" : 401},
        {"letter" : "B", "number" : 7, "X" : 323, "Y" : 416},
        {"letter" : "B", "number" : 8, "X" : 326, "Y" : 430},
        {"letter" : "B", "number" : 9, "X" : 331, "Y" : 444},

        {"letter" : "O", "number" : 0, "X" : 283, "Y" : 325},
        {"letter" : "O", "number" : 1, "X" : 283, "Y" : 339},
        {"letter" : "O", "number" : 2, "X" : 283, "Y" : 352},
        {"letter" : "O", "number" : 3, "X" : 282, "Y" : 367},
        {"letter" : "O", "number" : 4, "X" : 282, "Y" : 381},
        {"letter" : "O", "number" : 5, "X" : 282, "Y" : 394},
        {"letter" : "O", "number" : 6, "X" : 282, "Y" : 407},
        {"letter" : "O", "number" : 7, "X" : 281, "Y" : 422},
        {"letter" : "O", "number" : 8, "X" : 280, "Y" : 436},
        {"letter" : "O", "number" : 9, "X" : 280, "Y" : 450},

        {"letter" : "R", "number" : 0, "X" : 274, "Y" : 322},
        {"letter" : "R", "number" : 1, "X" : 270, "Y" : 336},
        {"letter" : "R", "number" : 2, "X" : 266, "Y" : 349},
        {"letter" : "R", "number" : 3, "X" : 261, "Y" : 363},
        {"letter" : "R", "number" : 4, "X" : 257, "Y" : 376},
        {"letter" : "R", "number" : 5, "X" : 253, "Y" : 389},
        {"letter" : "R", "number" : 6, "X" : 249, "Y" : 402},
        {"letter" : "R", "number" : 7, "X" : 244, "Y" : 416},
        {"letter" : "R", "number" : 8, "X" : 240, "Y" : 427},
        {"letter" : "R", "number" : 9, "X" : 236, "Y" : 442},

        {"letter" : "D", "number" : 0, "X" : 263, "Y" : 317},
        {"letter" : "D", "number" : 1, "X" : 255, "Y" : 329},
        {"letter" : "D", "number" : 2, "X" : 248, "Y" : 340},
        {"letter" : "D", "number" : 3, "X" : 239, "Y" : 351},
        {"letter" : "D", "number" : 4, "X" : 231, "Y" : 362},
        {"letter" : "D", "number" : 5, "X" : 222, "Y" : 372},
        {"letter" : "D", "number" : 6, "X" : 213, "Y" : 383},
        {"letter" : "D", "number" : 7, "X" : 203, "Y" : 395},
        {"letter" : "D", "number" : 8, "X" : 196, "Y" : 405},
        {"letter" : "D", "number" : 9, "X" : 187, "Y" : 417},

        {"letter" : "C", "number" : 0, "X" : 256, "Y" : 310},
        {"letter" : "C", "number" : 1, "X" : 244, "Y" : 318},
        {"letter" : "C", "number" : 2, "X" : 233, "Y" : 326},
        {"letter" : "C", "number" : 3, "X" : 222, "Y" : 334},
        {"letter" : "C", "number" : 4, "X" : 210, "Y" : 342},
        {"letter" : "C", "number" : 5, "X" : 199, "Y" : 350},
        {"letter" : "C", "number" : 6, "X" : 188, "Y" : 358},
        {"letter" : "C", "number" : 7, "X" : 176, "Y" : 366},
        {"letter" : "C", "number" : 8, "X" : 165, "Y" : 374},
        {"letter" : "C", "number" : 9, "X" : 153, "Y" : 382},

        {"letter" : "Z", "number" : 0, "X" : 132, "Y" : 337},
        {"letter" : "Z", "number" : 1, "X" : 145, "Y" : 333},
        {"letter" : "Z", "number" : 2, "X" : 158, "Y" : 328},
        {"letter" : "Z", "number" : 3, "X" : 171, "Y" : 325},
        {"letter" : "Z", "number" : 4, "X" : 184, "Y" : 321},
        {"letter" : "Z", "number" : 5, "X" : 198, "Y" : 317},
        {"letter" : "Z", "number" : 6, "X" : 211, "Y" : 312},
        {"letter" : "Z", "number" : 7, "X" : 224, "Y" : 309},
        {"letter" : "Z", "number" : 8, "X" : 237, "Y" : 304},
        {"letter" : "Z", "number" : 9, "X" : 250, "Y" : 300},

        {"letter" : "E", "number" : 0, "X" : 249, "Y" : 290},
        {"letter" : "E", "number" : 1, "X" : 236, "Y" : 289},
        {"letter" : "E", "number" : 2, "X" : 222, "Y" : 289},
        {"letter" : "E", "number" : 3, "X" : 208, "Y" : 288},
        {"letter" : "E", "number" : 4, "X" : 194, "Y" : 288},
        {"letter" : "E", "number" : 5, "X" : 180, "Y" : 288},
        {"letter" : "E", "number" : 6, "X" : 167, "Y" : 288},
        {"letter" : "E", "number" : 7, "X" : 152, "Y" : 287},
        {"letter" : "E", "number" : 8, "X" : 138, "Y" : 287},
        {"letter" : "E", "number" : 9, "X" : 125, "Y" : 287},

        {"letter" : "K", "number" : 0, "X" : 134, "Y" : 238},
        {"letter" : "K", "number" : 1, "X" : 146, "Y" : 242},
        {"letter" : "K", "number" : 2, "X" : 160, "Y" : 247},
        {"letter" : "K", "number" : 3, "X" : 173, "Y" : 252},
        {"letter" : "K", "number" : 4, "X" : 186, "Y" : 256},
        {"letter" : "K", "number" : 5, "X" : 199, "Y" : 261},
        {"letter" : "K", "number" : 6, "X" : 213, "Y" : 265},
        {"letter" : "K", "number" : 7, "X" : 225, "Y" : 270},
        {"letter" : "K", "number" : 8, "X" : 239, "Y" : 275},
        {"letter" : "K", "number" : 9, "X" : 252, "Y" : 279},

        {"letter" : "F", "number" : 0, "X" : 257, "Y" : 270},
        {"letter" : "F", "number" : 1, "X" : 246, "Y" : 261},
        {"letter" : "F", "number" : 2, "X" : 235, "Y" : 253},
        {"letter" : "F", "number" : 3, "X" : 224, "Y" : 245},
        {"letter" : "F", "number" : 4, "X" : 213, "Y" : 236},
        {"letter" : "F", "number" : 5, "X" : 202, "Y" : 228},
        {"letter" : "F", "number" : 6, "X" : 190, "Y" : 220},
        {"letter" : "F", "number" : 7, "X" : 178, "Y" : 211},
        {"letter" : "F", "number" : 8, "X" : 168, "Y" : 203},
        {"letter" : "F", "number" : 9, "X" : 157, "Y" : 195},

        {"letter" : "W", "number" : 0, "X" : 265, "Y" : 262},
        {"letter" : "W", "number" : 1, "X" : 256, "Y" : 250},
        {"letter" : "W", "number" : 2, "X" : 248, "Y" : 239},
        {"letter" : "W", "number" : 3, "X" : 240, "Y" : 227},
        {"letter" : "W", "number" : 4, "X" : 232, "Y" : 217},
        {"letter" : "W", "number" : 5, "X" : 224, "Y" : 205},
        {"letter" : "W", "number" : 6, "X" : 216, "Y" : 195},
        {"letter" : "W", "number" : 7, "X" : 208, "Y" : 183},
        {"letter" : "W", "number" : 8, "X" : 200, "Y" : 172},
        {"letter" : "W", "number" : 9, "X" : 192, "Y" : 160},
    ]

    // Results
    var results = JSON.parse($("#json").text());

    /**
     * Draw lines here
     */
    var lastCoords = [];
    ctx.beginPath();
    // Loop results
    for(var i = 0; i < results.length; i++){
        // Loop coordinates
        for(var j = 0; j < coords.length; j++){
            // Check similarity from elements "letter" and "number" from coordinates and results
            if(coords[j].letter == results[i].letter && coords[j].number == results[i].number){
                if(i <= 0) ctx.moveTo(coords[j].X, coords[j].Y);
                else ctx.lineTo(coords[j].X, coords[j].Y);
                if(i <= 0) lastCoords.push([coords[j].X, coords[j].Y]);
            }
        }
    }
    ctx.lineTo(lastCoords[0][0], lastCoords[0][1]);
    ctx.stroke();

    /**
     * Draw circles here
     */
    // Loop results
    for(var i = 0; i < results.length; i++){
        // Loop coordinates
        for(var j = 0; j < coords.length; j++){
            // Check similarity from elements "letter" and "number" from coordinates and results
            if(coords[j].letter == results[i].letter && coords[j].number == results[i].number){
                ctx.beginPath();
                ctx.arc(coords[j].X, coords[j].Y, 6, 0, 2*Math.PI);
                ctx.stroke();
            }
        }
    }
    
    generateImage();

    // // Report the mouse position on click
    // canvas.addEventListener("click", function (evt) {
    //     var mousePos = getMousePos(canvas, evt);
    //     alert(mousePos.x + ',' + mousePos.y);
    // }, false);

    // // Get Mouse Position
    // function getMousePos(canvas, evt) {
    //     var rect = canvas.getBoundingClientRect();
    //     return {
    //         x: evt.clientX - rect.left,
    //         y: evt.clientY - rect.top
    //     };
    // }
}

// Generate image to base 64 code
function generateImage(){
    var url = canvas.toDataURL();
    document.getElementById("image").value = url;
}

$(document).on("click", ".btn-print", function(e){
  e.preventDefault();
  $("#form")[0].submit();
});
</script>

@endsection
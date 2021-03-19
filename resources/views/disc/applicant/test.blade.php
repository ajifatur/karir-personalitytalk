@extends('template/applicant/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">DISC Test</h1>
  <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">DISC Test</h6>
    </div>
    <div class="card-body">
      @if(Session::get('message') != null)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ Session::get('message') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      @if(!$disc)
      <form method="post" action="/applicant/disc/store">
        {{ csrf_field() }}
        <div class="container-fluid">
          <div class="row">
            @foreach($soal as $data)
            <div class="col-lg-6">
              <table class="table table-borderless bg-white border border-muted shadow-sm">
                <thead>
                  <tr>
                    <th width="50"></th>
                    <th width="50"><i class="far fa-smile text-success"></i></th>
                    <th width="50"><i class="far fa-frown text-danger"></i></th>
                    <th>Karakteristik</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data->soal[0]['pilihan'] as $key=>$pilihan)
                  <tr>
                    @if($key == 'A')
                    <td rowspan="4" class="font-weight-bold num" data-id="{{ $data->nomor }}">{{ $data->nomor }}</td>
                    @endif
                    <td><input type="radio" name="m[{{ $data->nomor }}]" class="{{ $data->nomor }}-m" value="{{ $key }}"></td>
                    <td><input type="radio" name="l[{{ $data->nomor }}]" class="{{ $data->nomor }}-l" value="{{ $key }}"></td>
                    <td>{{ $pilihan }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endforeach
          </div>
        </div>
      </form>
      @else
      <div class="text-center">
        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="{{ asset('templates/sb-admin-2/img/undraw_team_goals.svg') }}" alt="">
      </div>
      <p class="text-center h5 mt-4">Anda sudah melakukan tes DISC</p>
      @endif
    </div>
  </div>

  @if(!$disc)
  <nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-light border-top">
    <ul class="navbar nav ml-auto">
      <li class="nav-item">
        <span id="answered">0</span>/<span id="total"></span> Soal Terjawab
      </li>
      <li class="nav-item ml-3">
        <a href="#" class="text-secondary" data-toggle="modal" data-target="#tutorialModal"><i class="fa fa-question-circle" style="font-size: 1.5rem"></i></a>
      </li>
      <li class="nav-item ml-3">
        <button class="btn btn-md btn-primary text-uppercase rounded-0" id="btn-submit" disabled>Submit</button>
      </li>
    </ul>
  </nav>
  <div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Tutorial DISC Test</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              {!! html_entity_decode($tutorial->tutorial) !!}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary text-uppercase rounded-0" data-dismiss="modal">Mengerti</button>
            </div>
        </div>
      </div>
  </div>
  @endif
  
@endsection

@section('css-extra')

<style type="text/css">
  .modal .modal-body {font-size: 14px;}
</style>

@endsection

@section('js-extra')

@if(!$disc)
<script type="text/javascript">
  // vertical align modal
  $(document).ready(function(){
    // Show modal when the page is loaded
    $("#tutorialModal").modal("toggle");

      function alignModal(){
          var modalDialog = $(this).find(".modal-dialog");
          
          // Applying the top margin on modal dialog to align it vertically center
          modalDialog.css("margin-top", Math.max(0, ($(window).height() - modalDialog.height()) / 2));
      }
      // Align modal when it is displayed
      $(".modal").on("shown.bs.modal", alignModal);
      
      // Align modal when user resize the window
      $(window).on("resize", function(){
          $(".modal:visible").each(alignModal);
      });

      totalQuestion();
  });

  // Change value
  $(document).on("change", "input[type=radio]", function(){
    var className = $(this).attr("class");
    var currentNumber = className.split("-")[0];
    var currentCode = className.split("-")[1];
    var oppositeCode = currentCode == "m" ? "l" : "m";
    var currentValue = $(this).val();
    var oppositeValue = $("." + currentNumber + "-" + oppositeCode + ":checked").val();

    // Detect if one question has same answer
    if(currentValue == oppositeValue){
      $("." + currentNumber + "-" + oppositeCode + ":checked").prop("checked", false);
      oppositeValue = $("." + currentNumber + "-" + oppositeCode + ":checked").val();
    }

    // Count answered question
    countAnswered();

    // Enable submit button
    countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
  });

  // Count answered question
  function countAnswered(){
    var total = 0;
    $(".num").each(function(key, elem){
      var id = $(elem).data("id");
      var mValue = $("." + id + "-m:checked").val();
      var lValue = $("." + id + "-l:checked").val();
      mValue != undefined && lValue != undefined ? total++ : "";
    });
    $("#answered").text(total);
    return total;
  }

  // Total question
  function totalQuestion(){
    var totalRadio = $("input[type=radio]").length;
    var pointPerQuestion = 4;
    var total = totalRadio / pointPerQuestion / 2;
    $("#total").text(total);
    return total;
  }

  // Submit form
  $(document).on("click", "#btn-submit", function(e){
    e.preventDefault();
    $("form")[0].submit();
  })
</script>
@endif

@endsection
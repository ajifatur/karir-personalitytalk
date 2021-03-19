<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
	<title>DISC Test</title>
</head>
<body>
	<div class="container-fluid mt-5" style="margin-bottom: 120px;">
		<div class="card bg-light">
			<div class="card-title py-3">
				<h1 class="text-center">DISC TEST</h1>
			</div>
			<div class="card-body">
				<form method="post" action="/disc/test/post">
					{{ csrf_field() }}
					<input type="hidden" name="nama" value="{{ $tester->nama }}">
					<input type="hidden" name="usia" value="{{ $tester->usia }}">
					<input type="hidden" name="email" value="{{ $tester->email }}">
					<input type="hidden" name="gender" value="{{ $tester->gender }}">
					<div class="container-fluid">
						<div class="row">
							@foreach($soal as $data)
							<div class="col-lg-6">
								<table class="table table-borderless bg-white shadow-sm">
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
			</div>
		</div>
	</div>
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
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
</style>
</html>
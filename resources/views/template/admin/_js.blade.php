  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('templates/sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('templates/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ asset('templates/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ asset('templates/sb-admin-2/js/sb-admin-2.min.js') }}"></script>
  <script src="{{ asset('templates/sb-admin-2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('templates/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

  <!-- JavaScripts -->
  <script type="text/javascript">
    $(function(){
      // Tooltip
      $('[data-toggle="tooltip"]').tooltip();
    });

    // Button Delete
    $(document).on("click", ".btn-delete", function(e){
        e.preventDefault();
        var id = $(this).data("id");
        var ask = confirm("Anda yakin ingin menghapus data ini?");
        if(ask){
            $("#form-delete input[name=id]").val(id);
            $("#form-delete").submit();
        }
    });
    
    // Button toggle password
    $(document).on("click", ".btn-toggle-password", function(e){
        e.preventDefault();
        if(!$(this).hasClass("show")){
            $(this).parents(".input-group").find("input").attr("type","text");
            $(this).find(".fa").removeClass("fa-eye").addClass("fa-eye-slash");
            $(this).addClass("show");
        }
        else{
            $(this).parents(".input-group").find("input").attr("type","password");
            $(this).find(".fa").removeClass("fa-eye-slash").addClass("fa-eye");
            $(this).removeClass("show");
        }
    });

    // Generate dataTable
    function generate_datatable(table, server_side = false, data = []){
      var config_lang = {
        "lengthMenu": "Menampilkan _MENU_ data",
        "zeroRecords": "Data tidak tersedia",
        "info": "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
        "infoEmpty": "Data tidak ditemukan",
        "infoFiltered": "(Terfilter dari total _MAX_ data)",
        "search": "Cari:",
        "paginate": {
          "first": "Pertama",
          "last": "Terakhir",
          "previous": "<",
          "next": ">",
        },
        "processing": "Memproses data..."
      };

      if(server_side == false){
        var datatable = $(table).DataTable({
          "language": config_lang,
          columnDefs: [
            {orderable: false, targets: 0},
            {orderable: false, targets: -1},
          ],
          order: []
        });
      }
      else{
        var datatable = $(table).DataTable({
          processing: true,
          serverSide: true,
          ajax: data.url,
          columns: data.columns,
          columnDefs: [
            {orderable: false, targets: 0}
          ],
          order: [data.order],
          "lengthMenu": [[10, 50, 100, 200], [10, 50, 100, 200]],
          "language": config_lang,
        });
      }
      datatable.on('draw.dt', function(){
          $('[data-toggle="tooltip"]').tooltip();
          $(table).addClass("ssp");
      });
      return datatable;
    }

    // Generate JSON url
    function generate_json_url(url){
      url = url.replace(/&amp;/g, "&");
      return url;
    }
  </script>
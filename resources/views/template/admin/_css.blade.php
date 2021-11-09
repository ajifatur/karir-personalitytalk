<style type="text/css">
    /* Sidebar */
    .sidebar-toggled .sidebar-brand-icon {display: block;}
    @media(min-width: 768px){
        .sidebar-brand-icon {display: none;}
        .sidebar .nav-item .nav-link {padding: .5rem 1rem;}
        .sidebar.toggled .nav-item .nav-link span {margin-top: 0; margin-bottom: .25rem;}
    }
    .sidebar .sidebar-heading {padding: .5rem 1rem;}
    .sidebar .nav-item.active {background-color: #ebecef;}
    .sidebar .nav-item.active .nav-link {color: #333;}
    .sidebar .nav-item.active .nav-link i {color: #333;}
    .sidebar .nav-item.active .nav-link[data-toggle=collapse]::after {color: #333;}

    /* Page Heading */
    .page-heading {background-color: #fff; border: 1px solid #e3e6f0; padding: .75rem 1.25rem; margin-bottom: 1.5rem; border-radius: .5rem;}
    .page-heading .h3 {margin-bottom: 0;}
    .page-heading .breadcrumb {background-color: transparent; margin-bottom: 0;}
    @media(max-width: 576px) {
        .page-heading .breadcrumb {display: none;}
    }

    /* Card */
    .card {border-radius: .5rem;}
    @media(max-width: 576px) {
        .card-header.d-flex {display: block!important;}
        .card-header.d-flex div {text-align: center;}
        .card-header.d-flex div:first-child {margin-bottom: .5rem;}
    }
    .card-header .form-inline .form-control {margin-right: .5rem;}
    .card-header .form-inline .form-control:last-child {margin-right: 0;}
    .list-group .list-group-item.active a {color: #fff;}

    /* Badge */
    .badge {font-size: 85%;}
    .nav-link .badge {margin-top: .15rem;}

    /* Table */
    #dataTable td {padding: .5rem;}
    #dataTable thead tr th {text-align: center;}
    #dataTable tbody tr td:first-child, #dataTable tbody tr td:last-child {text-align: center;}
    #dataTable td a.btn {width: 36px;}
    div.dataTables_wrapper div.dataTables_processing {background-color: #eeeeee;}

    /* Profile */
    .img-profile {border: 1px solid #bebebe;}
</style>
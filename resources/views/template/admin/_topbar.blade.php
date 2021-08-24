        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggle" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <div class="navbar-nav mr-auto">
            <!-- <h1 class="h4 text-gray-800 mb-0">Heading</h1> -->
          </div>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                @if(count(get_data_update())>0)
                <span class="badge badge-danger badge-counter">{{ count(get_data_update()) }}</span>
                @endif
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Notifikasi
                </h6>
                @if(count(get_data_update())>0)
                  @foreach(get_data_update() as $data)
                  <a class="dropdown-item d-flex align-items-center" href="/admin/update-sistem">
                    <div class="mr-3">
                      <div class="icon-circle bg-primary">
                        <i class="fas fa-info text-white"></i>
                      </div>
                    </div>
                    <div>
                      <div class="small text-gray-500">{{ generate_date($data->update_at) }}</div>
                      <span class="font-weight-bold">{{ $data->judul_update }}</span>
                    </div>
                  </a>
                  @endforeach
                @endif
                <a class="dropdown-item text-center small text-gray-500" href="/admin/update-sistem">Lihat Semua</a>
              </div>
            </li>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->nama_user }}</span>
                <img class="img-profile rounded-circle" src="{{ Auth::user()->foto != '' ? asset('assets/images/foto-user/'.Auth::user()->foto) : asset('assets/images/foto-user/user1.png') }}">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="/admin/profil">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profil
                </a>
                <a class="dropdown-item" href="/admin/profil/edit">
                  <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                  Edit Profil
                </a>
                <a class="dropdown-item" href="/admin/profil/edit-password">
                  <i class="fas fa-user-lock fa-sm fa-fw mr-2 text-gray-400"></i>
                  Ganti Password
                </a>
				<!--
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
				-->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('form-logout').submit();">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
                <form id="form-logout" class="d-none" method="post" action="/admin/logout">{{ csrf_field() }}</form>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->
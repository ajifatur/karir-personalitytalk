
    <div class="col-auto mx-auto">
      <div class="card shadow mb-4" style="width: 250px;">
        <div class="card-body">
          <div class="text-center">
            <img src="{{ Auth::user()->foto != '' ? asset('assets/images/foto-user/'.Auth::user()->foto) : asset('assets/images/default/user.png') }}" width="175" class="img-profile rounded-circle">
          </div>
          <div class="list-group mt-4">
            <a class="list-group-item list-group-item-action {{ Request::path() == 'admin/profil' ? 'active' : '' }}" href="/admin/profil">Profil</a>
            <a class="list-group-item list-group-item-action {{ Request::path() == 'admin/profil/edit' ? 'active' : '' }}" href="/admin/profil/edit">Edit Profil</a>
            <a class="list-group-item list-group-item-action {{ Request::path() == 'admin/profil/edit-password' ? 'active' : '' }}" href="/admin/profil/edit-password">Ganti Password</a>
          </div>
        </div>
      </div>
    </div>
@extends('layouts.app')
@section('title','Profil - Engineering')
@section('page-title','Profil Pengguna')
@section('content')
<div class="grid-2" style="gap:20px">
  <div class="card" style="display:flex;flex-direction:column;align-items:center;padding:32px 24px">
    <div class="profile-avatar-container" onclick="openModal('photoModal')">
      <div id="profilePreview" class="avatar-lg">A</div>
      <div class="avatar-overlay"><i class="fas fa-camera"></i></div>
    </div>
    <div style="font-size:20px;font-weight:700;margin-bottom:4px">{{ auth()->user()->name }}</div>
    <div style="color:var(--text2);font-size:14px;margin-bottom:12px">{{ auth()->user()->email }}</div>
    <span class="badge badge-success" style="font-size:12px;padding:4px 14px"><i class="fas fa-shield-alt"></i> Administrator</span>
    <div style="width:100%;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);display:grid;grid-template-columns:1fr 1fr;gap:12px;text-align:center">
      <div><div style="font-size:22px;font-weight:800;color:var(--primary)">48</div><div style="font-size:12px;color:var(--text2)">Sparepart</div></div>
      <div><div style="font-size:22px;font-weight:800;color:#10b981">32</div><div style="font-size:12px;color:var(--text2)">Alat Kantor</div></div>
    </div>
  </div>
  <div class="card">
    <div class="card-header"><span class="card-title"><i class="fas fa-user-edit" style="color:var(--primary)"></i> Edit Profil</span></div>
    
    @if(session('success'))
        <div style="background:rgba(16,185,129,.1);color:#10b981;padding:12px;border-radius:8px;margin-bottom:16px;font-size:13px">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('engineering.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
            @error('name') <span style="color:#ef4444;font-size:11px">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
            @error('email') <span style="color:#ef4444;font-size:11px">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>No. Telepon</label>
            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="+62...">
            @error('phone') <span style="color:#ef4444;font-size:11px">{{ $message }}</span> @enderror
        </div>

        <div class="form-group"><label>Jabatan</label><input type="text" value="Administrator" readonly style="background:var(--surface2);cursor:not-allowed"></div>
        <div class="form-group"><label>Departemen</label><input type="text" value="Engineering" readonly style="background:var(--surface2);cursor:not-allowed"></div>
        
        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
            <button type="reset" class="btn btn-outline">Reset</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </div>
    </form>
  </div>
</div>

{{-- Password Section --}}
<div class="card" style="margin-top:20px">
  <div class="card-header"><span class="card-title"><i class="fas fa-lock" style="color:var(--warning)"></i> Ubah Password</span></div>
  
  <form action="{{ route('engineering.profile.password') }}" method="POST">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
      <div class="form-group">
        <label>Password Lama</label>
        <input type="password" name="current_password" placeholder="••••••••" required>
        @error('current_password') <span style="color:#ef4444;font-size:11px">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label>Password Baru</label>
        <input type="password" name="password" placeholder="••••••••" required>
        @error('password') <span style="color:#ef4444;font-size:11px">{{ $message }}</span> @enderror
      </div>
      <div class="form-group">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" placeholder="••••••••" required>
      </div>
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:4px">
      <button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Update Password</button>
    </div>
  </form>
</div>

<!-- Modal Photo -->
<div class="modal-overlay" id="photoModal">
  <div class="modal" style="max-width:400px">
    <div class="modal-header">
      <span class="modal-title">Ganti Foto Profil</span>
      <button class="btn-icon" onclick="closePhotoModal()"><i class="fas fa-times"></i></button>
    </div>
    <div style="display:flex;flex-direction:column;gap:12px">
      <button class="btn btn-outline" style="justify-content:center" onclick="document.getElementById('fileInput').click()">
        <i class="fas fa-file-image"></i> Pilih dari File
      </button>
      <button class="btn btn-outline" style="justify-content:center" onclick="startCamera()">
        <i class="fas fa-camera"></i> Ambil Foto (Kamera)
      </button>
      
      <div id="cameraContainer" style="display:none;margin-top:10px;text-align:center">
        <video id="video" width="100%" autoplay style="border-radius:12px;background:#000"></video>
        <button class="btn btn-primary" style="margin-top:10px;width:100%" onclick="takeSnapshot()">
          <i class="fas fa-circle"></i> Ambil Gambar
        </button>
      </div>

      <input type="file" id="fileInput" hidden accept="image/*" onchange="handleFile(this)">
      <canvas id="canvas" style="display:none"></canvas>
    </div>
  </div>
</div>
@endsection

@section('extra-styles')
<style>
.profile-avatar-container { position:relative; width:90px; height:90px; cursor:pointer; margin-bottom:16px; transition:var(--transition); }
.profile-avatar-container:hover .avatar-overlay { opacity:1; }
.avatar-lg { width:100%; height:100%; border-radius:50%; background:linear-gradient(135deg,#3b82f6,#8b5cf6); display:flex; align-items:center; justify-content:center; font-size:36px; color:#fff; font-weight:700; box-shadow:0 8px 24px rgba(59,130,246,.3); overflow:hidden; }
.avatar-lg img { width:100%; height:100%; object-fit:cover; }
.avatar-overlay { position:absolute; inset:0; background:rgba(0,0,0,.4); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; opacity:0; transition:var(--transition); }
</style>
@endsection

@section('scripts')
<script>
let stream = null;
function openModal(id){document.getElementById(id).classList.add('open');}
function closePhotoModal(){
    document.getElementById('photoModal').classList.remove('open');
    stopCamera();
}
function stopCamera(){
    if(stream){
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    document.getElementById('cameraContainer').style.display = 'none';
}
async function startCamera() {
    document.getElementById('cameraContainer').style.display = 'block';
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        document.getElementById('video').srcObject = stream;
    } catch (err) {
        alert("Gagal mengakses kamera: " + err.message);
    }
}
function takeSnapshot() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const dataUrl = canvas.toDataURL('image/png');
    updatePreview(dataUrl);
    closePhotoModal();
}
function handleFile(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) { updatePreview(e.target.result); }
        reader.readAsDataURL(input.files[0]);
        closePhotoModal();
    }
}
function updatePreview(url) {
    const p = document.getElementById('profilePreview');
    p.innerHTML = `<img src="${url}">`;
    // Simpan ke localStorage/Kirim ke server disini
    localStorage.setItem('profile_photo', url);
}
// Load saved photo
(function(){
    const saved = localStorage.getItem('profile_photo');
    if(saved) updatePreview(saved);
})();
</script>
@endsection

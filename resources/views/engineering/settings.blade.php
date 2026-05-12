@extends('layouts.app')
@section('title','Pengaturan - Engineering')
@section('page-title','Pengaturan')
@section('content')
<div class="grid-2" style="gap:20px">
  <div style="display:flex;flex-direction:column;gap:20px">
    <div class="card">
      <div class="card-header"><span class="card-title"><i class="fas fa-palette" style="color:var(--primary)"></i> Tampilan</span></div>
      <div style="display:flex;flex-direction:column;gap:16px">
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div><div style="font-weight:600;font-size:14px">Mode Gelap</div><div style="font-size:12px;color:var(--text2)">Aktifkan tampilan gelap</div></div>
          <label class="toggle"><input type="checkbox" id="darkToggle" onchange="toggleTheme()"><span class="slider"></span></label>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div><div style="font-weight:600;font-size:14px">Notifikasi</div><div style="font-size:12px;color:var(--text2)">Tampilkan notifikasi sistem</div></div>
          <label class="toggle"><input type="checkbox" id="notifToggle" onchange="saveSetting('notif', this.checked)" checked><span class="slider"></span></label>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div><div style="font-weight:600;font-size:14px">Sidebar Compact</div><div style="font-size:12px;color:var(--text2)">Perkecil ukuran sidebar</div></div>
          <label class="toggle"><input type="checkbox" id="sidebarToggle" onchange="toggleSidebarCompact(this.checked)"><span class="slider"></span></label>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header"><span class="card-title"><i class="fas fa-globe" style="color:var(--accent)"></i> Bahasa & Regional</span></div>
      <div class="form-group"><label>Bahasa</label>
        <select id="langSelect"><option selected>Bahasa Indonesia</option><option>English</option></select>
      </div>
      <div class="form-group"><label>Format Tanggal</label>
        <select id="dateFormat"><option>DD/MM/YYYY</option><option>MM/DD/YYYY</option><option>YYYY-MM-DD</option></select>
      </div>
      <div class="form-group"><label>Zona Waktu</label>
        <select id="timezone"><option selected>Asia/Makassar (WITA)</option><option>Asia/Jakarta (WIB)</option><option>Asia/Jayapura (WIT)</option></select>
      </div>
      <button class="btn btn-primary btn-sm" onclick="saveRegional()"><i class="fas fa-save"></i> Simpan</button>
    </div>
  </div>
  <div style="display:flex;flex-direction:column;gap:20px">
    <div class="card">
      <div class="card-header"><span class="card-title"><i class="fas fa-database" style="color:var(--success)"></i> Data & Backup</span></div>
      <div style="display:flex;flex-direction:column;gap:10px">
        <button class="btn btn-outline" style="justify-content:center" onclick="mockAction('Export Excel sedang diproses...')"><i class="fas fa-file-export"></i> Export Semua Data (Excel)</button>
        <button class="btn btn-outline" style="justify-content:center" onclick="mockAction('Laporan PDF sedang dibuat...')"><i class="fas fa-file-pdf"></i> Export Laporan PDF</button>
        <button class="btn btn-outline" style="justify-content:center" onclick="mockAction('Database berhasil di-backup ke cloud.')"><i class="fas fa-cloud-upload-alt"></i> Backup Database</button>
        <div style="padding:12px;background:var(--surface2);border-radius:8px;font-size:12px;color:var(--text2)"><i class="fas fa-info-circle" style="color:var(--primary)"></i> Backup terakhir: <strong id="lastBackup">08 Mei 2026, 00:00 WITA</strong></div>
      </div>
    </div>
    <div class="card">
      <div class="card-header"><span class="card-title"><i class="fas fa-shield-alt" style="color:var(--warning)"></i> Keamanan</span></div>
      <div style="display:flex;flex-direction:column;gap:12px">
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div><div style="font-weight:600;font-size:14px">2FA Authentication</div><div style="font-size:12px;color:var(--text2)">Keamanan login dua langkah</div></div>
          <label class="toggle"><input type="checkbox" onchange="mockAction('Fitur 2FA akan segera hadir.')"><span class="slider"></span></label>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div><div style="font-weight:600;font-size:14px">Log Aktivitas</div><div style="font-size:12px;color:var(--text2)">Catat semua aktivitas pengguna</div></div>
          <label class="toggle"><input type="checkbox" checked onchange="saveSetting('log', this.checked)"><span class="slider"></span></label>
        </div>
        <button class="btn btn-outline btn-sm" style="color:#ef4444;border-color:#ef4444;margin-top:4px" onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout Semua Sesi</button>
      </div>
    </div>
  </div>
</div>

<div id="toast" class="toast"></div>
@endsection

@section('extra-styles')
<style>
.toggle{position:relative;display:inline-block;width:46px;height:24px}
.toggle input{opacity:0;width:0;height:0}
.slider{position:absolute;cursor:pointer;inset:0;background:#cbd5e1;border-radius:24px;transition:.3s}
.slider:before{content:'';position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s;box-shadow:0 1px 3px rgba(0,0,0,.2)}
.toggle input:checked+.slider{background:var(--primary)}
.toggle input:checked+.slider:before{transform:translateX(22px)}

.toast { position:fixed; bottom:24px; right:24px; background:var(--sidebar); color:#fff; padding:12px 24px; border-radius:8px; font-size:13px; font-weight:600; box-shadow:var(--shadow-lg); z-index:1000; transform:translateY(100px); opacity:0; transition:all .3s ease; }
.toast.show { transform:translateY(0); opacity:1; }

body.sidebar-compact .sidebar { width:70px; }
body.sidebar-compact .sidebar .brand-name, body.sidebar-compact .sidebar .brand-sub, body.sidebar-compact .sidebar .nav-section, body.sidebar-compact .sidebar .nav-item span:not(.nav-icon), body.sidebar-compact .sidebar .user-info { display:none; }
body.sidebar-compact .main { margin-left:70px; }
</style>
@endsection

@section('scripts')
<script>
function showToast(msg) {
    const t = document.getElementById('toast');
    t.innerText = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

function saveSetting(key, val) {
    localStorage.setItem('settings_' + key, val);
    showToast('Pengaturan berhasil diperbarui.');
}

function toggleSidebarCompact(isCompact) {
    document.body.classList.toggle('sidebar-compact', isCompact);
    saveSetting('sidebar_compact', isCompact);
}

function saveRegional() {
    saveSetting('lang', document.getElementById('langSelect').value);
    saveSetting('dateFormat', document.getElementById('dateFormat').value);
    saveSetting('timezone', document.getElementById('timezone').value);
}

function mockAction(msg) {
    showToast(msg);
}

function confirmLogout() {
    if(confirm('Apakah Anda yakin ingin logout dari semua perangkat?')) {
        showToast('Berhasil logout dari semua sesi.');
    }
}

// Init states
(function(){
    const dt = document.getElementById('darkToggle');
    if(dt) dt.checked = document.documentElement.classList.contains('dark');
    
    const isCompact = localStorage.getItem('settings_sidebar_compact') === 'true';
    document.getElementById('sidebarToggle').checked = isCompact;
    if(isCompact) document.body.classList.add('sidebar-compact');
    
    document.getElementById('notifToggle').checked = localStorage.getItem('settings_notif') !== 'false';
})();
</script>
@endsection

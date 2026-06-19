<!DOCTYPE html>
<html lang="id" class="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Engineering Dashboard')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --bg: #f0f4f8; --surface: #ffffff; --surface2: #f8fafc;
  --border: #e2e8f0; --text: #1e293b; --text2: #64748b;
  --primary: #3b82f6; --primary-dark: #2563eb;
  --success: #10b981; --warning: #f59e0b; --danger: #ef4444;
  --c-primary: 59,130,246; --c-success: 16,185,129; --c-warning: 245,158,11; --c-danger: 239,68,68;
  --sidebar: #1e293b; --sidebar-text: #94a3b8; --sidebar-active: #3b82f6;
  --shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
  --shadow-lg: 0 10px 25px rgba(0,0,0,.1);
  --radius: 12px; --radius-sm: 8px;
  --transition: all .2s ease;
}
html.dark {
  --bg: #0f172a; --surface: #1e293b; --surface2: #263548;
  --border: #334155; --text: #f1f5f9; --text2: #94a3b8;
  --sidebar: #0f172a; --shadow: 0 1px 3px rgba(0,0,0,.3);
  --shadow-lg: 0 10px 25px rgba(0,0,0,.4);
}
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; display:flex; transition:var(--transition); }
/* SIDEBAR */
.sidebar { width:260px; background:var(--sidebar); min-height:100vh; position:fixed; left:0; top:0; z-index:100; display:flex; flex-direction:column; transition: var(--transition); overflow: hidden; }
body.sidebar-compact .sidebar { width: 70px; }
body.sidebar-compact .sidebar .brand-name, 
body.sidebar-compact .sidebar .brand-sub, 
body.sidebar-compact .sidebar .nav-section, 
body.sidebar-compact .sidebar .sidebar-nav,
body.sidebar-compact .sidebar .sidebar-footer { display: none; }
body.sidebar-compact .sidebar .sidebar-brand { padding: 15px 0; justify-content: center; display: flex; border-bottom: none; }
body.sidebar-compact .sidebar .brand-icon { width: 45px; height: 45px; }
.sidebar-brand { padding:24px 20px; border-bottom:1px solid rgba(255,255,255,.08); }
.brand-logo { display:flex; align-items:center; gap:12px; text-decoration:none; }
.brand-icon { width:40px; height:40px; background:linear-gradient(135deg,#3b82f6,#06b6d4); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; color:#fff; }
.brand-name { color:#fff; font-size:16px; font-weight:700; }
.brand-sub { color:#94a3b8; font-size:11px; }
.sidebar-nav { flex:1; padding:16px 0; overflow-y:auto; }
.nav-section { padding:8px 20px 4px; font-size:10px; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:.8px; }
.nav-item { display:flex; align-items:center; gap:12px; padding:10px 20px; color:var(--sidebar-text); text-decoration:none; font-size:14px; font-weight:500; transition:var(--transition); cursor:pointer; border-left:3px solid transparent; }
.nav-item:hover { background:rgba(255,255,255,.06); color:#e2e8f0; }
.nav-item.active { background:rgba(59,130,246,.15); color:#60a5fa; border-left-color:#3b82f6; }
.nav-item .nav-icon { width:20px; text-align:center; font-size:15px; }
.nav-badge { margin-left:auto; background:#ef4444; color:#fff; font-size:10px; font-weight:600; padding:2px 7px; border-radius:20px; }
.sidebar-footer { padding:16px 20px; border-top:1px solid rgba(255,255,255,.08); }
.user-card { display:flex; align-items:center; gap:10px; }
.avatar { width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#3b82f6,#8b5cf6); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:14px; flex-shrink:0; }
.user-info { flex:1; min-width:0; }
.user-name { color:#e2e8f0; font-size:13px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.user-role { color:#64748b; font-size:11px; }
/* MAIN */
.main { margin-left:260px; flex:1; display:flex; flex-direction:column; min-height:100vh; min-width: 0; transition: var(--transition); }
body.sidebar-compact .main { margin-left: 70px; }
/* TOPBAR */
.topbar { background:var(--surface); border-bottom:1px solid var(--border); padding:0 24px; height:64px; display:flex; align-items:center; gap:16px; position:sticky; top:0; z-index:50; box-shadow:var(--shadow); }
.topbar-title { font-size:18px; font-weight:700; flex:1; }
.topbar-actions { display:flex; align-items:center; gap:8px; }
.btn-icon { width:38px; height:38px; border-radius:var(--radius-sm); border:1px solid var(--border); background:var(--surface2); color:var(--text2); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:var(--transition); font-size:15px; }
.btn-icon:hover { background:var(--primary); color:#fff; border-color:var(--primary); }
.notif-badge { position:relative; }
.notif-dot { position:absolute; top:6px; right:6px; width:8px; height:8px; background:#ef4444; border-radius:50%; border:2px solid var(--surface); }
/* CONTENT */
.content { padding:24px; flex:1; min-width: 0; overflow-x: hidden; }
/* CARDS */
.card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:20px; box-shadow:var(--shadow); }
.card-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
.card-title { font-size:15px; font-weight:700; }
/* STATS */
.stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; margin-bottom:24px; }
.stat-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:20px; display:flex; align-items:center; gap:16px; box-shadow:var(--shadow); }
.stat-icon { width:52px; height:52px; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
.stat-value { font-size:26px; font-weight:800; line-height:1; }
.stat-label { font-size:13px; color:var(--text2); margin-top:4px; }
/* TABLE */
.table-wrap { overflow-x:auto; border-radius:var(--radius-sm); }
table { width:100%; border-collapse:collapse; font-size:13px; }
thead th { background:var(--surface2); padding:10px 14px; text-align:left; font-weight:600; font-size:12px; color:var(--text2); text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid var(--border); white-space:nowrap; }
tbody td { padding:10px 14px; border-bottom:1px solid var(--border); vertical-align:middle; }
tbody tr:hover { background:var(--surface2); }
tbody tr:last-child td { border-bottom:none; }
/* BADGE */
.badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
.badge-success { background:rgba(16,185,129,.12); color:#10b981; }
.badge-warning { background:rgba(245,158,11,.12); color:#f59e0b; }
.badge-danger { background:rgba(239,68,68,.12); color:#ef4444; }
.badge-info { background:rgba(59,130,246,.12); color:#3b82f6; }
/* BTN */
.btn { display:inline-flex; align-items:center; gap:8px; padding:8px 16px; border-radius:var(--radius-sm); font-size:13px; font-weight:600; cursor:pointer; border:none; transition:var(--transition); }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-dark); }
.btn-outline { background:transparent; border:1px solid var(--border); color:var(--text); }
.btn-outline:hover { background:var(--surface2); }
.btn-sm { padding:5px 12px; font-size:12px; }
/* FORM */
.form-group { margin-bottom:16px; }
label { display:block; font-size:13px; font-weight:500; margin-bottom:6px; color:var(--text); }
input, select, textarea { width:100%; padding:9px 12px; border:1px solid var(--border); border-radius:var(--radius-sm); background:var(--surface); color:var(--text); font-size:13px; font-family:'Inter',sans-serif; outline:none; transition:var(--transition); }
input:focus, select:focus, textarea:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(59,130,246,.1); }
.search-bar { position:relative; }
.search-bar input { padding-left:36px; }
.search-bar i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text2); font-size:14px; }
/* MODAL */
.modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:200; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px); opacity:0; visibility:hidden; transition:var(--transition); }
.modal-overlay.open { opacity:1; visibility:visible; }
.modal { background:var(--surface); border-radius:var(--radius); padding:24px; width:90%; max-width:520px; box-shadow:var(--shadow-lg); transform:scale(.95); transition:var(--transition); max-height: 90vh; overflow-y: auto; }
.modal-overlay.open .modal { transform:scale(1); }
.modal-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
.modal-title { font-size:16px; font-weight:700; }
/* PAGINATION */
.pagination { display:flex; list-style:none; gap:5px; align-items:center; }
.pagination li span, .pagination li a { display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 10px; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:13px; text-decoration:none; color:var(--text); background:var(--surface); }
.pagination li.active span { background:var(--primary); color:#fff; border-color:var(--primary); }
.pagination li.disabled span { color:var(--text2); opacity:.5; cursor:not-allowed; }
nav[role="navigation"] svg { width:20px; }
nav[role="navigation"] div:first-child { margin-bottom: 10px; color: var(--text2); font-size: 13px; }
nav[role="navigation"] .flex.justify-between { display: flex; align-items: center; justify-content: space-between; flex-direction: column; gap: 10px; }
@media(min-width: 640px) {
  nav[role="navigation"] .flex.justify-between { flex-direction: row; }
}

.sidebar-toggle { display:flex; margin-right: 10px; }
@media(max-width:768px) {
  .sidebar { transform:translateX(-100%); }
  .sidebar.open { transform:translateX(0); }
  .main { margin-left:0; }
  .sidebar-toggle { display:flex; }
  .overlay { position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:99; display:none; }
  .overlay.show { display:block; }
}
.grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
@media(max-width:640px) { .grid-2 { grid-template-columns:1fr; } }
/* DROPDOWN */
.dropdown { position:relative; }
.dropdown-menu { position:absolute; top:110%; right:0; width:280px; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-lg); z-index:150; padding:8px; display:none; animation:dropdownFade .2s ease; }
.dropdown-menu.show { display:block; }
@keyframes dropdownFade { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
.dropdown-header { padding:10px 12px; font-size:13px; font-weight:700; border-bottom:1px solid var(--border); margin-bottom:8px; display:flex; justify-content:space-between; align-items:center; }
.dropdown-item { display:flex; align-items:center; gap:12px; padding:10px 12px; color:var(--text); text-decoration:none; font-size:13px; border-radius:var(--radius-sm); transition:var(--transition); cursor:pointer; }
.dropdown-item:hover { background:var(--surface2); }
.dropdown-item i { width:16px; color:var(--text2); }
.notif-item { display:flex; gap:12px; padding:10px; border-radius:var(--radius-sm); transition:var(--transition); cursor:pointer; border-bottom:1px solid var(--border); text-decoration: none; color: inherit; }
.notif-item:hover { background:var(--surface2); }
.notif-icon { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; flex-shrink:0; }
.notif-content { flex:1; }
.notif-title { font-size:12px; font-weight:600; margin-bottom:2px; }
.notif-time { font-size:10px; color:var(--text2); }

</style>
@yield('extra-styles')
<script>
  (function(){
    const t=localStorage.getItem('theme')||'light';
    if(t==='dark')document.documentElement.classList.add('dark');
    const isCompact=localStorage.getItem('sidebar-compact')==='true';
    if(isCompact) document.documentElement.classList.add('sidebar-compact-init');
    document.addEventListener('DOMContentLoaded', () => {
      if(isCompact) document.body.classList.add('sidebar-compact');
    });
  })();
</script>
</head>

<body>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <a href="/" class="brand-logo">
      <div class="brand-icon"><i class="fas fa-cogs"></i></div>
      <div>
        <div class="brand-name">Engineering</div>
        <div class="brand-sub">Management System</div>
      </div>
    </a>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section">Menu Utama</div>
    <a href="{{ route('engineering.dashboard') }}" class="nav-item {{ request()->routeIs('engineering.dashboard') ? 'active' : '' }}">
      <span class="nav-icon"><i class="fas fa-home"></i></span> Dashboard
    </a>
    <div class="nav-section">Inventaris</div>
    <a href="{{ route('engineering.sparepart') }}" class="nav-item {{ request()->routeIs('engineering.sparepart') ? 'active' : '' }}">
      <span class="nav-icon"><i class="fas fa-tools"></i></span> Log Book
    </a>
    <a href="{{ route('engineering.alat') }}" class="nav-item {{ request()->routeIs('engineering.alat') ? 'active' : '' }}">
      <span class="nav-icon"><i class="fas fa-toolbox"></i></span> Daftar Alat Kantor
    </a>
    <a href="{{ route('engineering.peminjaman') }}" class="nav-item {{ request()->routeIs('engineering.peminjaman') ? 'active' : '' }}">
      <span class="nav-icon"><i class="fas fa-hand-holding"></i></span> Peminjaman Alat
    </a>
    <a href="{{ route('engineering.klasifikasi') }}" class="nav-item {{ request()->routeIs('engineering.klasifikasi') ? 'active' : '' }}">
      <span class="nav-icon"><i class="fas fa-boxes"></i></span> Log Barang Masuk dan Keluar
    </a>

    <a href="{{ route('engineering.pengajuan_perangkat') }}" class="nav-item {{ request()->routeIs('engineering.pengajuan_perangkat') ? 'active' : '' }}">
      <span class="nav-icon"><i class="fas fa-laptop-medical"></i></span> Pengajuan Perangkat
    </a>
    <div class="nav-section">Pengaturan</div>
    <a href="{{ route('engineering.profile') }}" class="nav-item {{ request()->routeIs('engineering.profile') ? 'active' : '' }}">
      <span class="nav-icon"><i class="fas fa-user-circle"></i></span> Profil
    </a>
    <a href="{{ route('engineering.settings') }}" class="nav-item {{ request()->routeIs('engineering.settings') ? 'active' : '' }}">
      <span class="nav-icon"><i class="fas fa-cog"></i></span> Pengaturan
    </a>
  </nav>
  <div class="sidebar-footer">
    <div class="user-card">
      <div class="avatar">A</div>
      <div class="user-info">
        <div class="user-name">{{ auth()->user()->name }}</div>
        <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
      </div>
    </div>
  </div>
</aside>
<div class="main">
  <header class="topbar">
    <button class="btn-icon sidebar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <h1 class="topbar-title">@yield('page-title','Dashboard')</h1>
    <div class="topbar-actions">
      <!-- Notifications -->
      <div class="dropdown">
        <button class="btn-icon notif-badge" onclick="toggleDropdown('notifDropdown')" title="Notifikasi">
          <i class="fas fa-bell"></i><span class="notif-dot"></span>
        </button>
        <div class="dropdown-menu" id="notifDropdown">
          <div class="dropdown-header">Notifikasi <span class="badge badge-danger">3 Baru</span></div>
          <a href="{{ route('engineering.sparepart') }}?search=Printer" class="notif-item">
            <div class="notif-icon" style="background:rgba(59,130,246,.1);color:var(--primary)"><i class="fas fa-tools"></i></div>
            <div class="notif-content"><div class="notif-title">Log Book "Printer" tertunda</div><div class="notif-time">2 menit yang lalu</div></div>
          </a>
          <a href="{{ route('engineering.settings') }}" class="notif-item">
            <div class="notif-icon" style="background:rgba(16,185,129,.1);color:var(--success)"><i class="fas fa-check-circle"></i></div>
            <div class="notif-content"><div class="notif-title">Backup database berhasil</div><div class="notif-time">1 jam yang lalu</div></div>
          </a>
          <a href="{{ route('engineering.alat') }}?search=Kabel+UTP" class="notif-item" style="border:none">
            <div class="notif-icon" style="background:rgba(245,158,11,.1);color:var(--warning)"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="notif-content"><div class="notif-title">Stok Kabel UTP menipis</div><div class="notif-time">3 jam yang lalu</div></div>
          </a>
          <div style="padding:8px;text-align:center;border-top:1px solid var(--border);margin-top:8px">
            <a href="#" style="font-size:11px;color:var(--primary);font-weight:600;text-decoration:none">Lihat Semua Notifikasi</a>
          </div>
        </div>
      </div>

      <button class="btn-icon" id="themeToggle" onclick="toggleTheme()" title="Ganti Tema"><i class="fas fa-moon"></i></button>
      
      <!-- Profile -->
      <div class="dropdown">
        <button class="btn-icon" onclick="toggleDropdown('profileDropdown')" title="Profil"><i class="fas fa-user"></i></button>
        <div class="dropdown-menu" id="profileDropdown" style="width:200px">
          <div class="dropdown-header">Akun Saya</div>
          <a href="{{ route('engineering.profile') }}" class="dropdown-item"><i class="fas fa-user-circle"></i> Profil</a>
          <a href="{{ route('engineering.settings') }}" class="dropdown-item"><i class="fas fa-cog"></i> Pengaturan</a>
          <div style="border-top:1px solid var(--border);margin-top:8px;padding-top:8px">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" class="dropdown-item" style="color:var(--danger)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
          </div>
        </div>
      </div>
    </div>

  </header>
  <main class="content">
    @yield('content')
  </main>
</div>
<script>
function toggleSidebar(){
  if(window.innerWidth > 768) {
    document.body.classList.toggle('sidebar-compact');
    localStorage.setItem('sidebar-compact', document.body.classList.contains('sidebar-compact'));
  } else {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
  }
}
function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('show');}
function toggleTheme(){
  const h=document.documentElement;
  const isDark=h.classList.toggle('dark');
  const icon=document.querySelector('#themeToggle i');
  if(icon) icon.className=isDark?'fas fa-sun':'fas fa-moon';
  localStorage.setItem('theme',isDark?'dark':'light');
  
  // Sinkronisasi dengan checkbox di halaman settings jika ada
  const settingToggle = document.getElementById('darkToggle');
  if(settingToggle) settingToggle.checked = isDark;
}
function toggleDropdown(id){
  const d=document.getElementById(id);
  const isShow=d.classList.contains('show');
  document.querySelectorAll('.dropdown-menu').forEach(m=>m.classList.remove('show'));
  if(!isShow) d.classList.add('show');
}
window.onclick=function(e){if(!e.target.closest('.dropdown')){document.querySelectorAll('.dropdown-menu').forEach(m=>m.classList.remove('show'));}}

// Update icon status on load
document.addEventListener('DOMContentLoaded', () => {
    const isDark = document.documentElement.classList.contains('dark');
    const icon = document.querySelector('#themeToggle i');
    if(icon) icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
});

// AFK Logout logic (30 Menit)
let idleTime = 0;
const maxIdleTime = 30 * 60; // 30 minutes

function resetTimer() {
    idleTime = 0;
}

// Cek setiap detik
setInterval(function() {
    idleTime++;
    if (idleTime >= maxIdleTime) {
        const logoutForm = document.getElementById('logout-form');
        if (logoutForm) logoutForm.submit();
    }
}, 1000);

// Reset timer saat ada aktivitas
['onload', 'onmousemove', 'onmousedown', 'ontouchstart', 'onclick', 'onkeypress', 'onscroll'].forEach(evt => {
    window[evt] = resetTimer;
});
</script>


@yield('scripts')
</body>
</html>

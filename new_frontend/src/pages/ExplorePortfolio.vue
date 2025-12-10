<!-- src/pages/Portofolio.vue -->
<template>
  <div class="page-portfolio">
    <!-- NAVBAR (large rounded bar centered) -->
    <header class="nav-wrap">
      <div class="nav-bar">
        <div class="nav-left">
          <span class="brand">Porto Connect</span>
          <img src="@/assets/logo-soegija.png" alt="soegija" class="nav-logo" />
        </div>

        <div class="nav-center">
          <a class="nav-link">Home</a>
          <a class="nav-link active">Portofolio</a>
        </div>

        <div class="nav-right">
          <div class="user">
            <div class="user-name">Dashboard</div>
          </div>
        </div>
      </div>
    </header>

    <!-- SEARCH ROW -->
    <section class="search-row">
      <div class="search-inner">
        <button class="search-pill">Job</button>
        <button class="search-input">CV</button>
      </div>
    </section>

    <!-- MAIN: sidebar + purple-panel containing cards -->
    <main class="main-wrap">
      <!-- left vertical list with gradient and scroll -->
      <aside class="left-sidebar" aria-hidden="true">
        <div class="sidebar-scroll">
          <div v-for="i in 20" :key="i" class="sidebar-item">
            <span class="si-left">Logo Role</span>
            <span class="si-text">Full Stack</span>
            <span class="si-count">10</span>
          </div>
        </div>
      </aside>

      <!-- cards container (purple rounded big panel like figma) -->
      <section class="cards-panel">
        <div class="cards-inner">
          <div class="cards-grid">
            <article v-for="i in 9" :key="i" class="card">
              <div class="card-thumb" :style="{ backgroundImage: 'url(@/assets/card-sample.jpg)' }"></div>
              <div class="card-footer">
                <img src="@/assets/team1.jpg" alt="avatar" class="card-avatar" />
                <div class="card-texts">
                  <div class="card-name">Michelle Gore</div>
                  <div class="card-sub">UC Davis</div>
                </div>
                <div class="card-icon">◦</div>
              </div>
            </article>
          </div>
        </div>
      </section>
    </main>

    <!-- footer - contact + logo bar -->
    <footer class="page-footer">
      <div class="footer-inner">
        <div class="contact">
          <h3>Informasi Kontak</h3>
          <div class="c-line">Email : realchrisplays@gmail.com</div>
          <div class="c-line">WhatsApp Official : 08123-2345-479</div>
        </div>

        <div class="footer-logos">
          <div class="porto">Porto<br/>Connect</div>
          <div class="x">✕</div>
          <div class="uni">
            <div class="uni-text">
              <div class="muted">SOEGIJAPRANATA</div>
              <div class="muted">CATHOLIC UNIVERSITY</div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
defineOptions({ name: 'PortfolioPage' })
/* intentionally no unused imports */
</script>

<style scoped>
/* PAGE BACKGROUND - match Figma vertical gradient feel */
.page-portfolio {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: radial-gradient( circle at 50% -20%,
    #000 2%,
    #50145c 18%,
    #5b1e62 36%,
    #5b1e62 50%,
    #ffffff 100%
  );
  color: #111;
  font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
}

/* NAVBAR: big rounded pill centered */
.nav-wrap { padding: 28px 0; }
.nav-bar {
  width: min(1200px, 94%);
  margin: 0 auto;
  display:flex;
  align-items:center;
  justify-content:space-between;
  background: rgba(255,255,255,0.95);
  border-radius: 26px;
  padding: 12px 28px;
  box-shadow: 0 8px 30px rgba(6,6,10,0.12);
  height: 64px;
}
.nav-left, .nav-center, .nav-right { display:flex; align-items:center; gap:14px; }
.brand { font-weight:700; color:#5e1f62; }
.nav-logo { height:26px; }
.nav-center { gap:28px; margin-left: 14px;}
.nav-link { color:#222; opacity:0.9; }
.nav-link.active { font-weight:700; }

/* search row */
.search-row { margin-top: 20px; }
.search-inner {
  width: min(1200px, 94%);
  margin: 0 auto;
  display:flex;
  gap:18px;
  align-items:center;
  padding: 4px 12px;
}
.search-pill {
  min-width:86px;
  padding:10px 18px;
  border-radius:999px;
  background: #ffffff;
  border:none;
  box-shadow: 0 8px 20px rgba(6,6,10,0.06);
}
.search-input {
  flex:1;
  height: 42px;
  border-radius: 999px;
  padding: 0 20px;
  border: 2px solid rgba(0,0,0,0.8);
  background: white;
  color: #222;
  box-shadow: 0 8px 20px rgba(6,6,10,0.06);
}

/* MAIN LAYOUT: sidebar + purple-panel */
.main-wrap {
  width: min(1200px, 94%);
  margin: 28px auto;
  display:grid;
  grid-template-columns: 220px 1fr;
  gap: 28px;
  align-items:start;
}

/* Left sidebar: gradient, rounded, scrollbar */
.left-sidebar {
  border-radius: 18px;
  padding: 14px;
  background: linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
  box-shadow: 0 20px 60px rgba(6,6,10,0.06);
  height: calc(100vh - 360px);
  overflow: hidden;
}
.sidebar-scroll {
  height:100%;
  overflow:auto;
  padding-right:8px;
}
.sidebar-item {
  display:flex;
  align-items:center;
  gap:10px;
  padding: 12px;
  margin-bottom: 12px;
  color: rgba(255,255,255,0.9);
  opacity: 0.92;
  border-radius: 12px;
}

/* Cards panel: big purple rounded holder like Figma */
.cards-panel {
  border-radius: 26px;
  background: linear-gradient(180deg, rgba(90,25,78,0.85), rgba(187,135,180,0.15));
  padding: 28px;
  box-shadow: 0 30px 80px rgba(6,6,10,0.12);
}
.cards-inner {
  background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.04));
  padding: 18px;
  border-radius: 18px;
}
.cards-grid {
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
}

/* individual card: white rounded with thumb + footer */
.card {
  background: #fff;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 16px 40px rgba(6,6,10,0.08);
  display:flex;
  flex-direction:column;
  min-height: 220px;
}
.card-thumb {
  height: 140px;
  background-size: cover;
  background-position: center;
}
.card-footer {
  display:flex;
  align-items:center;
  gap:12px;
  padding: 12px 16px;
}
.card-avatar {
  width:44px; height:44px; border-radius:999px; object-fit:cover; border:3px solid #fff;
}
.card-texts .card-name { font-weight:700; color:#111; }
.card-sub { font-size:13px; color:#777; margin-top:4px; }
.card-icon { margin-left:auto; color:#bbb; }

/* footer */
.page-footer {
  margin-top: 48px;
  background: #4b1545;
  color:#fff;
  padding: 48px 0;
}
.footer-inner {
  width: min(1200px, 94%);
  margin: 0 auto;
  display:flex;
  flex-direction:column;
  gap:28px;
  align-items:center;
}
.contact { align-self:flex-start; }
.contact h3 { margin:0 0 6px 0; font-weight:700; }
.footer-logos {
  width:100%;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:36px;
  padding-top: 8px;
}
.porto { font-weight:700; font-size:20px; text-align:center; }
.x { font-size:34px; color:#fff; transform:translateY(6px); }
.uni img { height:48px; margin-right:12px; }
.uni-text .muted { font-size:20px; opacity:0.85; }

/* responsive */
@media (max-width: 1000px) {
  .cards-grid { grid-template-columns: repeat(2, 1fr); }
  .main-wrap { grid-template-columns: 160px 1fr; }
}
@media (max-width: 720px) {
  .nav-bar { padding: 8px 14px; height:58px; }
  .main-wrap { grid-template-columns: 1fr; }
  .left-sidebar { display:none; }
  .cards-grid { grid-template-columns: 1fr; }
  .search-inner { flex-direction:column; gap:12px; align-items:stretch; }
}
</style>

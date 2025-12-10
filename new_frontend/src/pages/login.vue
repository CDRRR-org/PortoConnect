<template>
  <div class="login-page min-h-screen">
    <!-- back -->
    <button class="back-btn" @click="goBack" aria-label="Back">
      <span class="chev">‹</span> Back
    </button>

    <!-- big S column (right side) -->
    <div class="s-column" aria-hidden="true">
      <img v-for="n in 5" :key="n" src="@/assets/logo-s-besar.png" alt="" class="s-logo" />
    </div>

    <!-- main centered area -->
    <div class="container">
      <div class="auth-card" role="main" aria-labelledby="welcome">
        <h1 id="welcome">Welcome to<br /><span>Porto Connect</span></h1>

        <button class="google-btn" @click="handleGoogleSignIn" aria-label="Sign in with Google">
          <span class="g-icon" aria-hidden="true">
            <!-- simple G svg -->
            <svg viewBox="0 0 533.5 544.3" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
              <path fill="#4285F4" d="M533.5 278.4c0-17.4-1.6-34.1-4.6-50.4H272v95.3h146.9c-6.4 34.7-25.9 64.1-55.5 83.7v69.7h89.5c52.6-48.4 83.6-119.9 83.6-198.3z"/>
              <path fill="#34A853" d="M272 544.3c73.7 0 135.6-24.4 180.8-66.4l-89.5-69.7c-24.9 16.8-56.8 26.7-91.3 26.7-70.2 0-129.7-47.2-151.1-110.6H28.6v69.5C73.6 486.1 167 544.3 272 544.3z"/>
              <path fill="#FBBC05" d="M120.9 324.3c-8.4-25-8.4-51.8 0-76.8V178H28.6c-36.7 72.9-36.7 159.4 0 232.3l92.3-86z"/>
              <path fill="#EA4335" d="M272 109.6c39.9 0 75.8 13.7 104 40.6l78-78C404.7 25.8 349.4 0 272 0 167 0 73.6 58.2 28.6 145.9l92.3 69.5C142.3 156.8 201.8 109.6 272 109.6z"/>
            </svg>
          </span>
          <span class="btn-text">Sign Up or Login with Google</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineOptions({ name: 'LoginPage' })

import { useRouter } from 'vue-router'
const router = useRouter()

function goBack() {
  router.back()
}

function handleGoogleSignIn() {
  // placeholder: replace with real OAuth flow
  // e.g. window.location = '/api/auth/google' or open popup
  console.info('Google sign-in clicked — integrate OAuth here')
  alert('Simulasi: jalankan proses Google OAuth di sini.')
}
</script>

<style scoped>
/* Fonts: use project's font already imported globally; local tweaks below */
.login-page {
  position: relative;
  overflow: hidden;
  display: block;
  background: radial-gradient(ellipse 250% 50% at -40%, #ffffff 6%, #f5eaf0 12%, #50145C 70%, #100 100%);
  min-height: 100vh;
}

/* Back button top-left */
.back-btn {
  position: fixed;
  top: 28px;
  left: 28px;
  z-index: 60;
  background: rgba(255,255,255,0.9);
  color: #111;
  border: none;
  padding: 10px 18px;
  border-radius: 999px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.08);
  cursor: pointer;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.back-btn .chev { font-size: 20px; line-height: 1; }

/* Right column of S logos */
.s-column {
  position: fixed;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
  display: flex;
  flex-direction: column;
  gap: 28px;
  align-items: center;
  pointer-events: none;
}
.s-column .s-logo {
  width: 86px;
  height: auto;
  opacity: 1;
  filter: brightness(100%);
  transform: rotate(0deg);
}

/* Container to center the card-ish block left-of-center like the design */
.container {
  display: flex;
  align-items: center;
  min-height: 100vh;
  padding-left: 6vw;
  padding-right: 6vw;
  z-index: 30;
}

/* Card */
.auth-card {
  width: min(620px, 86%);
  background: rgba(255,255,255,0.12);
  backdrop-filter: blur(6px);
  border-radius: 36px;
  padding: 56px 48px;
  box-shadow: 0 30px 70px rgba(0,0,0,0.35), inset 0 -4px 20px rgba(0,0,0,0.06);
  color: #0a0a0a;
  max-width: 680px;
}

/* Heading */
.auth-card h1 {
  margin: 0 0 18px 0;
  font-family: 'Poppins', sans-serif;
  font-weight: 800;
  font-size: 36px;
  color: #0b0b0b;
  text-align: left;
  line-height: 1.1;
}
.auth-card h1 span { display:block; }

/* subtitle */
.subtitle {
  margin: 6px 0 28px 0;
  color: rgba(0,0,0,0.6);
  font-size: 14px;
}

/* Google button */
.google-btn {
  display: inline-flex;
  align-items: center;
  gap: 14px;
  width: 100%;
  background: linear-gradient(90deg, rgba(80,20,92,0.22), rgba(80,20,92,0.10));
  border-radius: 999px;
  padding: 16px 22px;
  border: none;
  cursor: pointer;
  font-weight: 600;
  box-shadow: 0 10px 30px rgba(16,16,16,0.12);
  transition: transform .18s ease, box-shadow .18s ease;
}
.google-btn:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(16,16,16,0.18); }

/* svg container */
.g-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  background: white;
  border-radius: 999px;
  box-shadow: inset 0 -4px 10px rgba(0,0,0,0.06);
}

/* text inside button */
.btn-text {
  color: #111;
  font-size: 16px;
}

/* responsive */
@media (max-width: 900px) {
  .s-column { display: none; }
  .auth-card {
    width: calc(100% - 48px);
    padding: 34px 20px;
    border-radius: 18px;
  }
  .container { padding-left: 20px; padding-right: 20px; justify-content:center; }
  .auth-card h1 { font-size: 28px; text-align:center; }
  .subtitle { text-align:center; }
  .google-btn { width: 100%; }
  .back-btn { left: 16px; top: 18px; padding: 8px 14px; }
}
</style>

<template>
  <div class="bg-black text-white min-h-screen">
    
    <header class="fixed top-6 left-0 right-0 z-50">
      <nav
        class="max-w-6xl mx-auto py-3 px-6 bg-white/10 backdrop-blur-md rounded-full flex justify-between items-center shadow-lg"
      >
        <div class="flex items-center gap-3">
          <span class="text-xl font-bold font-poppins text-white"
            >Porto Connect</span
          >
          <img
          />
          <img
            src="@/assets/logo-soegija.png"
            alt="Soegijapranata Logo"
            class="h-8"
          />
        </div>

        <div class="hidden md:flex items-center gap-8 font-roboto">
          <a href="#" class="hover:text-purple-300 transition">Home</a>
          <a href="#" class="hover:text-purple-300 transition">Portofolio</a>
        </div>

        <div
          class="flex items-center gap-1 bg-black rounded-full py-1.5 px-2 font-roboto"
        >
          <router-link
            to="/login"
            class="py-1.5 px-4 rounded-full hover:bg-gray-800 transition"
          >
            Sign Up
          </router-link>
          <router-link
            to="/login"
            class="py-1.5 px-4 rounded-full bg-white text-black hover:bg-gray-200 transition"
          >
            Login
          </router-link>
        </div>
      </nav>
    </header>

    <main
      class="pt-32 pb-20 min-h-screen flex items-center bg-gradient-to-r from-purple-900 via-black to-black"
    >
      <div
        class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 items-center gap-16 px-6"
      >
        <div class="text-left">
          <h1
            class="text-5xl md:text-6xl font-bold font-poppins mb-6 leading-tight text-purple-300"
          >
            Showcase Your Potential.
            <span class="text-white">Connect with Top Employers.</span>
          </h1>
          <blockquote class="text-lg text-gray-300 italic mb-4 font-roboto">
            "I'm convinced that about half of what separates successful
            entrepreneurs from the non-successful ones is pure perseverance."
          </blockquote>
          <p class="text-gray-400 mb-8 font-roboto">
            Steve Jobs (Co-founder Apple)
          </p>
          <button
            class="bg-white text-black font-semibold py-2 px-8 rounded-full shadow-lg hover:bg-gray-200 transition"
          >
            About
          </button>
        </div>

        <div class="flex justify-center">
          <img
            src="@/assets/logo-s-besar.png"
            alt="Soegija Logo S"
            class="w-64 h-64 md:w-96 md:h-96"
          />
        </div>
      </div>
    </main>

    <section class="py-24 bg-white text-black">
      <div class="max-w-4xl mx-auto text-center px-6">
        <h2 class="text-3xl font-poppins text-purple-800 mb-8">
          "If you want to go fast, go alone. If you want to go far, go
          together."
        </h2>
        <button
          class="bg-white text-black font-semibold py-2 px-8 rounded-full border-2 border-black hover:bg-gray-100 transition mb-16"
        >
          Our Teams
        </button>

        <div class="flex justify-center items-center gap-8">
          <div
            class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-purple-800"
          ></div>
          <div
            class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-yellow-400"
          ></div>
          <div
            class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-black"
          ></div>
          <div
            class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-yellow-400"
          ></div>
          <div
            class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-purple-800"
          ></div>
        </div>
      </div>
    </section>

    <footer class="bg-purple-900 text-white py-16 font-roboto">
      <div
        class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center"
      >
        <div>
          <h3
            class="text-2xl md:text-3xl font-bold font-poppins mb-4"
          >
            Informasi Kontak
          </h3>
          <ul class="space-y-2 text-gray-300">
            <li>
              Email :
              <a
                href="mailto:unika@unika.ac.id"
                class="hover:text-purple-300 transition"
                >unika@unika.ac.id</a
              >
            </li>
            <li>Hotline : (024) 850 5003</li>
            <li>
              WhatsApp Official :
              <a
                href="https://wa.me/6281232345479"
                class="hover:text-purple-300 transition"
                >08123 2345 479</a
              >
            </li>
          </ul>
        </div>

        <div
          class="flex items-center justify-center md:justify-end gap-4"
        >
          <span class="text-3xl font-poppins text-white"
            >Porto Connect</span
          >
     
        </div>
      </div>
      <div class="border-t border-purple-800 mt-12 pt-8 text-center text-gray-500 text-sm">
        &copy; 2025 PortoConnect. All rights reserved.
      </div>
    </footer>
  </div>
</template>

<script setup>
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400&display=swap');
.font-poppins {
  font-family: 'Poppins', sans-serif;
}
.font-roboto {
  font-family: 'Roboto', sans-serif;
}
</style>
<template>
  <div>
    <!-- Navbar -->
    <p-menubar class="navbar">
      <template #start>
        <div class="navbar-start">
          <i class="pi pi-book mr-2"></i>
          <h3 class="school-name">SD Islam Plus Al Muhajirin</h3>
        </div>
      </template>
      <template #end>
        <div class="navbar-end">
          <Button v-if="!isAuthenticated" icon="pi pi-sign-in" label="Login" class="p-button p-button-success p-button-rounded p-button-sm" @click="goToLogin" />
        </div>
      </template>
    </p-menubar>

    <!-- Banner Carousel -->
    <section class="banner-carousel">
      <Galleria
        :value="images"
        :responsiveOptions="responsiveOptions"
        :numVisible="1"
        :showThumbnails="false"
        :showIndicators="true"
        :circular="true"
        containerStyle="max-width: 100%; margin: auto"
        :autoPlay="true"
        :transitionInterval="4000"
      >
        <template #item="slotProps">
          <img :src="slotProps.item.itemImageSrc" :alt="slotProps.item.alt" class="banner-image" />
        </template>
      </Galleria>
    </section>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-text">
        <h1>
          Selamat Datang di
          <span class="highlight">Sekolah Dasar Al Muhajirin</span>
        </h1>
        <p>Sekolah unggulan yang membentuk generasi Islami, cerdas, dan berkarakter mulia.</p>
      </div>
    </section>

    <!-- Tentang Kami -->
    <section class="section-profil">
      <h2><i class="pi pi-info-circle"></i> Tentang Kami</h2>
      <p>Sekolah Dasar Al Muhajirin berkomitmen membentuk generasi penerus bangsa yang unggul dalam prestasi, berlandaskan nilai‑nilai Islam, serta siap menghadapi tantangan global.</p>

      <div class="profil-content">
        <div class="profil-item" @click="showContent('visi-misi')">
          <i class="pi pi-eye icon"></i>
          <h3>Visi dan Misi</h3>
          <p>Klik untuk melihat visi dan misi</p>
        </div>
        <div class="profil-item" @click="showContent('prestasi')">
          <i class="pi pi-star icon"></i>
          <h3>Prestasi</h3>
          <p>Klik untuk melihat prestasi siswa</p>
        </div>
        <div class="profil-item" @click="showContent('ekstrakurikuler')">
          <i class="pi pi-users icon"></i>
          <h3>Ekstrakurikuler</h3>
          <p>Klik untuk melihat kegiatan siswa</p>
        </div>
        <div class="profil-item" @click="showContent('program-pembelajaran')">
          <i class="pi pi-book icon"></i>
          <h3>Program Pembelajaran</h3>
          <p>Klik untuk melihat program belajar</p>
        </div>
        <div class="profil-item" @click="showContent('guru-staf')">
          <i class="pi pi-id-card icon"></i>
          <h3>Guru & Staf</h3>
          <p>Klik untuk melihat tenaga pendidik</p>
        </div>
        <div class="profil-item" @click="showContent('fasilitas')">
          <i class="pi pi-building icon"></i>
          <h3>Fasilitas</h3>
          <p>Klik untuk melihat fasilitas sekolah</p>
        </div>
      </div>
    </section>

   <!-- Konten yang dipilih -->
<section v-if="selectedContent" class="section-content">
  <h3><i class="pi pi-folder-open"></i> {{ selectedContent.title }}</h3>

  <!-- VISI & MISI -->
  <div v-if="selectedContent.type === 'visi-misi'">
    <div class="visi-section">
      <h4><i class="pi pi-eye"></i> Visi</h4>
      <p>{{ selectedContent.visi }}</p>
    </div>
    <div class="misi-section">
      <h4><i class="pi pi-list"></i> Misi</h4>
      <ul>
        <li v-for="(item, index) in selectedContent.misi" :key="index">
          <i class="pi pi-check-circle text-primary mr-2"></i> {{ item }}
        </li>
      </ul>
    </div>
  </div>

  <!-- PRESTASI -->
  <div v-else-if="selectedContent.type === 'prestasi'" class="prestasi-section">
    <div v-for="(item, index) in selectedContent.items" :key="index" class="prestasi-card">
      <div class="prestasi-icon">
        <i class="pi pi-star-fill"></i>
      </div>
      <div class="prestasi-info">
        <h4>{{ item.judul }}</h4>
        <p>{{ item.deskripsi }}</p>
        <span class="prestasi-tanggal">{{ item.tanggal }}</span>
      </div>
    </div>
  </div>

  <!-- EKSTRAKURIKULER -->
  <div v-else-if="selectedContent.type === 'ekskul'" class="ekskul-section">
    <div class="ekskul-grid">
      <div v-for="(item, index) in selectedContent.items" :key="index" class="ekskul-card">
        <div class="ekskul-icon">
          <i :class="item.icon"></i>
        </div>
        <div class="ekskul-info">
          <h4>{{ item.nama }}</h4>
          <p>{{ item.deskripsi }}</p>
        </div>
      </div>
    </div>
  </div>

<!-- GURU & STAF -->
<div v-else-if="selectedContent.type === 'guru-staf'" class="guru-staf-section">
  <!-- Kepala Sekolah -->
  <h4 class="section-subtitle"><i class="pi pi-user"></i> Kepala Sekolah</h4>
  <div class="guru-grid">
    <div class="guru-card">
      <strong>Khjanah, S.Pd</strong>
      <p>Kepala Sekolah</p>
    </div>
  </div>

  <!-- Wakil Kepala Sekolah -->
  <h4 class="section-subtitle"><i class="pi pi-users"></i> Wakil Kepala Sekolah</h4>
  <div class="guru-grid">
    <div class="guru-card">
      <strong>Wisnu Febrian, S.H</strong>
      <p>Wakil Kepala Sekolah</p>
    </div>
  </div>

  <!-- Guru Walikelas -->
  <h4 class="section-subtitle"><i class="pi pi-bookmark"></i> Guru Walikelas</h4>
  <div class="guru-grid">
    <div class="guru-card" v-for="(guru, index) in selectedContent.walikelas" :key="index">
      <strong>{{ guru.nama }}</strong>
      <p>{{ guru.jabatan }}</p>
    </div>
  </div>

  <!-- Guru & Staf Lainnya -->
  <h4 class="section-subtitle"><i class="pi pi-id-card"></i> Guru & Staf Lainnya</h4>
  <div class="guru-grid">
    <div class="guru-card" v-for="(guru, index) in selectedContent.guruStaf" :key="index">
      <strong>{{ guru.nama }}</strong>
      <p>{{ guru.jabatan }}</p>
    </div>
  </div>
</div>

  <!-- Fasilitas -->
   <!-- FASILITAS SEKOLAH -->
<div v-else-if="selectedContent.type === 'fasilitas'" class="fasilitas-section">
  <div class="fasilitas-list">
    <div class="fasilitas-item">
      <img src="@/assets/foto/Masjid.JPEG" alt="Masjid" class="fasilitas-img" />
      <div class="fasilitas-text">
        <h4>Masjid</h4>
        <p>Tempat ibadah yang nyaman dan luas untuk kegiatan spiritual siswa seperti sholat berjamaah, doa bersama, dan pesantren kilat.</p>
      </div>
    </div>
    <div class="fasilitas-item">
      <img src="@/assets/foto/Toilet.JPEG" alt="Toilet" class="fasilitas-img" />
      <div class="fasilitas-text">
        <h4>Toilet</h4>
        <p>Toilet bersih dan terawat yang selalu dijaga kebersihannya demi kenyamanan siswa dan guru.</p>
      </div>
    </div>
    <div class="fasilitas-item">
      <img src="@/assets/foto/Ruang Kelas.JPEG" alt="Ruang Kelas" class="fasilitas-img" />
      <div class="fasilitas-text">
        <h4>Ruang Kelas</h4>
        <p>Ruang kelas yang rapi, ber-AC, dan dilengkapi media pembelajaran modern untuk mendukung kegiatan belajar mengajar.</p>
      </div>
    </div>
    <div class="fasilitas-item">
      <img src="@/assets/foto/Perpustakaan.JPEG" alt="Perpustakaan" class="fasilitas-img" />
      <div class="fasilitas-text">
        <h4>Perpustakaan</h4>
        <p>Perpustakaan dengan koleksi buku lengkap dan suasana nyaman untuk meningkatkan literasi siswa.</p>
      </div>
    </div>
  </div>
</div>



  <!-- DEFAULT -->
  <p v-else>{{ selectedContent.description }}</p>
</section>

    <!-- Galeri Video Kegiatan Sekolah -->
<section class="galeri-video">
  <h2><i class="pi pi-video"></i> Galeri Video</h2>
  <p>Dokumentasi kegiatan siswa, acara sekolah, dan momen spesial lainnya di SD Plus Al Muhajirin.</p>

  <div class="video-gallery">
    <div class="video-item">
      <iframe
        src="https://www.youtube.com/embed/3ASf_OMFV3M"
        title="Video Kegiatan 1"
        frameborder="0"
        allowfullscreen
      ></iframe>
    </div>
    <div class="video-item">
      <iframe
        src="https://www.youtube.com/embed/EZOCneBGrpw"
        title="Video Kegiatan 2"
        frameborder="0"
        allowfullscreen
      ></iframe>
    </div>
    <div class="video-item">
      <iframe
        src="https://www.youtube.com/embed/YzmVLb1SJEo"
        title="Video Kegiatan 3"
        frameborder="0"
        allowfullscreen
      ></iframe>
    </div>
  </div>
</section>


    <!-- Footer (tidak diubah) -->
    <!-- Footer -->
    <footer class="footer">
      <div class="footer-container">
        <!-- Map - Pojok Kiri -->
        <div class="footer-section footer-left">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.457540569906!2d106.71000000000001!3d-6.199999999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDInMjAuMCJF!5e0!3m2!1sen!2sid!4v0000000000000!5m2!1sen!2sid"
            loading="lazy"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"
            class="footer-map"
          ></iframe>
        </div>

        <!-- SDI Al Muhajirin - Tengah -->
        <div class="footer-section footer-center">
          <h3>SD PLUS AL-MUHAJIRIN</h3>
          <p>SD Islam yang mengedepankan integrasi nilai-nilai keislaman, kekayaan budaya, serta pengembangan seni dalam setiap kegiatan belajar. Sekolah ini dikelola oleh Yayasan Taman Al Muhajirin Kota Tangerang Selatan</p>
        </div>

        <!-- Alamat - Pojok Kanan -->
        <div class="footer-section footer-right">
          <h3>Alamat Kami</h3>
          <p>Jl. Kasuari No.B. III/51, RT.1/RW.4, Pd. Pucung, Kec. Pd. Aren, Kota Tangerang Selatan.</p>
          <div class="footer-icons">
            <i class="pi pi-facebook"></i>
            <i class="pi pi-instagram"></i>
            <i class="pi pi-youtube"></i>
            <i class="pi pi-telegram"></i>
            <i class="pi pi-whatsapp"></i>
            <i class="pi pi-tiktok"></i>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>Copyright © 2025 - All Rights Reserved</p>
      </div>
    </footer>
  </div>
</template>

<script>
import Button from 'primevue/button';
import Menubar from 'primevue/menubar';
import Galleria from 'primevue/galleria';

export default {
  name: 'HomePage',
  components: { Button, Menubar, Galleria },
  data() {
    return {
      /* --- AUTH & STATE --- */
      isAuthenticated: false,
      selectedContent: null,

      /* --- BANNER IMAGES --- */
      images: [
        {
          itemImageSrc: 'https://lh5.googleusercontent.com/p/AF1QipOlUcbc17VVo_Puj3xL2pQ1qzj4r6s9aSBbKkD6=w408-h306-k-no',
          alt: 'Gedung SD Al Muhajirin',
        },
        {
          itemImageSrc: 'https://deliksatu.com/wp-content/uploads/2023/06/IMG-20230619-WA0027.jpg',
          alt: 'Wisuda SD Al Muhajirin',
        },
      ],
      responsiveOptions: [
        { breakpoint: '1024px', numVisible: 1 },
        { breakpoint: '768px', numVisible: 1 },
        { breakpoint: '560px', numVisible: 1 },
      ],
    };
  },
  methods: {
    goToLogin() {
      this.$router.push('/login');
    },
    showContent(contentType) {
      if (contentType === 'visi-misi') {
        this.selectedContent = {
          title: 'Visi dan Misi',
          type: 'visi-misi',
          visi: 'Membina akhlaq meraih prestasi, berwawasan global yang dilandasi nilai-nilai luhur sesuai dengan ajaran agama.',
          misi: [
            'Menanamkan keyakinan / akidah melalui pengamalan ajaran agama',
            'Mengoptimalkan proses pembelajran dan bimbingan',
            'Mengembangkan pengetahuan dibidang IPTEK, Bahasa, Olahraga, dan Seni Budaya sesuai dengan bakat, minat dan potensi siswa',
            'Menjalin Kerjasama yang harmonis antara warga sekolah dan lingkungan',
            'Membentuk sumberdaya manusia yang aktif, kreatif, inovatif sesuai dengan perkembangan zaman.',
            'Memberikan pelatihan-pelatihan kepada guru dalam rangka peningkatan profesionalisme.',
          ],
        };
      } else {
        const contentData = {
          prestasi: {
            title: 'Prestasi',
            type: 'prestasi',
            items: [
              {
                judul: 'Juara 1 Lomba Cerdas Cermat Tingkat Kota',
                deskripsi: 'Tim siswa SD Plus Al‑Muhajirin meraih juara 1 dalam lomba Cerdas Cermat tingkat Kota Tangerang Selatan.',
                tanggal: '15 Maret 2025',
              },
              {
                judul: 'Juara 3 Olimpiade Matematika Kota',
                deskripsi: 'Ananda Zahra Aulia meraih juara 3 pada Olimpiade Matematika Tingkat Kota.',
                tanggal: '21 Februari 2025',
              },
              {
                judul: 'Juara 2 Cerdas Cermat Pelajar se‑Pondok Aren',
                deskripsi: 'Tim futsal SD Plus Al‑Muhajirin meraih juara 2 pada cerdas cermat antar pelajar se‑Pondok Aren.',
                tanggal: '28 Januari 2025',
              },
            ],
          },
           ekstrakurikuler: {
        title: 'Ekstrakurikuler',
        type: 'ekskul',
        items: [
          {
            nama: 'Pramuka',
            deskripsi: 'Melatih kedisiplinan, kerja sama, dan kepemimpinan siswa melalui kegiatan kepramukaan.',
            icon: 'pi pi-compass',
          },
          {
            nama: 'Futsal',
            deskripsi: 'Ekstrakurikuler olahraga yang melatih fisik, strategi bermain, dan semangat sportivitas.',
            icon: 'pi pi-futbol',
          },
          {
            nama: 'Pencak Silat',
            deskripsi: 'Seni bela diri tradisional yang menanamkan ketangkasan, etika, dan kepercayaan diri.',
            icon: 'pi pi-shield',
          },
          {
            nama: 'Tahfidz Qur\'an',
            deskripsi: 'Program menghafal Al‑Qur\'an untuk meningkatkan kecintaan siswa terhadap kitab suci.',
            icon: 'pi pi-book',
          },
          {
            nama: 'Seni Tari',
            deskripsi: 'Menumbuhkan bakat seni dan budaya melalui gerakan tari tradisional maupun modern.',
            icon: 'pi pi-palette',
          },
          {
            nama: 'Robotik Dasar',
            deskripsi: 'Mengenalkan dunia teknologi dan robotika dengan cara menyenangkan dan kreatif.',
            icon: 'pi pi-cog',
          },
        ],
      },
          'program-pembelajaran': {
            title: 'Program Pembelajaran',
            type: 'text',
            description: 'Detail program pembelajaran akan segera ditampilkan.',
          },
          'guru-staf': {
  title: 'Guru dan Staf',
  type: 'guru-staf',
  walikelas: [
    { nama: 'Nela Laelah, S.Pd', jabatan: 'Guru Walikelas VI A' },
    { nama: 'Septi Istanti, S.Pd', jabatan: 'Guru Walikelas VI B' },
    { nama: 'Tri Pugawati, S.Ag', jabatan: 'Guru Walikelas V A' },
    { nama: 'Andivita Nurmiranda, S.Pd', jabatan: 'Guru Walikelas V B' },
    { nama: 'Wisnu Febrian, S.H', jabatan: 'Guru Walikelas IV A' },
    { nama: 'Intan Andini, S.Pd', jabatan: 'Guru Walikelas IV B' },
    { nama: 'Wilda Utami Rizqilah, S.H', jabatan: 'Guru Walikelas III A' },
    { nama: 'Abdul Karim Al Muslim, S.Pd', jabatan: 'Guru Walikelas III B' },
    { nama: 'Nurhayaji, S.Pd', jabatan: 'Guru Walikelas II A' },
    { nama: 'Dita Anggraini, M.Pd', jabatan: 'Guru Walikelas II B' },
    { nama: 'Eli Susanti Lubis', jabatan: 'Guru Walikelas I A' },
    { nama: 'Yusi Apriani, S.Pd', jabatan: 'Guru Walikelas I B' },
  ],
  guruStaf: [
    { nama: 'Ahmad Fauzi, S.Sy.', jabatan: 'Guru PAI' },
    { nama: 'Zerlyta Amelia Fauzi, S.Pd', jabatan: 'Guru TIK' },
    { nama: 'Nur Saddam, S.Pd', jabatan: 'Guru Bahasa Inggris' },
    { nama: 'Wahyu Aditya S.M', jabatan: 'Guru PJOK' },
    { nama: 'M. Fajrul Iman, S.H', jabatan: 'Guru Bahasa Arab' },
    { nama: 'Susanti, S.Pd.I', jabatan: 'Guru Seni Budaya' },
    { nama: 'Dimas Yudi Pratama', jabatan: 'Tata Usaha' },
    { nama: 'Ahmad Hidayat', jabatan: 'Petugas Kebersihan' },
    { nama: 'Sera Suhayya', jabatan: 'Petugas Kebersihan' },
  ],
},
          fasilitas: {
              title: 'Fasilitas',
              type: 'fasilitas',
          },
        };
        this.selectedContent = contentData[contentType];
      }
    },
  },
};
</script>

<style scoped>
/* Navbar */
/* Navbar */
.p-menubar {
  background-color: #28a745;
  padding: 0 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.school-name {
  color: #fff;
  font-size: 1.6rem;
  font-weight: 600;
}
.navbar-start {
  display: flex;
  align-items: center;
  gap: 10px;
}
.navbar-end {
  display: flex;
  align-items: center;
  gap: 20px;
}

/* Banner */
.banner-carousel {
  margin-top: 60px; /* Beri jarak dari top bar */
}

.banner-image {
  width: 100%;
  height: 450px;
  object-fit: cover;
  border-radius: 0;
}

/* Hero Section */
.hero {
  padding: 40px 20px;
  background: #f0f8ff;
  text-align: center;
}
.hero-text h1 {
  font-size: 2.2rem;
  margin-bottom: 12px;
}
.hero-text p {
  font-size: 1.2rem;
  color: #444;
}
.highlight {
  color: #28a745;
}

/* Hero */
.hero {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  padding: 40px 20px;
  background: #f0f8ff;
}
.hero-text {
  flex: 1;
}
.highlight {
  color: #088a26;
}

/* Section Profil */
.section-profil {
  padding: 40px 20px;
}
.section-profil h2 {
  font-size: 2rem;
  margin-bottom: 10px;
  color: #208003bd;
}
.profil-content {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 20px;
}
.profil-item {
  flex: 1 1 calc(33.333% - 20px);
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  padding: 20px;
  cursor: pointer;
  transition: transform 0.2s;
  text-align: center;
}
.profil-item:hover {
  transform: scale(1.05);
  background-color: #f0f8ff;
}
.icon {
  font-size: 2rem;
  color: #119b31;
  margin-bottom: 10px;
}

/* Section Content */
.section-content {
  background-color: #fff;
  margin: 20px;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}
.section-content h3 {
  color: #0e0f0f;
  margin-bottom: 20px;
}

/* Visi Misi Style */
.visi-section,
.misi-section {
  margin-bottom: 20px;
}
.visi-section h4,
.misi-section h4 {
  font-size: 1.3rem;
  color: #0c0c0c;
  margin-bottom: 10px;
  border-left: 4px solid #0c0e0d;
  padding-left: 10px;
}
.visi-section p {
  background-color: #e6f0ff;
  padding: 15px 20px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0, 123, 255, 0.1);
}
.misi-section ul {
  padding-left: 20px;
  background-color: #f5faff;
  padding: 15px 20px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0, 123, 255, 0.1);
}
.misi-section li {
  margin-bottom: 10px;
  display: flex;
  align-items: center;
}

/* Prestasi Section */
.prestasi-section {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-top: 20px;
}

.prestasi-card {
  display: flex;
  background-color: #f0f8ff;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 123, 255, 0.1);
  padding: 20px;
  align-items: flex-start;
  transition: transform 0.2s;
}

.prestasi-card:hover {
  transform: translateY(-4px);
}

.prestasi-icon {
  flex-shrink: 0;
  font-size: 2rem;
  color: #ffd700;
  margin-right: 20px;
}

.prestasi-info h4 {
  margin: 0 0 8px;
  color: #020e04;
  font-size: 1.25rem;
  font-weight: bold;
}

.prestasi-info p {
  margin: 0 0 10px;
  line-height: 1.6;
  font-size: 1rem;
}

.prestasi-tanggal {
  font-size: 0.85rem;
  color: #888;
}

/* Ekskul Section */
.ekskul-section {
  padding: 40px 20px;
}

.ekskul-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.ekskul-card {
  background-color: #f9fff9;
  border: 1px solid #d1f0d4;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 128, 0, 0.05);
  display: flex;
  align-items: flex-start;
  transition: transform 0.2s, box-shadow 0.2s;
}

.ekskul-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 12px rgba(0, 128, 0, 0.1);
}

.ekskul-icon {
  font-size: 2rem;
  color: #28a745;
  margin-right: 16px;
  flex-shrink: 0;
}

.ekskul-info h4 {
  margin: 0 0 8px;
  font-size: 1.2rem;
  color: #155724;
}

.ekskul-info p {
  margin: 0;
  color: #444;
  font-size: 0.95rem;
}

.guru-staf-section {
  margin-top: 2rem;
}

.section-subtitle {
  margin: 1.5rem 0 0.5rem;
  font-size: 1.2rem;
  font-weight: bold;
  color: #2c3e50;
}

.guru-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.guru-card {
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 1rem;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  transition: transform 0.3s ease;
}
.guru-card:hover {
  transform: scale(1.03);
  background-color: #fff;
  border-color: #8ab4f8;
}


/* Fasilitas */
.fasilitas-section {
  padding: 40px 20px;
  background-color: #f9fff9;
}

.fasilitas-list {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.fasilitas-item {
  display: flex;
  align-items: flex-start;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 128, 0, 0.08);
  overflow: hidden;
  transition: transform 0.3s ease;
}

.fasilitas-item:hover {
  transform: translateY(-5px);
}

.fasilitas-img {
  width: 180px;
  height: 140px;
  object-fit: cover;
  border-top-left-radius: 12px;
  border-bottom-left-radius: 12px;
}

.fasilitas-text {
  padding: 20px;
}

.fasilitas-text h4 {
  margin: 0 0 10px;
  font-size: 1.3rem;
  color: #2d6a4f;
}

.fasilitas-text p {
  margin: 0;
  color: #444;
  font-size: 1rem;
  line-height: 1.6;
}

@media (max-width: 768px) {
  .fasilitas-item {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .fasilitas-img {
    width: 100%;
    height: 200px;
    border-radius: 0;
  }

  .fasilitas-text {
    padding: 15px;
  }
}


/* FOOTER */
.footer {
  background: #28a745;
  color: #fff;
  padding: 50px 40px 20px;
  font-size: 1rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* ---------- GRID LAYOUT ---------- */
.footer-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  column-gap: 80px;
  align-items: flex-start;
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  row-gap: 40px;
}

/* Setiap kolom */
.footer-section {
  box-sizing: border-box;
}

/* MAP – kiri */
.footer-map {
  width: 100%;
  height: 250px;
  border: 0;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

/* TENGAH */
.footer-center {
  text-align: center;
}
.footer-center h3 {
  font-size: 1.6rem;
  margin-bottom: 16px;
  font-weight: 700;
}
.footer-center p {
  font-size: 1.05rem;
  line-height: 1.8;
  max-width: 500px;
  margin: 0 auto;
}

/* KANAN */
.footer-right {
  text-align: right;
}
.footer-right h3 {
  font-size: 1.3rem;
  margin-bottom: 14px;
  font-weight: 600;
}
.footer-right p {
  font-size: 1.05rem;
  line-height: 1.8;
}

/* ICON Sosmed */
.footer-icons {
  display: flex;
  justify-content: flex-end;
  gap: 20px;
  margin-top: 18px;
  flex-wrap: wrap;
}
.footer-icons i {
  font-size: 1.4rem;
  cursor: pointer;
  transition: color 0.3s;
}
.footer-icons i:hover {
  color: #00ff73;
}

/* BOTTOM BAR */
.footer-bottom {
  margin-top: 40px;
  text-align: center;
  border-top: 1px solid #ffffff44;
  padding-top: 18px;
  color: #ffffffcc;
  font-size: 0.9rem;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 768px) {
  .footer-container {
    grid-template-columns: 1fr;
    text-align: center;
  }
  .footer-right {
    text-align: center;
  }
  .footer-icons {
    justify-content: center;
  }
  .footer-center p {
    margin: 0 auto;
  }
}
/* Galeri Video */
.galeri-video {
  padding: 50px 20px;
  text-align: center;
  background-color: #f9fff9;
}

.galeri-video h2 {
  font-size: 2rem;
  color: #207b2d;
  margin-bottom: 10px;
}

.galeri-video p {
  font-size: 1.1rem;
  color: #555;
  margin-bottom: 30px;
}

.video-gallery {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 25px;
  justify-content: center;
}

.video-item {
  width: 100%;
  aspect-ratio: 16 / 9;
  overflow: hidden;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  background: #000;
}

.video-item iframe {
  width: 100%;
  height: 100%;
  border: none;
  border-radius: 12px;
}

</style>

  <template>
    <div class="admin-dashboard">
      <div class="sidebar">
        <div class="sidebar-header">
  <img src="@/assets/foto/Logo Sekolah.png" alt="Logo Sekolah" class="school-logo" />
</div>
        <ul>
          <li @click="showForm('dashboard-guru')"><i class="fa fa-tachometer-alt"></i> Dashboard</li>
          <li @click="showForm('data-siswa-guru')"><i class="fa fa-users"></i> Data Siswa</li>
          <li @click="showForm('jadwal-pelajaran-kelas')"><i class="fa fa-calendar-alt"></i> Jadwal Pelajaran</li>
          <li @click="showForm('tanggapan')"><i class="fa fa-comment-dots"></i> Kritik Dan Saran</li>
          <li @click="showForm('nilai-form')"><i class="fa fa-pen-square"></i> Input Nilai Siswa</li>
          <li @click="showForm('nilai-all-siswa')"><i class="fa fa-chart-bar"></i> Detail Nilai Siswa</li>
          <li @click="showForm('nilai-all')"><i class="fa fa-chart-bar"></i> Cetak Rapot Nilai Siswa</li>
          <li @click="showForm('nilai-grouper')"><i class="fa fa-chart-bar"></i> Laporan Penilaian</li>
          <li @click="showForm('cetaktanggapan')"><i class="fa fa-chart-bar"></i> Laporan Kritik Saran</li>
        </ul>
      </div>

      <div class="content">
        <div class="navbar">
          <div class="navbar-left">
            <h1>Walikelas DASHBOARD</h1>
          </div>
          <div class="navbar-right">
            <div class="profile-dropdown" @click.stop="toggleDropdown">
              <i class="fa fa-user-circle"></i>
              <div v-if="dropdownVisible" class="dropdown-menu">
                <p>{{ user.username }}</p>
                <button @click="editPassword">Ganti Password</button>
                <button @click="logout" class="logout-button">Logout</button>
              </div>
            </div>
          </div>
        </div>

        <Dialog v-model:visible="showPasswordModal" modal header="Edit Password" :style="{ width: '600px' }">
          <form @submit.prevent="submitEditPassword">
            <div class="field">
              <label for="newPassword">Password Baru</label>
              <Password v-model="passwordForm.password" toggleMask :feedback="false" id="newPassword" class="w-full" required />
            </div>
            <div class="field">
              <label for="confirmPassword">Konfirmasi Password Baru</label>
              <Password v-model="passwordForm.password_confirmation" toggleMask :feedback="false" id="confirmPassword" class="w-full" required />
            </div>
            <Button label="Update Password" type="submit" class="mt-3 w-full" :loading="loading" />
          </form>
        </Dialog>

        <div v-if="selectedForm === 'dashboard-guru'" class="form-container">
          <DashboardDefaultGuru />
        </div>
        <div v-if="selectedForm === 'data-siswa-guru'" class="form-container">
          <DataSiswaGuru />
        </div>
        <div v-if="selectedForm === 'jadwal-pelajaran-kelas'" class="form-container">
          <JadwalPelajaranPerGuruKelas />
        </div>
        <div v-if="selectedForm === 'nilai-form'" class="form-container">
          <NilaiForm />
        </div>
        <div v-if="selectedForm === 'nilai-all-siswa'" class="form-container">
          <NilaiSiswaPerGuru />
        </div>
        <div v-if="selectedForm === 'nilai-all'" class="form-container">
          <NilaiAll />
        </div>
        <div v-if="selectedForm === 'nilai-grouper'" class="form-container">
          <CetakRaportGroup />
        </div>
        <div v-if="selectedForm === 'tanggapan'" class="form-container">
          <Tanggapan />
        </div>
        <div v-if="selectedForm === 'cetaktanggapan'" class="form-container">
          <CetakTanggapan />
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted, onBeforeUnmount } from 'vue';
  import axios from 'axios';
  import Dialog from 'primevue/dialog';
  import Password from 'primevue/password';
  import Button from 'primevue/button';

  import DashboardDefaultGuru from './DashboardDefaultGuru.vue';
  import DataSiswaGuru from '../masterdata/DataSiswaPerGuru.vue';
  import JadwalPelajaranPerGuruKelas from '../masterdata/JadwalPelajaranPerGuruKelas.vue';
  import NilaiForm from '../akademis/NilaiForm.vue';
  import NilaiSiswaPerGuru from '../masterdata/NilaiSiswaPerGuru.vue';
  import NilaiAll from '../masterdata/NilaiSiswaAll.vue';
  import Tanggapan from '../forms/Tanggapan.vue';
  import CetakTanggapan from '../forms/CetakTanggapan.vue';
  import CetakRaportGroup from '../masterdata/AllLaporanSiswaPerKelas.vue';

  const selectedForm = ref('dashboard-guru');
  const dropdownVisible = ref(false);
  const showPasswordModal = ref(false);
  const loading = ref(false);

  const user = {
    username: localStorage.getItem('username') || 'Tidak Dikenal',
  };

  const passwordForm = ref({
    user_id: localStorage.getItem('user_id'),
    password: '',
    password_confirmation: '',
  });

  const showForm = (formType) => {
    selectedForm.value = formType;
  };

  const toggleDropdown = () => {
    dropdownVisible.value = !dropdownVisible.value;
  };

  const closeDropdown = () => {
    dropdownVisible.value = false;
  };

  const logout = () => {
    localStorage.removeItem('access_token');
    localStorage.removeItem('role');
    localStorage.removeItem('username');
    localStorage.removeItem('user_id');
    window.location.href = '/login';
  };

  const editPassword = () => {
    showPasswordModal.value = true;
  };

  const submitEditPassword = async () => {
    loading.value = true;
    try {
      await axios.put(`http://127.0.0.1:8000/api/users/${passwordForm.value.user_id}/password`, passwordForm.value);
      toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Password berhasil diubah', life: 3000 });
      showPasswordModal.value = false;
    } catch (err) {
      toast.add({
        severity: 'error',
        summary: 'Gagal',
        detail: err.response?.data?.message || 'Gagal update password',
        life: 3000,
      });
    } finally {
      loading.value = false;
    }
  };

  onMounted(() => {
    document.addEventListener('click', closeDropdown);
  });

  onBeforeUnmount(() => {
    document.removeEventListener('click', closeDropdown);
  });
  </script>
<style scoped>
.admin-dashboard {
  display: flex;
  flex-direction: row;
  height: 100vh;
  flex-wrap: wrap;
}

/* ==== SIDEBAR ==== */
.sidebar {
  width: 280px;
  background: linear-gradient(to bottom, #1f2937, #374151); /* abu-abu gelap elegan */
  color: white;
  padding: 30px;
  display: flex;
  flex-direction: column;
  border-radius: 10px;
}

.sidebar-header {
  margin-bottom: 40px;
  text-align: center;
}

.sidebar ul {
  list-style-type: none;
  padding: 0;
}

.sidebar ul li {
  margin: 20px 0;
  cursor: pointer;
  font-size: 1.2rem;
  display: flex;
  align-items: center;
  transition: all 0.3s ease;
}

.sidebar ul li:hover {
  background-color: #4b5563; /* abu-abu sedikit terang */
  padding-left: 15px;
  border-radius: 5px;
}

.sidebar ul li i {
  margin-right: 20px;
  font-size: 1.4rem;
}

/* ==== NAVBAR ==== */
.navbar {
  background-color: #1f2937; /* abu-abu gelap */
  color: white;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-radius: 10px 10px 0 0;
}

/* ==== CONTENT ==== */
.content {
  flex-grow: 1;
  padding: 30px;
  background-color: #f9f9f9;
  border-radius: 10px;
  min-width: 0;
  overflow-x: auto;
}

.profile-dropdown {
  position: relative;
}

.profile-dropdown i {
  font-size: 3rem;
  cursor: pointer;
  color: white;
}

/* ==== DROPDOWN MENU ==== */
.dropdown-menu {
  position: absolute;
  top: 35px;
  right: 0;
  background-color: #f9fafb; /* abu-abu terang */
  color: #1f2937; /* abu-abu gelap */
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  width: 180px;
  font-size: 1.2rem;
  z-index: 999;
}

.dropdown-menu p {
  margin: 10px 0;
}

.dropdown-menu button {
  background-color: #374151; /* abu-abu sedang */
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
  margin-bottom: 10px;
}

.dropdown-menu button:hover {
  background-color: #4b5563;
}

.logout-button {
  background-color: #d9534f;
}

.logout-button:hover {
  background-color: #c9302c;
}

/* ==== FORM CONTAINER ==== */
.form-container {
  background-color: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  margin-top: 20px;
}

/* ==== PASSWORD MODAL ==== */
.field {
  margin-bottom: 1rem;
}

.w-full {
  width: 100%;
}

.mt-3 {
  margin-top: 1rem;
}

/* ==== RESPONSIVE ==== */
@media screen and (max-width: 768px) {
  .admin-dashboard {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    padding: 10px 5px;
    flex-direction: row;
    overflow-x: auto;
    border-radius: 0;
  }

  .sidebar ul {
    display: flex;
    flex-wrap: nowrap;
    width: max-content;
    padding: 0;
    margin: 0;
  }

  .sidebar ul li {
    flex: 0 0 auto;
    padding: 8px 12px;
    font-size: 0.95rem;
    white-space: nowrap;
    display: flex;
    align-items: center;
  }

  .sidebar ul li i {
    margin-right: 8px;
    font-size: 1.1rem;
  }

  .content {
    padding: 12px;
  }

  .navbar {
    flex-direction: column;
    gap: 8px;
    padding: 15px;
    text-align: center;
  }

  .profile-dropdown i {
    font-size: 2.2rem;
  }

  .dropdown-menu {
    right: auto;
    left: 0;
    width: 100%;
    padding: 15px;
  }

  .dropdown-menu button {
    font-size: 1rem;
    padding: 8px 12px;
  }

  .form-container {
    padding: 20px;
  }
}
.school-logo {
  width: 100px;
  height: auto;
  margin: 0 auto 10px auto;
  display: block;
  border-radius: 8px;
}
</style>


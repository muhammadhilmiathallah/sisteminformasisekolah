<template>
  <div class="login-page">
    <div class="login-container">
      <h1>Selamat Datang</h1>
      <p class="welcome-message">Portal Sekolah Dasar Al Muhajirin</p>

      <form @submit.prevent="login">
        <div class="input-group">
          <input v-model="username" type="text" placeholder="Username" required />
        </div>
        <div class="input-group">
          <input v-model="password" type="password" placeholder="Password" required />
        </div>

        <button type="submit" class="submit-btn">Masuk</button>

        <!-- Tombol kembali ke beranda di bawah login -->
        <button type="button" class="back-btn" @click="goHome">← Kembali ke Beranda</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      username: '',
      password: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/login', {
          username: this.username,
          password: this.password,
        });

        if (response.data.access_token) {
          localStorage.setItem('access_token', response.data.access_token);
          localStorage.setItem('user_id', response.data.user_id);
          localStorage.setItem('username', this.username);
          localStorage.setItem('role', response.data.role);
          axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;

          this.$toast.add({
            severity: 'success',
            summary: 'Login Berhasil',
            detail: `Selamat datang, ${this.username}!`,
            life: 3000,
          });

          const role = response.data.role;
          if (role === 'Admin Sekolah') {
            this.$router.push('/dashboard/admin');
          } else if (role === 'Guru') {
            this.$router.push('/dashboard/guru');
          } else if (role === 'Siswa') {
            this.$router.push('/dashboard/siswa');
          }
        } else {
          this.$toast.add({
            severity: 'error',
            summary: 'Login Gagal',
            detail: 'Token tidak diterima.',
            life: 3000,
          });
        }
      } catch (error) {
        this.$toast.add({
          severity: 'error',
          summary: 'Login Gagal',
          detail: 'Username atau Password salah.',
          life: 3000,
        });
      }
    },
    goHome() {
      this.$router.push('/');
    },
  },
};
</script>

<style scoped>
.login-page {
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #a1c4fd, #c2e9fb);
  height: 100vh;
  padding: 20px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.login-container {
  background-color: #fff;
  padding: 40px 30px;
  border-radius: 16px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 420px;
  text-align: center;
}

h1 {
  font-size: 2rem;
  color: #2e7d32;
  margin-bottom: 10px;
}

.welcome-message {
  font-size: 1rem;
  color: #555;
  margin-bottom: 25px;
}

.input-group {
  margin-bottom: 20px;
}

input {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #ccc;
  border-radius: 10px;
  font-size: 1rem;
  background-color: #f9f9f9;
  transition: 0.3s;
}

input:focus {
  border-color: #2e7d32;
  background-color: white;
  outline: none;
  box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
}

.submit-btn {
  width: 100%;
  padding: 12px;
  background-color: #2e7d32;
  border: none;
  color: white;
  font-size: 1.1rem;
  font-weight: bold;
  border-radius: 10px;
  cursor: pointer;
  margin-bottom: 15px;
  transition: background-color 0.3s ease;
}

.submit-btn:hover {
  background-color: #388e3c;
}

.back-btn {
  width: 100%;
  padding: 10px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.back-btn:hover {
  background-color: #c82333;
}

@media (max-width: 480px) {
  .login-container {
    padding: 30px 20px;
  }

  h1 {
    font-size: 1.6rem;
  }

  .welcome-message {
    font-size: 0.95rem;
  }
}
</style>

<template>
  <div id="login">
    <div class="login-container">
      <h2>Login</h2>
      <form @submit.prevent="handleSubmit">
        <div class="input-group">
          <label for="email">Username</label>
          <input v-model="email" type="email" id="email" placeholder="Ingresa tu correo" />
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input v-model="password" type="password" id="password" placeholder="Ingresa tu contraseña" />
        </div>
        <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        <button type="submit" class="submit-btn">Ingresar</button>
      </form>
    </div>
  </div>
</template>
  
<script>
  import { ref } from 'vue';
  import router from '../router';
  import env from "@/env.js";

  export default {
    name: "LoginTodo",
    setup() {
      const email = ref('');
      const password = ref('');
      const errorMessage = ref('');

      const handleSubmit = async () => {
      errorMessage.value = '';
      console.log(!email.value,!password.value);
      if(!email.value) return errorMessage.value = 'Debe Ingresar un correo';
      if(!password.value) return errorMessage.value = 'Debe ingresar una clave';
      // Replace with your actual API call
      const response = await fetch(`${env.url}/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          email: email.value,
          password: password.value,
        }),
      });
      const data = await response.json();
      if (data.access_token) {
        // Save the token in localStorage, bad practice
        localStorage.setItem('t', data.access_token);
        localStorage.setItem('u', data.user_name);
        // Navigate to the TodoApp component
        router.push('/todo');
      } else {
        // Handle login error
        errorMessage.value = 'Sus credenciales no coinciden';
      }
    };

    return {
      email,
      password,
      errorMessage,
      handleSubmit,
    };

    },
  };
</script>
  
<style scoped>
  body{
    background-color: #A0D995 !important;
  }
  #login {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 90vh;
  }
  
  .login-container {
    width: 100%;
    max-width: 400px;
    background-color: #a0d9954a;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    font-size: 1.5em;
  }
  
  h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #3a7f8b;
  }
  
  .input-group {
    margin-bottom: 20px;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
    color: #3a7f8b;
  }
  
  input {
    display: block;
    width: 100%;
    padding: 10px;
    border: 2px solid #6CC4A1;
    border-radius: 5px;
    font-size: 14px;
    background-color: #f6e3c580;
  }
  
  button.submit-btn {
    width: 100%;
    padding: 10px;
    background-color: #4CACBC;
    border: none;
    border-radius: 5px;
    color: #ffffff;
    font-size: 16px;
    cursor: pointer;
  }
  
  button.submit-btn:hover {
    background-color: #3b9ca8;
  }

  @media (max-width: 450px) {
    .login-container{
      background-color: transparent;
    }
  }

</style>
  
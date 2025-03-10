<template>
  <nav class="navbar navbar-light bg-light center">
      <div class="navbar-nav flex-row">
        <router-link class="nav-item nav-link" to="/">Home</router-link>
        <router-link class="nav-item nav-link" to="/cadastro">Usuários</router-link>
      </div>
  </nav>
</template>

<script>
export default {
  name: "NavbarComponent",
};
</script>

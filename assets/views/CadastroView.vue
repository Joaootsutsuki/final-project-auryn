<template>
  <div>

    <nav class="navbar navbar-dark bg-mynav">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">My App</a>
      </div>
    </nav>

    <div class="container">
      <div class="d-flex bd-highlight mb-3">
        <div class="me-auto p-2 bd-highlight">
          <h2>Usuários</h2>
        </div>
        <div class="p-2 bd-highlight">
          <button type="button" class="btn btn-secondary" @click="showUserCreateBox">Criar novo usuário</button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Nome</th>
              <th scope="col">Telefone</th>
              <th scope="col">Nascimento</th>
              <th scope="col">Email</th>
              <th scope="col">CPF</th>
              <th scope="col">Ação</th>

            </tr>
          </thead>
          <tbody>
            <tr v-if="users.length === 0">
              <th scope="row" colspan="6">Loading...</th>
            </tr>
            <tr v-else v-for="(user, index) in users" :key="user.id">
              <th scope="row">{{ user.id }}</th>
              <td>{{ user.nome }}</td>
              <td>{{ user.telefone }}</td>
              <td>{{ user.nascimento }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.cpf }}</td>

              <td>
                <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from 'sweetalert2';
import axios from 'axios';

function formatTelefone(telefone) {
  return telefone.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
}

function formatCPF(cpf) {
  return cpf.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1.$2.$3-$4');
}

function formatNascimento(nascimento) {
  return nascimento.split(' ')[0].split('-').reverse().join('/');
}



export default {
  data() {
    return {
      users: [],
    };
  },
  methods: {

    async fetchUsers() {
      try {
        const response = await axios.get('http://localhost:8000/api/usuarios');

        this.users = response.data.map(user => ({
          id: user.id,
          nome: user.nome,
          telefone: user.telefone ? formatTelefone(user.telefone) : '',
          cpf: user.cpf ? formatCPF(user.cpf) : '',
          nascimento: user.nascimento && user.nascimento.date
            ? formatNascimento(user.nascimento.date)
            : '',
          email: user.email,
        }));


      } catch (error) {
        console.error("Erro ao buscar usuários:", error);
      }
    },

    showUserCreateBox() {
      Swal.fire({
        title: 'Criar Usuário', 
        html: `
            <div class="form-group">
                <input id="nome" class="swal2-input" placeholder="Nome">
                <input id="telefone" class="swal2-input" placeholder="Telefone" type="tel">
                <input id="cpf" class="swal2-input" placeholder="CPF" type="text">
                <input id="nascimento" class="swal2-input" placeholder="Nascimento (DD MM AAAA)" type="text">
                <input id="email" class="swal2-input" placeholder="Email" type="email">
                <input id="senha" class="swal2-input" placeholder="Senha" type="password">
                <input id="repetirSenha" class="swal2-input" placeholder="Repetir Senha" type="password">
            </div>
        `,
        focusConfirm: false, 
        confirmButtonText: 'Inserir',  
        cancelButtonText: 'Cancelar', 
        preConfirm: () => {
          const nome = document.getElementById('nome').value;
          const telefone = document.getElementById('telefone').value;
          const cpf = document.getElementById('cpf').value;
          const nascimento = document.getElementById('nascimento').value;
          const email = document.getElementById('email').value;
          const senha = document.getElementById('senha').value;
          const repetirSenha = document.getElementById('repetirSenha').value;

          if (!nome || !telefone || !cpf || !nascimento || !email || !senha || !repetirSenha) {
            Swal.showValidationMessage('Todos os campos são obrigatórios!');
            return false;
          }

          if (senha !== repetirSenha) {
            Swal.showValidationMessage('As senhas não coincidem!');
            return false;
          }

          return {
            nome,
            telefone,
            cpf,
            nascimento,
            email,
            senha
          };
        }
      }).then(result => {
        if (result.isConfirmed) {
          axios.post('http://localhost:8000/api/usuario', {
            nome: result.value.nome,
            telefone: result.value.telefone,
            cpf: result.value.cpf,
            nascimento: result.value.nascimento,
            email: result.value.email,
            senha: result.value.senha,
          })
            .then(response => {
              Swal.fire('Sucesso!', 'Usuário criado com sucesso!', 'success');
            })
            .catch(error => {
              console.error('Erro ao criar usuário:', error);
              Swal.fire('Erro!', 'Não foi possível criar o usuário.', 'error');
            });
        }
      });
    }
    ,
    deleteUser(id) {
      this.users = this.users.filter(user => user.id !== id);
      Swal.fire('Deleted!', 'User has been deleted.', 'success');
    }
  },
  mounted() {
    this.fetchUsers();
  }
};
</script>

<style>
.bg-mynav {
  background-color: #343a40;
}
</style>
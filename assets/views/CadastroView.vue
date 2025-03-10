<template>
  <main>
    <div class="container">
      <div class="d-flex bd-highlight mb-3">
        <div class="me-auto p-2 bd-highlight">
          <h2>Usuários</h2>
        </div>
        <div class="p-2 bd-highlight">
          <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#userModal">
            Criar novo usuário
          </button>
        </div>
      </div>

      <!-- Tabela de Usuários-->
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
            <tr v-else v-for="user in users" :key="user.id">
              <th scope="row">{{ user.id }}</th>
              <td>{{ user.nome }}</td>
              <td>{{ formatTelefone(user.telefone) }}</td>
              <td>{{ formatNascimento(user.nascimento.date) }}</td>
              <td>{{ user.email }}</td>
              <td>{{ formatCPF(user.cpf) }}</td>
              <td>
                <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Criar Usuário</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="createUser">
              <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" class="form-control" v-model="newUser.nome" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Telefone</label>
                <input v-mask="'(##) #####-####'" v-model="newUser.telefone" placeholder="(00) 00000-0000" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">CPF</label>
                <input type="text" class="form-control" v-model="newUser.cpf" v-mask="'###.###.###-##'" placeholder="000.000.000-00" required >
              </div>
              <div class="mb-3">
                <label class="form-label">Nascimento</label>
                <input type="date" class="form-control" v-model="newUser.nascimento" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" v-model="newUser.email" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" class="form-control" v-model="newUser.senha" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Repetir Senha</label>
                <input type="password" class="form-control" v-model="newUser.repetirSenha" required>
              </div>
              <button type="submit" class="btn btn-primary ">Inserir</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  data() {
    return {
      users: [],
      newUser: {
        nome: '',
        telefone: '',
        cpf: '',
        nascimento: '',
        email: '',
        senha: '',
        repetirSenha: ''
      }
    };
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await axios.get('http://localhost:8000/api/usuarios');
        this.users = response.data;
      } catch (error) {
        console.error("Erro ao buscar usuários:", error);
      }
    },
    
    async createUser() {
      const telefoneSemMascara = this.newUser.telefone.replace(/\D/g, ''); 
      const cpfSemMascara = this.newUser.cpf.replace(/\D/g, '');

      if (telefoneSemMascara.length !== 11) {
        await Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'O telefone deve ter 11 dígitos!',
        });
        return;
      }

      if (cpfSemMascara.length !== 11) {
        await Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'O CPF deve ter 11 dígitos!',
        });
        return;
      }

      if (this.newUser.senha !== this.newUser.repetirSenha) {
        await Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'As senhas não coincidem!',
        });
        return;
      }

      try {
        await axios.post('http://localhost:8000/api/usuario', this.newUser, {
          headers: {
            'Content-Type': 'application/json' 
          }
        });

        await Swal.fire({
          icon: 'success',
          title: 'Sucesso!',
          text: 'Usuário criado com sucesso!',
        });

        this.fetchUsers();
        this.resetForm();
      } catch (error) {
        console.error('Erro ao criar usuário:', error);

        await Swal.fire({
          icon: 'error',
          title: 'Erro',
          text: 'Erro ao criar usuário.',
        });
      }
    },

    async deleteUser(id) {
      const result = await Swal.fire({
        title: "Você tem certeza?",
        text: "Você não poderá reverter essa ação!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar"
      });

      if(result.isConfirmed){
        try{
          await axios.delete(`http://localhost:8000/api/usuario/${id}`);

          await Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: 'Usuário excluido com sucesso!',
          });

          this.fetchUsers();
        }catch(error){
          console.log('Erro ao excluir usuário: ', error);

          await Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Erro ao excluir usuário',
          })

        }
      }
    },

    resetForm() {
      this.newUser = {
        nome: '',
        telefone: '',
        cpf: '',
        nascimento: '',
        email: '',
        senha: '',
        repetirSenha: ''
      };
    },
    formatCPF(cpf) {
      if (!cpf) return '';
      return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    },

    formatTelefone(telefone) {
      if (!telefone) return '';
      return telefone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    },

    formatNascimento(data) {
      if (!data) return '';
      return data.split(' ')[0].split('-').reverse().join('/');
    },
  },
  
  mounted() {
    this.fetchUsers();
  }
};
</script>

<style>

</style>

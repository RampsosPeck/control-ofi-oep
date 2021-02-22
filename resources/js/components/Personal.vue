<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
              <div class="col-md-12 mx-auto">
                <div class="card border-0 bg-light mb-3 shadow-sm">
                  <div class="card-header cyane">
                    <h3 class="card-title"><b>LISTA DEL PERSONAL</b></h3>
                    <div class="card-tools">
                      <button class="btn btn-outline-primary" @click="newModal"><i class="fas fa-user-plus"></i> Nuevo Personal</button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-0">
                    <table class="table table-striped">
                      <tbody>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Datos</th>
                        <th class="text-center">Cédula</th>
                        <th class="text-center">Cargo</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Acciones</th>
                      </tr>
                      <tr v-for="(user, key, index)  in users.data" :key="user.id" >
                        <td v-text="user.id"></td>
                        <td>{{ user.name }}
                          <small v-if="user.telefono"><b>Celular: </b></small> {{ user.telefono }}
                          <small v-if="user.email"><b>E-mail: </b></small> {{ user.email }}
                          <small v-if="user.bio"><b>Biografía: </b></small> {{ user.bio }}
                        </td>
                        <td v-text="user.cedula"></td>
                        <td class="text-center">{{ user.cargo.nombre | upText }}</td>
                        <td class="text-center"> {{ user.created_at | myDate }}</td>
                        <td class="text-center ">
                            <a href="#" @click="editModal(user)" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-edit blue">Editar</i>
                            </a>
                        </td>
                      </tr>
                    </tbody></table>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer cyane">
                      <pagination :data="users" @pagination-change-page="getResults"></pagination>
                  </div>
                </div>
                <!-- /.card -->
              </div>
            </div>
        </div>


    <div v-if="!$gate.isAdminOrAuthor()">
        <not-found></not-found>
    </div>


<!-- Modal -->
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header sidebar-dark-primary text-white">
        <h5 class="modal-title" v-show="!editmode" id="addNewLabel"><b>NUEVO PERSONAL</b></h5>
        <h5 class="modal-title" v-show="editmode" id="addNewLabel"><b>ACTUALIZAR DATOS DEL PERSONAL</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <form @submit.prevent="editmode  ? updateUser() : createUser()" >
      <div class="modal-body">
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Nombre completo:</label>
                <div class="col-sm-8">
                  <input v-model="form.name" type="text" name="name" placeholder="Ingrese su nombre completo" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input v-model="form.cedula" type="text" name="cedula" placeholder="Número de carnet de identidad" class="form-control" :class="{ 'is-invalid': form.errors.has('cedula') }">
                    <has-error :form="form" field="cedula"></has-error>
                </div>
                <div class="form-group col-md-6">
                    <input v-model="form.telefono" type="text" name="telefono" placeholder="Número de celular (Opcional)" class="form-control" :class="{ 'is-invalid': form.errors.has('telefono') }">
                    <has-error :form="form" field="telefono"></has-error>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label">Correo electrónico:</label>
                <div class="col-sm-8">
                  <input v-model="form.email" type="email" name="email" placeholder="Ingrese su E-mail. (opcional)" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                  <has-error :form="form" field="email"></has-error>
                </div>
            </div>
            <div class="form-group">
              <textarea v-model="form.bio" type="text" name="bio" id="bio"
                placeholder="Alguna descripción (opcional)"
                class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
              <has-error :form="form" field="bio"></has-error>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Cargo a ocupar:</label>
                <div class="col-sm-8">
                    <select v-model="form.cargo_id">
                      <option v-for="cargo in cargos" v-bind:value="cargo.id" >
                        {{ cargo.nombre }}
                      </option>
                    </select>
                    <has-error :form="form" field="form.cargo_id"></has-error>
                </div>
            </div>
      </div>
      <div class="modal-footer text-center cyane">
        <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
        <button v-show="editmode" type="submit" class="btn btn-info">ACTUALIZAR</button>
        <button v-show="!editmode" type="submit" class="btn btn-success">GUARDAR DATOS</button>
      </div>
     </form>
    </div>
  </div>
</div>

     </div>
</template>

<script>
    export default {
        data() {
            return {
                editmode : false,
                users : {},
                cargos : {},
                form: new Form({
                    id:'',
                    name: '',
                    cedula: '',
                    telefono: '',
                    email: '',
                    cargo_id: '',
                    type: '',
                    bio: '',
                    photo: ''
                })
            }
        },
        methods:{
            getResults(page = 1) {
              axios.get('api/user?page=' + page)
                .then(response => {
                  this.users = response.data;
                });
            },
            updateUser(id){
                this.$Progress.start();
                this.form.put('api/user/'+this.form.id)
                .then(() => {
                    $('#addNew').modal('hide');
                    swal.fire(
                        'Excelente!',
                        'Se actualizó la información del usuario.',
                        'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    swal.fire(
                        'Oops...!',
                        'Algo salió mal.',
                        'warning'
                    )
                    this.$Progress.fail();
                });
            },
            editModal(user){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            loadUsers(){
                if(this.$gate.isAdminOrAuthor()){
                  axios.get('api/personas').then(({data}) => (this.users = data));
                  axios.get("api/cargo").then(({data}) => (this.cargos = data.data));
                }
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                .then((result)=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    //toast.fire({
                    swal.fire({
                      type:  'success',
                      title: `<b>${result.data.message}</b>`,
                      text: "Bienvenido al TED - Potosí!",
                      showConfirmButton: false,
                      timer: 2000
                    })
                    this.$Progress.finish();
                })
                .catch(()=>{
                    swal.fire(
                        'Oops...!',
                        'Algo salió mal.',
                        'warning'
                    )
                    this.$Progress.fail();
                })

            }
        },
        created() {
            Fire.$on('searching',()=> {
              let query = this.$parent.search;
              axios.get('api/findUser?q=' + query)
              .then((data)=>{
                  this.users = data.data
              })
              .catch(()=>{

              })
            })
            this.loadUsers();
            Fire.$on('AfterCreate',() => {
                this.loadUsers();
            });

        }
    }
</script>
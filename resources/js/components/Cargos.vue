<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
              <div class="col-md-12 mx-auto">
                <div class="card border-0 bg-light mb-3 shadow-sm">
                  <div class="card-header cyane">
                    <h3 class="card-title"><b>LISTA DE CARGOS</b></h3>

                    <div class="card-tools">
                      <button class="btn btn-outline-primary" @click="newModal"><i class="fas fa-user-plus"></i> Nuevo Cargo</button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-0">
                    <table class="table table-striped">
                      <tbody>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Acciones</th>
                      </tr>
                      <tr v-for="(cargo, key, index)  in cargos.data" :key="cargo.id" >
                        <td v-text="cargo.id"></td>
                        <td v-text="cargo.nombre"></td>
                        <td class="text-center"> {{ cargo.created_at | myDate }}</td>
                        <td class="text-center">
                            <a href="#" @click="editModal(cargo)">
                                <i class="fas fa-edit blue"></i>
                            </a> /
                            <a href="#" @click="deleteCargo(cargo.id)">
                                <i class="fas fa-trash red"></i>
                            </a>
                        </td>
                      </tr>
                    </tbody></table>
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
        <h5 class="modal-title" v-show="!editmode" id="addNewLabel"><b>NUEVO CARGO</b></h5>
        <h5 class="modal-title" v-show="editmode" id="addNewLabel"><b>ACTUALIZAR CARGO</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <form @submit.prevent="editmode  ? updateCargo() : createCargo()" >
      <div class="modal-body">
          <div class="form-group row">
              <label for="nombre" class="col-sm-4 col-form-label">Nombre:</label>
              <div class="col-sm-8">
                <input v-model="form.nombre" type="text" name="nombre" placeholder="Nombre del cargo" class="form-control" :class="{ 'is-invalid': form.errors.has('nombre') }">
                <has-error :form="form" field="nombre"></has-error>
              </div>
          </div>
          <div class="form-group row">
              <label for="fecha" class="col-sm-4 col-form-label">Fecha:</label>
              <div class="col-sm-8">
                  <input v-model="form.fecha" type="date" name="fecha" placeholder="Fecha del cargo" class="form-control" :class="{ 'is-invalid': form.errors.has('fecha') }">
                  <has-error :form="form" field="fecha"></has-error>
              </div>
          </div>
      </div>
      <div class="modal-footer text-center cyane">
        <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
        <button v-show="editmode" type="submit" class="btn btn-info">ACTUALIZAR</button>
        <button v-show="!editmode" type="submit" class="btn btn-success">CREAR CARGO</button>
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
                cargos : {},
                form: new Form({
                    id:'',
                    nombre: '',
                    fecha: ''
                })
            }
        },
        methods:{
            updateCargo(id){
                this.$Progress.start();
                this.form.put('api/cargo/'+this.form.id)
                .then(() => {
                    $('#addNew').modal('hide');
                    swal.fire(
                        'Excelente!',
                        'Se actualizó la información del cargo.',
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
            editModal(cargo){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(cargo);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteCargo(id){
                swal.fire({
                      title: '¿Estás seguro?',
                      text: "¡No podrás revertir esto!",
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: '¡Sí, bórralo!'
                    }).then((result) =>
                    {
                        if(result.value)
                        {
                            this.form.delete('api/cargo/'+id).then(()=>{
                                swal.fire(
                                    'Excelente!',
                                    'El cargo fue eliminado.',
                                    'success'
                                )
                                Fire.$emit('AfterCreate');
                            }).catch(()=>{
                                swal.fire(
                                    'Failed!',
                                    'Revise algo salio mal.',
                                    'warning'
                                )
                            })
                        }

                    })

            },
            loadCargos(){
                if(this.$gate.isAdminOrAuthor()){
                  axios.get("api/cargo").then(({data}) => (this.cargos = data));
                }

            },
            createCargo(){
                this.$Progress.start();
                this.form.post('api/cargo')
                .then((result)=>{
                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide')
                    //toast.fire({
                    swal.fire({
                      type:  'success',
                      title: `<b>${result.data.message}</b>`,
                      text: "Fue creado correctamente!",
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
            this.loadCargos();
            Fire.$on('AfterCreate',() => {
                this.loadCargos();
            });

        }
    }
</script>
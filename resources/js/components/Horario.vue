<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
              <div class="col-md-12 mx-auto">
                <div class="card border-0 bg-light mb-3 shadow-sm">
                  <div class="card-header cyane">
                    <h3 class="card-title"><b>HORARIO</b></h3>

                    <div class="card-tools">
                      <button class="btn btn-outline-primary" @click="newModal"><i class="fas fa-user-plus"></i> Nuevo horario</button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-0">
                    <table class="table table-striped">
                      <tbody>
                      <tr>
                        <th class="text-center">Nro.</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Ingreso</th>
                        <th class="text-center">Salida</th>
                        <th class="text-center">Ingreso</th>
                        <th class="text-center">Salida</th>
                        <th class="text-center">Acciones</th>
                      </tr>
                      <tr v-for="(horario, key, index)  in horarios.data" :key="horario.id" >
                        <td v-text="horario.id"></td>
                        <td v-text="horario.nombre"></td>
                        <td class="text-center"> {{ horario.fecha | myDate }}</td>
                        <td v-text="horario.ingresom"></td>
                        <td v-text="horario.salidam"></td>
                        <td v-text="horario.ingresot"></td>
                        <td v-text="horario.salidat"></td>
                        <td class="text-center">
                            <a href="#" @click="editModal(horario)">
                                <i class="fas fa-edit blue"></i>
                            </a> /
                            <a href="#" @click="deleteHorario(horario.id)">
                                <i class="fas fa-trash red"></i>
                            </a>  /
                            <a href="#" @click="activarHorario(horario.id)" v-if="!horario.estado">
                                <i class="fas fa-check yellow"></i>
                            </a>
                            <span v-else>
                              <b class="green"> ACTIVO </b>
                            </span>
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
        <h5 class="modal-title" v-show="!editmode" id="addNewLabel"><b>NUEVO HORARIO</b></h5>
        <h5 class="modal-title" v-show="editmode" id="addNewLabel"><b>ACTUALIZAR HORARIO</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <form @submit.prevent="editmode  ? updateHorario() : createHorario()" >
      <div class="modal-body">
          <div class="form-group row">
              <label for="nombre" class="col-sm-5 col-form-label">Nombre:</label>
              <div class="col-sm-7">
                <input v-model="form.nombre" type="text" name="nombre" placeholder="Nombre del horario" class="form-control" :class="{ 'is-invalid': form.errors.has('nombre') }">
                <has-error :form="form" field="nombre"></has-error>
              </div>
          </div>
          <div class="form-group row">
              <label for="fecha" class="col-sm-5 col-form-label">Fecha:</label>
              <div class="col-sm-7">
                  <input v-model="form.fecha" type="date" name="fecha" placeholder="Fecha del horario" class="form-control" :class="{ 'is-invalid': form.errors.has('fecha') }">
                  <has-error :form="form" field="fecha"></has-error>
              </div>
          </div>
          <div class="form-group row">
              <label for="ingresom" class="col-sm-5 col-form-label">Ingreso manana:</label>
              <div class="col-sm-7">
                  <input v-model="form.ingresom" type="time" name="ingresom" placeholder="Hora de ingreso" class="form-control" :class="{ 'is-invalid': form.errors.has('ingresom') }">
                  <has-error :form="form" field="ingresom"></has-error>
              </div>
          </div>
          <div class="form-group row">
              <label for="salidam" class="col-sm-5 col-form-label">Salida manana:</label>
              <div class="col-sm-7">
                  <input v-model="form.salidam" type="time" name="salidam" placeholder="Hora de ingreso" class="form-control" :class="{ 'is-invalid': form.errors.has('salidam') }">
                  <has-error :form="form" field="salidam"></has-error>
              </div>
          </div>
          <div class="form-group row">
              <label for="ingresot" class="col-sm-5 col-form-label">Ingreso tarde:</label>
              <div class="col-sm-7">
                  <input v-model="form.ingresot" type="time" name="ingresot" placeholder="Hora de ingreso" class="form-control" :class="{ 'is-invalid': form.errors.has('ingresot') }">
                  <has-error :form="form" field="ingresot"></has-error>
              </div>
          </div>
          <div class="form-group row">
              <label for="salidat" class="col-sm-5 col-form-label">Salida tarde:</label>
              <div class="col-sm-7">
                  <input v-model="form.salidat" type="time" name="salidat" placeholder="Hora de ingreso" class="form-control" :class="{ 'is-invalid': form.errors.has('salidat') }">
                  <has-error :form="form" field="salidat"></has-error>
              </div>
          </div>
      </div>
      <div class="modal-footer text-center cyane">
        <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
        <button v-show="editmode" type="submit" class="btn btn-info">ACTUALIZAR</button>
        <button v-show="!editmode" type="submit" class="btn btn-success">CREAR HORARIO</button>
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
                horarios : {},
                form: new Form({
                    id:'',
                    nombre: '',
                    fecha: '',
                    ingresom: '',
                    salidam: '',
                    ingresot: '',
                    salidat: '',
                })
            }
        },
        methods:{
            updateHorario(id){
                this.$Progress.start();
                this.form.put('api/horarios/'+this.form.id)
                .then(() => {
                    $('#addNew').modal('hide');
                    swal.fire(
                        'Excelente!',
                        'Se actualizó el horario.',
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
            deleteHorario(id){
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
                            this.form.delete('api/horarios/'+id).then(()=>{
                                swal.fire(
                                    'Excelente!',
                                    'El horario fue eliminado.',
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
            activarHorario(id){
                swal.fire({
                      title: '¿Deseas activar este horario?',
                      text: "¿Estás seguro?",
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: '¡Sí, activar!'
                    }).then((result) =>
                    {
                        if(result.value)
                        {
                            this.form.get('api/horarios/'+id).then(()=>{
                                swal.fire(
                                    'Excelente!',
                                    'El horario fue activado.',
                                    'success'
                                )
                                Fire.$emit('AfterCreate');
                            }).catch(()=>{
                                swal.fire(
                                    'Ooops... Algo salio mal!',
                                    'Vuelva a intertarlo.',
                                    'warning'
                                )
                            })
                        }

                    })

            },
            loadHorario(){
                if(this.$gate.isAdminOrAuthor()){
                  axios.get("api/horarios").then(({data}) => (this.horarios = data));
                }

            },
            createHorario(){
                this.$Progress.start();
                this.form.post('api/horarios')
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
            this.loadHorario();
            Fire.$on('AfterCreate',() => {
                this.loadHorario();
            });

        }
    }
</script>
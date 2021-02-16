<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
              <div class="col-md-6 mx-auto">
				<div class="form-row">
				    <div class="form-group col-md-7">
						<v-select :reduce="name => name.id" label="name" :options="users.data" placeholder="Selecciona al personal" v-model="user" ></v-select>
				    </div>
				    <div class="form-group col-md-5">
					    <button type="submit" class="btn cyane mb-2" v-on:click="reportUser(user)">Enviar</button>
				    </div>
				  </div>
              </div>
              <div class="col-md-12 mx-auto">
                <div class="card border-0 bg-light mb-3 shadow-sm">
                  <div class="card-header cyane">
                    <h3 class="card-title"><b>REGISTROS DE UN USUARIO</b></h3>
                    <div class="card-tools">

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
                        <th class="text-center">Horario</th>
                        <th class="text-center">Ingreso</th>
                        <th class="text-center">Salida</th>
                        <th class="text-center">Atraso</th>
                        <th class="text-center">Ingreso</th>
                        <th class="text-center">Salida</th>
                        <th class="text-center">Atraso</th>
                      </tr>
                      <tr v-for="(registro,  index)  in registros.data" :key="registro.id" >
                        <td >{{ index+1 }}</td>
                        <td v-text="registro.user.name"></td>
                        <td v-text="registro.fecha"></td>
                        <td> {{ registro.horario.nombre }}</td>
                        <td v-text="registro.llegadam"></td>
                        <td v-text="registro.retirom"></td>
                        <td v-text="registro.atraso1" class="cyane"></td>
                        <td v-text="registro.llegadat"></td>
                        <td v-text="registro.retirot"></td>
                        <td v-text="registro.atraso2" class="cyane"></td>
                      </tr>
                    </tbody></table>
                  </div>
                  <!-- /.card-body -->

                </div>
                <!-- /.card -->
              </div>
            </div>
        </div>


    <div v-if="!$gate.isAdminOrAuthor()">
        <not-found></not-found>
    </div>
     </div>
</template>

<script>
    export default {
        data() {
            return {
            	user:'',
                users : {},
                registros : {},
            }
        },
        methods:{
        	reportUser: function (id) {
			    this.$Progress.start();
                axios.get('api/registro/'+id)
                .then(({data}) => {
                	swal.fire(
                        'Excelente!',
                        'Mostrando registros del personal.',
                        'success'
                    )
                	this.registros = data;
                    this.$Progress.finish();
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
            loadRegistros(){
                if(this.$gate.isAdminOrAuthor()){
                  axios.get("api/user").then(({data}) => (this.users = data));
                }

            }
        },
        created() {
            this.loadRegistros();
            Fire.$on('AfterCreate',() => {
                this.loadRegistros();
            });

        }
    }
</script>
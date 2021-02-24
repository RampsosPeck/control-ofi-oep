<template>
    <div class="container">
        <div class="justify-content-center">
            <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
              <div class="col-md-12 col-lg-10 mx-auto">
                <div class="card border-0 bg-light mb-3 shadow-sm">
                  <div class="card-header cyane">
                    <h3 class="card-title"><b>REGISTROS GENERALES</b></h3>
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
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer cyane">
                        <pagination :data="registros" @pagination-change-page="getResults"></pagination>
                  </div>
                </div>
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
                registros : {},
            }
        },
        methods:{
            getResults(page = 1) {
              axios.get('api/user?page=' + page)
                .then(response => {
                  this.registros = response.data;
                });
            },
            loadRegistros(){
                if(this.$gate.isAdminOrAuthor()){
                  axios.get("api/registro").then(({data}) => (this.registros = data));
                }

            }
        },
        created() {
            Fire.$on('searching',()=> {
              let query = this.$parent.search;
              axios.get('api/findUser?q=' + query)
              .then((data)=>{
                  this.registros = data.data
              })
              .catch(()=>{

              })
            })
            this.loadRegistros();
            Fire.$on('AfterCreate',() => {
                this.loadRegistros();
            });

        }
    }
</script>
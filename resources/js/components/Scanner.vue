<template>
  <div>
    <div class="row justify-content-center mt-5">
      <div class="col-md-4">
          <div class="card text-center"  style='width:18rem;'>
             <qrcode-stream @decode="onDecode" @init="onInit" />
            <div class="card-body">
              <h5 class="card-title">QR SCANNER</h5>
              <p class="card-text">
               {{ mensaje }}
              </p>
            </div>
            <div class="card-body">
              <a href="#" class="card-link">Mas detalles </a>
            </div>
          </div>
      </div>
      <div class="col-md-8">
        <div class="table-responsive">
           <table class="table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">Nro.</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Cargo</th>
                  <th scope="col">Hora</th>
                  <th scope="col">Atraso</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) of muestra">
                  <td scope="row">{{ index+1 }}</td>
                  <td>{{ item.nombre }}</td>
                  <td>{{ item.cargo }}</td>
                  <td>{{ item.hora }}</td>
                  <td>{{ item.atraso }}</td>
                </tr>
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                muestra : [],
                mensaje: 'Escanee el QR',

                name:'',
                cargo:'',

                existe: false,
                momento: '',
                fechahoy:'',
                personal:{},
                form: new Form({
                    userid: '',
                    userci: '',
                    fecha: '',
                    hora: '',
                    atraso:''
                })
            }
        },
        methods:{
            onDecode (decodedString) {
            //console.log(decodedString)
                // Validaciones para ver si existe o no el QR
                this.personal.map((data)=>{
                    if(data.cedula == parseInt(decodedString))
                    {
                      this.existe = true;

                      let hoy = new Date();
                      this.momento = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();

                      this.$Progress.start();
                      /*aqui validar si tiene o no tiene atraso*/
                      this.form.userid = data.id;
                      this.form.userci = data.cedula;
                      this.form.fecha = this.fechahoy;
                      this.form.hora = this.momento;
                      this.form.atraso = 0;

                      this.form.name = data.name;
                      this.form.cargo = data.cargo;
                    }
                })
                // Si existe entonces
                if(this.existe)
                {
                    this.form.post('api/registro')
                    .then((result)=>{
                        swal.fire({
                          type:  'success',
                          //title: `<b>${result.data.message}</b>`,
                          title: 'Hola '+this.form.name,
                          text: "Bienvenido al TED - Potosí!",
                          showConfirmButton: false,
                          timer: 2500
                        });

                        this.muestra.push({
                            nombre: this.form.name,
                            cargo: this.form.cargo,
                            hora: this.momento,
                            atraso: '00:00:00'
                        });
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        swal.fire({
                          type:  'error',
                          title: 'Oops!!! '+this.form.name+' algo salió mal!',
                          text: "Vuelve a intentarlo por favor.",
                          showConfirmButton: false,
                          timer: 2500
                        });
                        this.$Progress.fail();
                    });
                    this.existe = false;
                }else{
                    swal.fire({
                      type:  'error',
                      title: 'Código QR invalido!',
                      text: "Lo estamos grabando",
                      showConfirmButton: false,
                      timer: 2500
                    });
                }
            },

            loadPersonal(){
                if(this.$gate.isAdminOrAuthor()){
                  axios.get("api/user").then(({data}) => (this.personal = data.data));
                }

            },
            async onInit (promise) {
              try {
                await promise
              } catch (error) {
                if (error.name === 'NotAllowedError') {
                  this.error = "ERROR: debe otorgar permiso de acceso a la cámara"
                } else if (error.name === 'NotFoundError') {
                  this.error = "ERROR: no hay cámara en este dispositivo"
                } else if (error.name === 'NotSupportedError') {
                  this.error = "ERROR: se requiere contexto seguro (HTTPS, localhost)"
                } else if (error.name === 'NotReadableError') {
                  this.error = "ERROR: ¿la cámara ya está en uso?"
                } else if (error.name === 'OverconstrainedError') {
                  this.error = "ERROR: las cámaras instaladas no son adecuadase"
                } else if (error.name === 'StreamApiNotSupportedError') {
                  this.error = "ERROR: Stream API no es compatible con este navegador"
                }
              }
            }
        },
        created() {
            let hoy = new Date();
            this.momento = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();

            this.fechahoy = hoy.getDate() + '-' + (hoy.getMonth()+1) + '-' + hoy.getFullYear();

            this.loadPersonal();
            Fire.$on('AfterCreate',() => {
                this.loadPersonal();
            });

        }
    }
</script>
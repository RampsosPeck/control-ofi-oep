<template>
  <div>
    <div class="row justify-content-center mt-5">
      <div class="col-md-4">
          <div class="card text-center"  style='width:18rem;'>
            <div class="card-header cyane">
                <h5><b>REGISTRO DE ASISTENCIA EN HORARIO VARIADO</b></h5>
            </div>
            <div class="card-body m-0 p-1 sidebar-dark-primary">
                <qrcode-stream @decode="onDecode" @init="onInit" />
            </div>
            <div class="card-footer cyane">
                <p class="card-text" :class="this.classsms" style="border-radius:5em/5em;">
                    <b>{{ mensaje }}</b>
                </p>
            </div>
            <div class="text-center sidebar-dark-primary text-warning">
              <p class="mb-0"><b>Mañana: </b></p>
                Entrada < <b>10:00</b> > Salida
              <p  class="mb-0"><b>Tarde: </b></p>
                Entrada < <b>16:00</b> > Salida
            </div>
          </div>
      </div>
      <div class="col-md-8">
        <div class="table-responsive">
            <table class="table">
              <thead class="sidebar-dark-primary text-white">
                <tr>
                  <th scope="col" class="text-center">Estado</th>
                  <th scope="col" class="text-center">Nombre</th>
                  <th scope="col" class="text-center">Cargo</th>
                  <th scope="col" class="text-center">Hora</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) of muestra">
                  <td scope="row" class="text-center"><i class="fa fa-check-square fa-2x bg-success" aria-hidden="true"></i></td>
                  <td>{{ item.nombre }}</td>
                  <td class="text-center">{{ item.cargo.nombre }}</td>
                  <td class="text-center">{{ item.hora }}</td>
                </tr>
              </tbody>
            </table>
        </div><br /><br />
        <div class="row sidebar-dark-primary">
          <div class="hero" style="height:50vh;">
              <h1> <small>CONTROL DE ASISTENCIA</small>
                 <img src="img/qrscan5.png" width="150" class="my-4" style="margin-top: 0.2rem !important;">
                 </h1>
              <div class="row">
                  <small class="footerleft">Created By  &copy;  Ing. Jorge Peralta </small>
              </div>
              <div class="neon-light" style="width: 5px; height: 250px;">
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
    export default {
        data() {
            return {
                muestra : [],
                mensaje: 'Escanea el código QR de tu intransferible!',
                classsms: 'sidebar-dark-primary text-white',

                name:'', //nombre del que esta marcando
                cargo:'', //cargo del que esta marcando

                existe: false, //si existe o no el usuario en la BD
                momento: '', //la hora al instante
                fechahoy:'', //saca la fecha de hoy
                personal:{}, //personal que va a marcar el qr
                horario:'', //con que horario va a sacar su atraso
                form: new Form({
                    userid: '', //usuario id
                    userci: '', //cedula del usuario
                    fecha: '', //fecha en que esta marcando
                    hora: '', //hora en que esta marcando
                    marca:'', //Mmaniana, mediodia, tarde, noche
                    horarioid:'' //que horario estamos usando (horario continuo)
                })
            }
        },
        methods:{
            onDecode (decodedString) {
            //console.log(decodedString)
                var audio = new Audio('/sound/success.mp3');
                var audioerror = new Audio('/sound/errors.mp3');
                this.personal.map((data)=>{
                    if(data.cedula == parseInt(decodedString))
                    {
                        this.muestra = [];
                        this.existe = true;
                        this.form.userid = data.id;
                        this.form.userci = data.cedula;
                        this.name = data.name;
                        this.cargo = data.cargo;
                    }
                });

                this.$Progress.start();
                if(this.existe)
                {
                    let hoy = new Date();
                    this.momento = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();

                    if ( moment(this.momento,'HH:mm:ss') <= moment('10:00:00','HH:mm:ss')) {
                        this.form.marca = 'maniana';
                    } else if ( moment(this.momento,'HH:mm:ss') <= moment('13:30:00','HH:mm:ss') ) {
                        this.form.marca = 'mediodia';
                    } else {
                        if (moment(this.momento,'HH:mm:ss') <= moment('16:00:00','HH:mm:ss'))
                        {
                            this.form.marca = 'tarde';
                        }else{
                            this.form.marca = 'noche';
                        }
                    }

                    this.form.fecha = this.fechahoy;
                    this.form.hora = this.momento;
                    this.form.horarioid = this.horario.id;
                    this.form.post('api/regicontinuo')
                    .then((result)=>{
                        this.muestra.push({
                            nombre: this.name,
                            cargo: this.cargo,
                            hora: this.momento
                        });
                        audio.play();
                        swal.fire({
                          type:  'success',
                          title: 'Hola '+ this.name +' bienvenido al TED - Potosí!',
                          text: `${result.data.message}`,
                          showConfirmButton: false,
                          timer: 3000
                        });
                        this.mensaje = result.data.message;
                        this.classsms = 'bg-success';
                        setTimeout(() => {
                            this.mensaje = 'Escanea el código QR de tu intransferible!';
                            this.classsms = 'sidebar-dark-primary text-white';
                        }, 5000);
                        this.existe = false;
                        this.$Progress.finish();
                    })
                    .catch(error => {
                        audioerror.play();
                        swal.fire({
                          type:  'error',
                          title: `${error.response.data.message}`,
                          text: 'Oops!!! volviste a marcar',
                          showConfirmButton: false,
                          timer: 3000
                        });
                        this.mensaje = error.response.data.message;
                        this.classsms = 'bg-danger';
                        setTimeout(() => {
                            this.mensaje = 'Escanea el código QR de tu intransferible!';
                            this.classsms = 'sidebar-dark-primary text-white';
                        }, 5000);
                        this.existe = false;
                        this.$Progress.fail();
                    });

                }else{
                    this.mensaje = 'Oops!!! Código QR invalido!';
                    this.classsms = 'bg-danger';
                    setTimeout(() => {
                        this.mensaje = 'Escanea el código QR de tu intransferible!';
                        this.classsms = 'sidebar-dark-primary text-white';
                    }, 5000);
                    audioerror.play();
                    this.$Progress.fail();
                }
                this.$Progress.finish();
            },

            loadPersonal(){
                //if(this.$gate.isUser()){
                  axios.get("api/user").then(({data}) => (this.personal = data.data));
                  axios.get("api/horactiva").then(({data}) => (this.horario = data.data));
                //}
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























<template>
  <div>
    <div class="row justify-content-center mt-5">
      <div class="col-md-4">
          <div class="card text-center"  style='width:18rem;'>
            <div class="card-header cyane">
                <h5><b>REGISTRO DE ASISTENCIA</b></h5>
            </div>
            <div class="card-body m-0 p-1 sidebar-dark-primary">
                <qrcode-stream @decode="onDecode" @init="onInit" />
            </div>
            <div class="card-footer cyane">
                <p class="card-text" :class="this.classsms" style="border-radius:5em/5em;">
                    <b>{{ mensaje }}</b>
                </p>
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
                  <th scope="col" class="text-center">Atraso</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) of muestra">
                  <td scope="row" class="text-center"><i class="fa fa-check-square fa-2x bg-success" aria-hidden="true"></i></td>
                  <td>{{ item.nombre }}</td>
                  <td class="text-center">{{ item.cargo.nombre }}</td>
                  <td class="text-center">{{ item.hora }}</td>
                  <td class="text-center">{{ item.atraso }}</td>
                </tr>
              </tbody>
            </table>
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
                retraso:'', //que tanto es su retraso
                form: new Form({
                    userid: '', //usuario id
                    userci: '', //cedula del usuario
                    fecha: '', //fecha en que esta marcando
                    hora: '', //hora en que esta marcando
                    atraso:'', //el atraso para que vaya a sumar
                    marca:'', //Mmaniana, mediodia, tarde, noche
                    horarioid:'' //que horario estamos usando (horario continuo)
                })
            }
        },
        methods:{
            onAtraso: function(momento1, ingreso2) {
                var hora1 = (momento1).split(":"),
                hora2 = (ingreso2).split(":"),
                    t1 = new Date(),
                    t2 = new Date();

                t1.setHours(hora1[0], hora1[1], hora1[2]);
                t2.setHours(hora2[0], hora2[1], hora2[2]);

                //Aquí hago la resta
                t1.setHours(t1.getHours() - t2.getHours(), t1.getMinutes() - t2.getMinutes(), t1.getSeconds() - t2.getSeconds());

                //Imprimo el resultado
                return (t1.getHours() ? t1.getHours() + (t1.getHours() > 1 ? ":" : ":") : "") + (t1.getMinutes() ? "" + t1.getMinutes() + (t1.getMinutes() > 1 ? ":" : ":") : "") + (t1.getSeconds() ? (t1.getHours() || t1.getMinutes() ? "" : "") + t1.getSeconds() + (t1.getSeconds() > 1 ? "" : "") : "");
            },
            onDecode (decodedString) {
            //console.log(decodedString)
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

                    if (this.momento <= '10:00:00')
                    {
                        if(this.momento > this.horario.ingresom){
                            this.form.atraso = this.onAtraso(this.momento, this.horario.ingresom);
                        }else{
                            this.form.atraso = '00:00:00';
                        }
                        this.form.marca = 'maniana';
                    }else{
                        if (this.momento <= this.horario.salidam) {
                            return swal.fire({
                              type:  'error',
                              title: 'Oops!!! '+this.name+' aún no es hora!',
                              text: "Se le tomará como abandono.",
                              showConfirmButton: false,
                              timer: 3000
                            });
                            this.existe = false;
                        }else{
                            this.form.marca = 'mediodia';
                            this.form.atraso = '00:00:00';
                        }
                    }

                    if (this.momento <= '16:00:00')
                    {
                        if(this.momento > this.horario.ingresot){
                            this.form.atraso = this.onAtraso(this.momento, this.horario.ingresot);
                        }else{
                            this.form.atraso = '00:00:00';
                        }
                        this.form.marca = 'tarde';
                    }else{
                        if (this.momento <= this.horario.salidat) {
                            return swal.fire({
                              type:  'error',
                              title: 'Oops!!! '+this.name+' aún no es hora!',
                              text: "Se le tomará como abandono.",
                              showConfirmButton: false,
                              timer: 3000
                            });
                            this.existe = false;
                        }else{
                            this.form.marca = 'noche';
                            this.form.atraso = '00:00:00';
                        }
                    }

                    this.form.fecha = this.fechahoy;
                    this.form.hora = this.momento;
                    this.form.horarioid = this.horario.id;
                    this.form.post('api/registro')
                    .then((result)=>{
                        this.muestra.push({
                            nombre: this.name,
                            cargo: this.cargo,
                            hora: this.momento,
                            atraso: this.form.atraso
                        });
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
                    /*return swal.fire({
                      type:  'error',
                      title: 'Oops!!! Código QR invalido!',
                      text:  "Lo estamos grabando.",
                      showConfirmButton: false,
                      timer: 3000
                    });*/
                    this.$Progress.fail();
                }
                this.$Progress.finish();
            },

            loadPersonal(){
                if(this.$gate.isAdminOrAuthor()){
                  axios.get("api/user").then(({data}) => (this.personal = data.data));
                  axios.get("api/horactiva").then(({data}) => (this.horario = data.data));
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
























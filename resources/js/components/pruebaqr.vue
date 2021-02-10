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
import moment from 'moment';
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
                horario:'',
                retraso:'',
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
        /*computed:{
            calcularAtraso: function(momento1, ingreso2) {
                //console.log(this.momento);
                //console.log(this.horario.ingresom);
                var hora1 = (momento1).split(":"),
                hora2 = (ingreso2).split(":"),
                    t1 = new Date(),
                    t2 = new Date();

                t1.setHours(hora1[0], hora1[1], hora1[2]);
                t2.setHours(hora2[0], hora2[1], hora2[2]);

                //Aquí hago la resta
                t1.setHours(t1.getHours() - t2.getHours(), t1.getMinutes() - t2.getMinutes(), t1.getSeconds() - t2.getSeconds());

                //Imprimo el resultado
                return  (t1.getHours() ? t1.getHours() + (t1.getHours() > 1 ? ":" : " hora") : "") + (t1.getMinutes() ? "" + t1.getMinutes() + (t1.getMinutes() > 1 ? ":" : " minuto") : "") + (t1.getSeconds() ? (t1.getHours() || t1.getMinutes() ? "" : "") + t1.getSeconds() + (t1.getSeconds() > 1 ? "" : " segundo") : "");
                    /*document.body.innerHTML = "La diferencia es de: " + (t1.getHours() ? t1.getHours() + (t1.getHours() > 1 ? " horas" : " hora") : "") + (t1.getMinutes() ? ", " + t1.getMinutes() + (t1.getMinutes() > 1 ? " minutos" : " minuto") : "") + (t1.getSeconds() ? (t1.getHours() || t1.getMinutes() ? " y " : "") + t1.getSeconds() + (t1.getSeconds() > 1 ? " segundos" : " segundo") : "");*/
            /*}
        },*/

        methods:{
            onAtraso(momento1, ingreso2) {
                var hora1 = (momento1).split(":"),
                hora2 = (ingreso2).split(":"),
                    t1 = new Date(),
                    t2 = new Date();

                t1.setHours(hora1[0], hora1[1], hora1[2]);
                t2.setHours(hora2[0], hora2[1], hora2[2]);

                //Aquí hago la resta
                t1.setHours(t1.getHours() - t2.getHours(), t1.getMinutes() - t2.getMinutes(), t1.getSeconds() - t2.getSeconds());

                //Imprimo el resultado
                this.form.atraso =  (t1.getHours() ? t1.getHours() + (t1.getHours() > 1 ? ":" : " hora") : "") + (t1.getMinutes() ? "" + t1.getMinutes() + (t1.getMinutes() > 1 ? ":" : " minuto") : "") + (t1.getSeconds() ? (t1.getHours() || t1.getMinutes() ? "" : "") + t1.getSeconds() + (t1.getSeconds() > 1 ? "" : " segundo") : "");
            },
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
                      if (this.momento <= '10:00:00') {
                          if(this.momento > this.horario.ingresom){
                            this.form.atraso = onAtraso(this.momento, this.horario.ingresom);
                            console.log(this.form.atraso);
                          }else{
                            this.form.atraso = '00:00:00';
                          }
                          this.marca = 'maniana';
                      } else if (this.momento  <= '13:00:00') {
                          if(this.momento < this.horario.salidam){
                              this.existe = false;
                              swal.fire({
                                type:  'error',
                                title: 'Aún falta para la hora de salida!',
                                text: "Lo estamos grabando",
                                showConfirmButton: false,
                                timer: 2500
                              });
                          }
                          this.marca = 'mediodia';
                      } else {
                          if(this.momento  <= '16:00:00'){
                              this.marca = 'tarde';
                              if(this.momento > this.horario.ingresot){
                                  this.form.atraso = onAtraso(this.momento, this.horario.ingresom);
                                  console.log(this.form.atraso);
                              }else{
                                  this.form.atraso = '00:00:00';
                              }
                          }else{
                              if(this.momento < this.horario.salidat){
                                  this.existe = false;
                                  swal.fire({
                                    type:  'error',
                                    title: 'Aún falta para la hora de salida!',
                                    text: "Lo estamos grabando",
                                    showConfirmButton: false,
                                    timer: 2500
                                  });
                              }else{
                                  this.marca = 'noche';
                                  this.existe = true;
                              }

                          }
                      }
                      /*Hasta aqui para sacar retraso*/

                      this.form.userid = data.id;
                      this.form.userci = data.cedula;
                      this.form.fecha = this.fechahoy;
                      this.form.hora = this.momento;
                      //this.form.atraso = 0;

                      this.name = data.name;
                      this.cargo = data.cargo;
                    }
                })
                // Si existe entonces
                if(this.existe)
                {
                    this.form.horarioid = this.horario.id;
                    this.form.post('api/registro')
                    .then((result)=>{
                        swal.fire({
                          type:  'success',
                          //title: `<b>${result.data.message}</b>`,
                          title: 'Hola '+this.name,
                          text: "Bienvenido al TED - Potosí!",
                          showConfirmButton: false,
                          timer: 2500
                        });

                        this.muestra.push({
                            nombre: this.name,
                            cargo: this.cargo,
                            hora: this.momento,
                            atraso: this.form.atraso
                        });
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        swal.fire({
                          type:  'error',
                          title: 'Oops!!! '+this.name+' algo salió mal!',
                          text: "Vuelve a intentarlo por favor.",
                          showConfirmButton: false,
                          timer: 2500
                        });
                        this.$Progress.fail();
                    });
                    this.existe = false;
                }else{
                  //console.log(this.horario.ingresom);
                    /*if (this.momento >= this.horario.ingresom) {
                      console.log('si')
                    }else{ console.log('no')}*/

                    /*if (this.momento >= '10:00:00') {
                          console.log('si es menor a 13:00')
                      }else{
                        console.log('no es menor')
                      }*/

                      /*if (this.momento >= this.horario.ingresom) {
                      console.log('si')
                    }else{ console.log('no')}*/

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
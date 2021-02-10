<template>
  <div>
    <div class="row justify-content-center mt-5">
      <div class="col-md-4">
          <div class="card text-center"  style='width:18rem;'>
             <qrcode-stream @decode="onDecode"></qrcode-stream>
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
                  <th scope="col">Codigo</th>
                  <th scope="col">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) of registros">
                  <td scope="row">{{ index+1 }}</td>
                  <td>{{ item.mensaje }}</td>
                  <td>@mdo</td>
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
                registros : [
                      { mensaje: 'Foo' },
                      { mensaje: 'Bar' }
                ],
                mensaje: 'Escanee el QR',
                form: new Form({
                    id:'',
                    nombre: '',
                    fecha: ''
                })
            }
        },
        methods:{
          onDecode (decodedString) {
              console.log(decodedString)

                  this.registros.push({
                      mensaje: decodedString
                    });
                  this.mensaje = 'Excelente correcta';

                setTimeout(function (){
                  this.mensaje = 'Escanee el QR';
                }, 1500);
            }
        }
    }
</script>
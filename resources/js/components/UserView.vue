<style>
.widget-user-header{
    background-position: center center;
    background-size: cover;
    height: 300px;

}
</style>
 <template>
	<div class="row justify-content-center">
        <div class="col-md-8 mt-5">
            <div class="card card-widget widget-user">
                  <div class="widget-user-header text-white" style="background: url('/img/qrwebuser.jpg') center center;">
                    <h3 class="widget-user-username">REGISTRO DE ASISTENCIA</h3>
                    <h5 class="widget-user-desc">USUARIO SECURITY</h5>
                  </div>
                  <div class="widget-user-image">

                  </div>
                  <div class="card-footer sidebar-dark-primary text-white">
                    <div class="row">
                      <div class="col-sm-4 border-right">
                        <div class="description-block">
                        	<b>ASISTENCIA</b>
                        </div>
                      </div>
                      <div class="col-sm-4 border-right">
                        <div class="description-block">
                        	<router-link to="scaneo" >
                        	<b class="text-white">ESCANEO NORMAL</b>
                        	</router-link>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="description-block">
                        	<router-link to="scaneo" >
                        	<b class="text-white">ESCANEO DINÁMICO</b>
                        	</router-link>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
	</div>
</template>
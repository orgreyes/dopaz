<div class="row justify-content-center">
  <div class="col-12 col-md-8 col-lg-3">
    <div class="card bg-light">
      <div class="row justify-content-center">
        <div class="col-7 col-sm-6 col-lg-8">
          <img src="<?= asset('./images/A1.png') ?>" class="w-100" alt="logo">

        </div>
      </div>
      <div class="card-header bg-transparent text-center">
        <small class="text-muted">Versión 3.0</small>
      </div>
      <div class="card-body">
        <form id="formLogin">
          <div class="row mb-3">
            <div class="col">
              <div class="form-floating mb-3">
                <input type="text" class="form-control form-control-sm" name="user" id="user" placeholder="Usuario">
                <label for="floatingInput" class="text-secondary">Usuario</label>
              </div>
            </div>
  
          </div>
          <div class="row mb-3">
            <div class="col">
              <div class="form-floating mb-3">
                <input type="password" class="form-control form-control-sm" name="password" id="password" placeholder="password">
                <label for="floatingInput" class="text-secondary" >Contraseña</label>
              </div>
            </div>
          </div>
          <div class="row justify-content-center text-center mb-3 p-auto">
            <div id="captcha" class="col">
              <div class="g-recaptcha w-100" data-sitekey="6Lc8UrYkAAAAAM6qyRHb6WLTHH2Q7-TkLlrhFoBP" data-size="normal" data-callback="verificar" data-expired-callback="expirado" data-error-callback="error"></div>

            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
              <button type="submit" class="btn btn-lg w-100 btn-primary" id="loginButton"><span class="spinner-border spinner-border-sm me-2 d-none" id="spinner"></span>Iniciar Sesión</button>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button type="button" class="btn w-100 btn-secondary">Cibercuestionario</button>
            </div>
          </div>
        </form>
      </div>
      <div class="card-footer text-center">
        <p style="font-size:xx-small; font-weight: bold;">
          Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
        </p>
      </div>
    </div>
  </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="modal fade" id="modalAceptar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAceptarLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-image-modal">
      <div class="modal-header d-flex justify-content-center">
        <h1 class="modal-title fs-3 text-danger" id="modalAceptarLabel">CONFIDENCIAL</h1>
        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
      </div>
      <div class="modal-body ">
        <div class="row">
          <div class="col">
            <p class="fs-5" style="text-align: justify;">La utilización de esta aplicación se considera de carácter CONFIDENCIAL en base a lo estipulado en la Constitución Política de la República de Guatemala (artículo 30 y 249), Ley Constitutiva del Ejército de Guatemala (Decreto No. 72-90), Ley de libre acceso a la información pública (decreto No. 57-2008), Reglamento de publicaciones militares (Acuerdo No. MDN-31-70), Reglamento para el Servicio del Ejército en tiempos de paz, Reglamento de Sanciones Disciplinarias (Acuerdo Gubernativo No.2-2008), Directiva de Clasificación de la Información Militar (No. MDN-008-SAGE-2009) y Directiva de Transmisión de Información Militar (No. MDN-009-SAGE-2009). Por lo que cualquier mal uso que se haga de la información que contiene, será responsabilidad del usuario que acepta y firma con su clave, ateniéndose a las sanciones que de acuerdo a la legislación antes mencionada le corresponda.</p>
            <p class="fw-bold fs-4 text-center text-danger">No comparta su usuario y contraseña, recuerde que todo lo que se haga dentro de este sistema, esta siendo monitoreado y registrado.</p>
            <p class="text-center fs-4">¿Acepta la Responsabilidad que esta Información conlleva?</p>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <a href="/login/logout" class="btn btn-danger btn-lg">Rechazar</a>
        <a href="/menu/" class="btn btn-primary btn-lg">Aceptar</a>
      </div>
    </div>
  </div>
</div>

<script src="build/js/login/index.js"></script>
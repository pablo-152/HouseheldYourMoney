<?php $this->load->view('login/header-login'); ?>
<?php $this->load->view('login/nav-login'); ?>
<div class="form-container outer fondo_login">
  <div class="form-form">
      <div class="form-form-wrap">
          <div class="form-container">
              <div class="form-content">
                  <h1 class="">¡Bienvenido!</h1>
                  <p class="">Gestione sus finanzas fácilmente con <br>Household Your Money.</p>
                  <form class="text-left">
                      <div class="form">
                          <div id="username-field" class="field-wrapper input">
                              <label for="usuario">Usuario</label>
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                              <input id="usuario" name="usuario" type="text" class="form-control" placeholder="Usuario">
                          </div>
                          <div id="password-field" class="field-wrapper input mb-2">
                              <div class="d-flex justify-content-between">
                                  <label for="clave">Contraseña</label>
                                  <a href="auth_pass_recovery_boxed.html" class="forgot-pass-link">¿Has olvidado tu contraseña?</a>
                              </div>
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                              <input id="password" name="password" type="password" class="form-control" placeholder="Contraseña">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                          </div>
                          <div class="d-sm-flex justify-content-between">
                              <div class="field-wrapper">
                                  <button type="button" class="btn btn-primary" value="" onclick="Ingresar();">Ingresar</button>
                              </div>
                          </div>
                          <div class="division">
                                <span></span>
                          </div>
                          <div class="social">
                              <!--<a href="javascript:void(0);" class="btn social-fb">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg> 
                                  <span class="brand-name">Facebook</span>
                              </a>-->
                              <a href="javascript:void(0);" class="btn social-gmail">
                                  <!--<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github">
                                  <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
                                  -->
                                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="100" height="100">
                                    <path fill="#4285F4" d="M44.5 20H24v8.5h11.9C34 33 30.5 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3 0 5.7 1.1 7.8 2.9l6.4-6.4C34.3 4 29.5 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.1-2.7-.5-4z"/>
                                    <path fill="#34A853" d="M6.3 14.6l7 5.1C15 16.5 18.3 14 24 14c3 0 5.7 1.1 7.8 2.9l6.4-6.4C34.3 4 29.5 2 24 2c-7.8 0-14.6 4.4-17.7 10.9z"/>
                                    <path fill="#FBBC05" d="M24 46c6.3 0 11.7-2.1 15.6-5.6l-7.2-5.6c-2.1 1.5-4.8 2.5-8.4 2.5-6.5 0-12-5-12-12H4v7.4C7.1 39.6 14.9 46 24 46z"/>
                                    <path fill="#EA4335" d="M44.5 20H24v8.5h11.9c-.9 3.4-3.4 5.8-6.7 7.3l7.2 5.6C41.5 37.3 46 31.7 46 24c0-1.3-.1-2.7-.5-4z"/>
                                  </svg>
                                  <span class="brand-name">Gmail</span>
                              </a>
                          </div>
                          <p class="signup-link">¿No estás registrado?<a href="auth_register_boxed.html"> Crea una cuenta </a></p>
                      </div>
                  </form>
              </div>                    
          </div>
      </div>
  </div>
</div>
<script>

</script>
<?php $this->load->view('login/validaciones-login'); ?>
<?php $this->load->view('login/footer-login'); ?>

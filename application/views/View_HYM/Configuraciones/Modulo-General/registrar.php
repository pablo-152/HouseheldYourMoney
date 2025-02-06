<div class="modal-header">
    <h5 class="modal-title">Nueva Navegación</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
</div>
<div class="modal-body">
    <div class="col-md-11 mx-auto">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone">Padre</label>
                    <select class="form-control mb-4" id="country">
                        <option>All Countries</option>
                        <option selected>United States</option>
                        <option>India</option>
                        <option>Japan</option>
                        <option>China</option>
                        <option>Brazil</option>
                        <option>Norway</option>
                        <option>Canada</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Link</label>
                    <input type="text" class="form-control mb-4" id="email" placeholder="Write your email here" value="Jimmy@gmail.com">
                </div>
            </div>                                    
            <div class="col-md-6">
                <div class="form-group">
                    <label for="website1">Titulo</label>
                    <input type="text" class="form-control mb-4" id="website1" placeholder="Write your website here">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="website1">Descripción</label>
                    <input type="text" class="form-control mb-4" id="website1" placeholder="Write your website here">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="website1">SVG</label>
                    <textarea style="max-height: 255px;" class="form-control" id="aboutBio" placeholder="Tell something interesting about yourself" rows="3">
                        I'm Creative Director and UI/UX Designer from Sydney, Australia, working in web development and print media. 
                        I enjoy turning complex problems into simple, beautiful and intuitive designs.
                    </textarea>                
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="phone">Niveles</label>
                    <select class="form-control tagging" multiple="multiple">
                        <option>orange</option>
                        <option>white</option>
                        <option>purple</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(".tagging").select2({
        tags: true
    });
</script>

<div class="modal-footer md-button">
    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancelar</button>
    <button type="button" class="btn btn-primary">Guardar</button>
</div>
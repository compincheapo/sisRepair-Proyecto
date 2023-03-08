<div id="changePasswordModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Contraseña</h5>
                <button type="button" aria-label="Close" class="close outline-none" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="alert alert-danger d-none" id=""></div>
                    <input type="hidden" name="is_active" value="1">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="user_id" id="editPasswordValidationErrorsBox">
                    {{csrf_field()}}
                <div class="row">
                    <div class="form-group col-sm-12">
                        <label>Contraseña actual:</label><span
                                class="required confirm-pwd"></span><span class="required">*</span>
                        <div class="input-group">
                            <input class="form-control input-group__addon" id="pfCurrentPassword" type="password"
                                   name="password_current" required>
                            <div class="input-group-append input-group__icon">
                                <span class="input-group-text changeType">
                                    <i class="icon-ban icons"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Nueva contraseña:</label><span
                                class="required confirm-pwd"></span><span class="required">*</span>
                        <div class="input-group">
                            <input class="form-control input-group__addon" id="pfNewPassword" type="password"
                                   name="password" required>
                            <div class="input-group-append input-group__icon">
                                <span class="input-group-text changeType">
                                    <i class="icon-ban icons"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Confirmar contraseña:</label><span
                                class="required confirm-pwd"></span><span class="required">*</span>
                        <div class="input-group">
                            <input class="form-control input-group__addon" id="pfNewConfirmPassword" type="password"
                                   name="password_confirmation" required>
                            <div class="input-group-append input-group__icon">
                                <span class="input-group-text changeType">
                                    <i class="icon-ban icons"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary" id="btnPrPasswordEditSave" data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">Guardar</button>
                    <button type="button" class="btn btn-light ml-1" data-dismiss="modal">Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('body').on('click', '#btnPrPasswordEditSave', function (){
        id ="<?php echo  \Illuminate\Support\Facades\Auth::user()->id ?>";
        
        var contraActual = $('#pfCurrentPassword').val();
        var contraNueva = $('#pfNewPassword').val();
        var contraNuevaConfirm = $('#pfNewConfirmPassword').val();
        
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });


        $.ajax({
            url: "{{route('cambiarPassword')}}",
            method: 'POST',
            data: {
            'idUser': id,
            'contraActual': contraActual,
            'contraNueva': contraNueva,
            'contraNuevaConfirm': contraNuevaConfirm         
            },
            success: function(response){
                console.log(response);

                if(response.success){
                    Swal.fire(
                    'Cambio de contraseña realizada!',
                    response.success, "success")       
                    
                    $('#changePasswordModal').modal('hide');

                    $('#pfCurrentPassword').val('');
                    $('#pfNewPassword').val('');
                    $('#pfNewConfirmPassword').val('');
                }

                if(response.error){
                    Swal.fire(
                    'Error en cambio de Contraseña!',
                    response.error, "error")    
                }

                },
            error: function(error){
                if(error){ 
                console.log(error)
                }
            }
        });
        
    });
</script>
<?php

<div class="card">
    <div class="card-header pb-1" style="min-height: unset;">
        <h4>Eliminar cuenta</h4>
    </div>

    <div class="card-body pt-1">

        <p>Una vez eliminada su cuenta, todos sus recursos y datos se borrarán permanentemente. Antes de eliminar su cuenta, descargue los datos o la información que desee conservar.</p>

        <?php if ($errors->userDeletion->has('password')) : ?>
            @foreach ($errors->userDeletion->get('password') as $error)
            <div class="alert alert-danger mt-2">{{ $error }}</div>
            @endforeach
        <?php endif ?>

        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-account">
            Eliminar cuenta
        </button>

        <div class="modal fade" id="modal-delete-account" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">¿Seguro que quieres eliminar tu cuenta?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <p>Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>

                        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                            @csrf
                            @method('delete')

                            <div class="form-group row align-items-center mb-3">
                                <div class="col-sm-12">
                                    <input required type="password" placeholder="Password" class="form-control" id="password" name="password">
                                    <?php if ($errors->updatePassword->has('password')) : ?>
                                        @foreach ($errors->updatePassword->get('password') as $error)
                                        <div class="alert alert-danger mt-2">{{ $error }}</div>
                                        @endforeach
                                    <?php endif ?>
                                </div>
                            </div>

                            <div class="text-right mt-2">
                                <button type="button" class="btn btn-success mr-2" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Eliminar cuenta</button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
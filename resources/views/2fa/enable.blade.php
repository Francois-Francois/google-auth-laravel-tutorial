@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Secret Key</div>

                    <div class="panel-body">
                        Ouvrez Google Authenticator et scannez le QR code suivant :
                        <br />
                        <img alt="Image of QR barcode" src="{{ $image }}" />

                        <br />
                        Si vous avez un souci avec le QR code, vous pouvez taper ce code: <code>{{ $secret }}</code>
                        <br /><br />
                    </div>

                    <form class="form-horizontal" role="form" method="POST" action="/2fa/activate">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('totp') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Clé de sécurité</label>

                            <div class="col-md-6">
                                <input type="number" class="form-control" name="totp">

                                @if ($errors->has('totp'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('totp') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-mobile"></i>Valider
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center col-md-12">
            <div class="col-md-8">
                <div class="card" style="margin-top: 200px">
                    <div class="card-header">{{ __('Import Users') }}</div>
                    @if($errors->any())
                        <h5 style="color: red; text-align: center; font-size: 16px">{{$errors->first()}}</h5>
                    @endif
                    <div class="card-body">
                        <form method="POST" action="{{ route('import.users') }}">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input id="file" type="file" class="form-control" name="file" value=""
                                           required>

                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Import') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card" style="margin-top:50px">
                    <div class="card-header">{{ __('Import Content Groups') }}</div>
                    @if($errors->any())
                        <h5 style="color: red; text-align: center; font-size: 16px">{{$errors->first()}}</h5>
                    @endif
                    <div class="card-body">
                        <form method="POST" action="{{ route('import.content-groups') }}">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input id="file" type="file" class="form-control" name="file" value=""
                                           required>
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Import') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card" style="margin-top:50px">
                    <div class="card-body">
                        <div class="card-header">{{ __('Import Audit Data') }}</div>

                        <form method="POST" action="{{ route('audit.import') }}">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input id="file" type="file" class="form-control" name="file" value=""
                                           required>
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Import') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card" style="margin-top:50px">
                    <div class="card-body">
                        <div class="card-header">{{ __('Import Contracts') }}</div>
                        <form method="POST" action="{{ route('contracts.import') }}">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input id="file" type="file" class="form-control" name="file" value=""
                                           required>
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Import') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card" style="margin-top:50px">
                    <div class="card-body">
                        <div class="card-header">{{ __('Import Attachments') }}</div>
                        <form method="POST" action="{{ route('attachments.import') }}">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input id="file" type="file" class="form-control" name="file" value=""
                                           required>
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Import') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

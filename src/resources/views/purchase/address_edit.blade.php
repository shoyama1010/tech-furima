@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('main')
<div class="address-container">
    <h2>住所の変更</h2>
    <form method="POST" action="{{ route('purchase.address.update', $item->id) }}">
        @csrf
        <div class="form-group mb-3">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="address">住所</label>
            <input type="text" name="address" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="name">宛先名</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">更新する</button>
    </form>
</div>
@endsection
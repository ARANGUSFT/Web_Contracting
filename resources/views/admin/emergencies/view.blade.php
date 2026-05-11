@extends('admin.layouts.superadmin')

@section('title', 'Emergency Detail')

@section('content')
<div class="container-fluid px-4 py-4">
    <h2 class="mb-3">Emergency #{{ $emergency->id }}</h2>

    <div class="card p-3 mb-4">
        <h5>Company Info</h5>
        <p><strong>Submitted By:</strong> {{ optional($emergency->user)->company_name }}</p>
        <p><strong>Contact:</strong> {{ optional($emergency->user)->name }} ({{ optional($emergency->user)->email }})</p>
    </div>

    <div class="card p-3 mb-4">
        <h5>Details</h5>
        <p><strong>Type of Supplement:</strong> {{ $emergency->type_of_supplement }}</p>
        <p><strong>Date Submitted:</strong> {{ $emergency->date_submitted }}</p>
        <p><strong>Crew Assigned:</strong> {{ optional($emergency->crew)->name ?? 'Not Assigned' }}</p>
    </div>

    
<x-payment-form :item="$emergency" type="emergency" />

</div>
@endsection

@extends('admin.layouts.superadmin')

@section('title', 'Job Detail')

@section('content')
<div class="container-fluid px-4 py-4">
    <h2 class="mb-3">Job Request: {{ $job->job_number_name }}</h2>

    <div class="card p-3 mb-4">
        <h5>Company Info</h5>
        <p><strong>Company Name:</strong> {{ $job->company_name }}</p>
        <p><strong>Representative:</strong> {{ $job->company_rep }} ({{ $job->company_rep_phone }})</p>
        <p><strong>Email:</strong> {{ $job->company_rep_email ?? 'N/A' }}</p>
    </div>

    <div class="card p-3 mb-4">
        <h5>Customer Info</h5>
        <p><strong>Name:</strong> {{ $job->customer_first_name }} {{ $job->customer_last_name }}</p>
        <p><strong>Phone:</strong> {{ $job->customer_phone_number }}</p>
    </div>

    <div class="card p-3 mb-4">
        <h5>Job Address</h5>
        <p>{{ $job->job_address_street_address }} {{ $job->job_address_street_address_line_2 }}</p>
        <p>{{ $job->job_address_city }}, {{ $job->job_address_state }} {{ $job->job_address_zip_code }}</p>
    </div>

    <div class="card p-3 mb-4">
        <h5>Install Details</h5>
        <p><strong>Install Date Requested:</strong> {{ $job->install_date_requested }}</p>
        <p><strong>Crew Assigned:</strong> {{ optional($job->crew)->name ?? 'Not Assigned' }}</p>
    </div>

<x-payment-form :item="$job" type="job" />

</div>
@endsection

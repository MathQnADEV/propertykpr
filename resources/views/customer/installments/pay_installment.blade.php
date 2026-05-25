@extends('layouts.master')

@section('title', 'Pay Installment - X-Pro')

@section('content')
{{--
    ╔══════════════════════════════════════════════════════════════╗
    ║  TIDAK DIGUNAKAN — View ini sudah tidak aktif               ║
    ║  Alur customer: browse properti → detail → tombol WA        ║
    ║  untuk menghubungi agent langsung.                          ║
    ║  Semua pengurusan KPR dilakukan di sisi agent panel.        ║
    ╚══════════════════════════════════════════════════════════════╝

    ... isi asli view customer/installments/pay_installment dinonaktifkan ...
    (termasuk integrasi Midtrans snap.pay)
--}}
@endsection

@push('after-scripts')
{{-- Midtrans + jQuery scripts dinonaktifkan bersama view --}}
@endpush

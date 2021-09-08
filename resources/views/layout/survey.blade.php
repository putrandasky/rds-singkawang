<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="height:100%">

<head>
  <title>Real Demand Survey - Perencanaan Pembangunan Bandara Singkawang</title>
  <meta http-equiv="X-UA-Compatible" content="chrome=1">
  <meta charset="utf-8">
  <meta name="author" content="Radityo Putra Paripurna | alterfingers.com">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
  <meta name="description" content="Real Demand Survey - Perencanaan Pembangunan Bandara Singkawang">
  <meta name="keywords" content="Real Demand Survey - Perencanaan Pembangunan Bandara Singkawang">
  <meta property="og:image" content="{{ asset('favicon.png') }}">

  <meta property="og:title" content="Real Demand Survey - Perencanaan Pembangunan Bandara Singkawang" />
  <meta property="og:url" content="https://rds-singkawang.ptpii.co.id" />
  <meta property="og:description" content="Survei Preferensi Kebijakan Parkir, Layanan Feeder, Jalur Sepeda, dan Jalur Pejalan Kaki terhadap Layanan MRT di DKI Jakarta">
  <link type="image/png" href="{{ asset('favicon.png') }}" rel="icon">
  {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,700|Roboto:400,700&display=swap" rel="stylesheet"> --}}
   <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body style="height:100%">
  <!--[if IE]>
 <p>Applkasi ini tidak mendukung Internet Explorer

 </p>
<![endif]-->
  @yield('content')


</body>

</html>

<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- General CSS Files -->
<link rel="stylesheet" href="{{asset('assets/css/app.min.css')}}">
<!-- Template CSS -->
<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/components.css')}}">
<!-- Custom style CSS -->
<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
<link rel='shortcut icon' type='image/x-icon' href="{{asset('assets/img/favico-300x300.png')}}" />




<!-- ajax -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- themelayout -->
<script src="{{asset('assets/new_add/js/theme_setting.js')}}"></script>




<style>

.form-check-input:disabled~.form-check-label{
    cursor: auto !important;
}

[readonly]{
    pointer-events:none !important;
}

[disabled]{
    pointer-events:none !important;
}

/* *{
    font-family: 'Roboto',"Segoe UI", sans-serif;
}  */





</style>


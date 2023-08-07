<html>
<head>
    <title>{{ config('app.name') }} | Frontend API's Swagger</title>
    <link href="{{secure_asset('style.css')}}" rel="stylesheet">
</head>
<body>
<div id="swagger-ui"></div>
<script src="{{secure_asset('jquery-2.1.4.min.js')}}"></script>
<script src="{{secure_asset('swagger-bundle.js')}}"></script>
<script type="application/javascript">
    const ui = SwaggerUIBundle({
        url: "{{ secure_asset('swagger.yaml') }}",
        dom_id: '#swagger-ui',
    });
</script>
</body>
</html>

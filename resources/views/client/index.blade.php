<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel & Vue</title>
    <link rel="stylesheet" type="text/css" href="/css/app.css">
</head>
<body>
<div id="app">
    <passport-clients></passport-clients>
    <passport-authorized-clients></passport-authorized-clients>
    <passport-personal-access-tokens></passport-personal-access-tokens>
</div>
<script type="text/javascript" src="/js/app.js"></script>
</body>
</html>
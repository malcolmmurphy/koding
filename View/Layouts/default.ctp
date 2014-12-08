<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Planet Vitals</title>
<meta name="viewport" content="width=device-width,initial-scale=1">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="//www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script src="/js/selectordie.min.js"></script>
<script src="/js/main.js?nocache=<?php echo time(); ?>"></script>
<!--[if IE]>
  <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<link type="text/css" rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/dark-hive/jquery-ui.min.css"/>
<link type="text/css" rel="stylesheet" href="//fonts.googleapis.com/css?family=Julius+Sans+One|Raleway:500">
<link type="text/css" rel="stylesheet" href="/css/selectordie.css"/>
<link type="text/css" rel="stylesheet" href="/css/main.css?nocache=<?php echo time(); ?>"/>

</head>
<body>

<?php echo $this->fetch('content'); ?>

</body>
</html>

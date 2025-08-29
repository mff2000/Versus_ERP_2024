
<?php
 if(!function_exists('apache_get_modules') ){ 
 echo $res = 'Module Unavailable';
 phpinfo(); 
 exit; }
 if(in_array('mod_rewrite',apache_get_modules())) 
 echo $res = 'Module Available';
?>
<html>
<head>
<title>A mod_rewrite availability check !</title></head>
<body>
<p><?php echo apache_get_version(),"</p><p>mod_rewrite $res"; ?></p>
</body>
</html>

<?php echo phpinfo()?>
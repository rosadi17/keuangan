<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6 lt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7 lt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8 lt8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <title><?= $title ?></title>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Login and Registration Form with HTML5 and CSS3" />
        <meta name="keywords" content="html5, css3, form, switch, animation, :target, pseudo-class" />
        <meta name="author" content="Codrops" />
        <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>" />
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/demo.css') ?>" />
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/style.css') ?>" />
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/animate-custom.css') ?>" />
        <script type="text/javascript" src="<?= base_url('assets/js/jquery-1.8.3.js') ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery-ui-1.9.2.custom.js') ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery.cookies.js') ?>"></script>
        
        <script type="text/javascript">
        $(document).ready(function(){
            $.cookie('url', null);
            $('#username').focus();
            $('.warning').hide();
            $('input').live('keyup', function(e) {
                if (e.keyCode===13) {
                    loginForm();
                }
            });
        });
        function loginForm() {
            var Url = '<?= base_url('user/login') ?>';
                $.ajax({
                    type : 'POST',
                    url: Url,               
                    data: $('#loginform').serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if(data.id_user !== ''){
                            location.href='<?= base_url('') ?>';
                        } else {
                            $('#username-label').html('Username :');
                            $('#password-label').html('Password :');
                            $('#username').focus().select();
                            $('#username-check .loadingbar,#password-check .loadingbar').fadeOut();
                            $('.loading').hide();
                            $('.warning').show().html('Username atau password yang Anda masukkan salah !');
                        }            
                    }, error: function() {
                        $('.loading').hide();
                        $('.warning').show().html('Username atau password yang Anda masukkan salah !');
                    }
                });
                return false;
        }
        </script>
    </head>
    <body>
        <div class="container">
            <!-- Codrops top bar -->
            <div class="codrops-top">
                <a href="">
                    
                </a>
                <span class="right">
                    <a href=" http://tympanus.net/codrops/2012/03/27/login-and-registration-form-with-html5-and-css3/">
                        &nbsp;
                    </a>
                </span>
                <div class="clr"></div>
            </div><!--/ Codrops top bar -->
            <header>
                <h1>Sistem Informasi Keuangan <span><br/> Universitas Bhayangkara</span></h1>
            </header>
            <section>				
                <div id="container_demo" >
                    <!-- hidden anchor to stop jump http://www.css3create.com/Astuce-Empecher-le-scroll-avec-l-utilisation-de-target#wrap4  -->
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form  action="" autocomplete="off" id="loginform"> 
                                <h1>Log in</h1> 
                                <p> 
                                    <label for="username" class="uname" data-icon="u" > Your username </label>
                                    <input id="username" name="username" required="required" type="text" placeholder="myusername or mymail@mail.com"/>
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                                    <input id="password" name="password" required="required" type="password" placeholder="eg. X8df!90EO" /> 
                                </p>
                                <p class="keeplogin"> 
									<input type="checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" /> 
									<label for="loginkeeping">Keep me logged in</label>
								</p>
                                
                            </form>
                            <p class="login button"> 
                                <input type="button" value="Login" onclick="loginForm();" /> 
                            </p>
                            <center>&copy; 2014 - Muhammad Syafii M.Ak</center>
                        </div>
						
                    </div>
                </div>  
            </section>
        </div>
    </body>
</html>
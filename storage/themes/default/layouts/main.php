<!DOCTYPE html>
<html lang="<?php /* echo \Core\Localization::locale() ?>"<?php echo \Core\Localization::get('rtl') ? 'dir="rtl"':'' */ echo 'vi' ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-158D4YFWGM"></script>
		<script>
  		window.dataLayer = window.dataLayer || [];
  		function gtag(){dataLayer.push(arguments);}
  		gtag('js', new Date());

  		gtag('config', 'G-158D4YFWGM');
	</script>
    <!-- Google ads -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8159182794520687" crossorigin="anonymous"></script>
    <!-- Server custom script -->
    <script>
  // use the window.onload event to run the script after the HTML is loaded
  window.onload = function() {
    // get the image element by its id
    var qrImage = document.getElementById("qr-image");

    // get the initial image source
    var qrSrc = qrImage.src;

    // set a flag to indicate if the image is loaded or not
    var qrLoaded = false;

    // define a function to check the image source
    function checkImage() {
      // create a new image object
      var img = new Image();

      // set the onload event handler
      img.onload = function() {
        // if the image is loaded, set the flag to true and update the image element
        qrLoaded = true;
        qrImage.src = qrSrc;
// change the alt value to Done
qrImage.setAttribute("alt", "Done");
      };
      // set the onerror event handler
      img.onerror = function() {
        // if the image is not loaded, set the flag to false and hide the image element
        qrLoaded = false;
        hideImage(qrImage);
      };

      // set the image source to the initial source
      img.src = qrSrc;
    }

    // call the checkImage function every second until the image is loaded
    var interval = setInterval(function() {
      if (!qrLoaded) {
        checkImage();
      } else {
        clearInterval(interval);
      }
    }, 200);
  };
</script>
    <script>
        function hideImage(img) {img.style.display = "none";}
    </script>



        <?php meta() ?>

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo assets('frontend/libs/select2/dist/css/select2.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo assets('cookieconsent.min.css') ?>">
        <link rel="stylesheet" href="<?php echo assets('frontend/css/style'.(request()->cookie('darkmode') || themeSettings::config('homestyle', 'darkmode', true) ? '-dark' : '').'.min.css') ?>" id="stylesheet">
        <?php if(config('font')): ?>
            <link rel="preconnect" href="https://fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css2?family=<?php echo str_replace(' ', '+', config('font')) ?>:wght@300;400;600" rel="stylesheet">
            <style>body{font-family:'<?php echo config('font') ?>' !important}</style>
        <?php endif ?>            
        <script>
            var appurl = '<?php echo url() ?>';
        </script>
        <?php echo html_entity_decode(config('customheader')) ?>
        <?php block('header') ?>
    </head>
    <body>        
        <header class="header-transparent" id="header-main">            
        <?php if(\Helpers\App::loggedAs()): ?>
            <div class="alert alert-success mb-0 text-right p-2 rounded-0 d-block">
                <?php ee('You are logged in as another user') ?>
                <a href="<?php echo route('return') ?>" class="btn btn-light ml-2 btn-xs"><?php ee('Return to my account') ?></a>
            </div>
        <?php endif ?>            
            <nav class="navbar navbar-main navbar-expand-lg <?php echo themeSettings::config('homestyle', 'light', 'navbar-light bg-white', 'navbar-dark bg-dark') ?>" id="navbar-main">
                <div class="container">
                    <a class="navbar-brand" href="<?php echo route('home') ?>" title="<?php echo config('title') ?>">
                        <?php if(config('logo')): ?>
                            <img alt="<?php echo config('title') ?>" src="<?php echo uploads(config('logo')) ?>" id="navbar-logo">
                        <?php else: ?>
                            <h1 class="h5 mt-2 <?php echo themeSettings::config('homestyle', 'light', 'text-dark', 'text-white') ?>"><?php echo config('title') ?></h1>
                        <?php endif ?>
                    </a>                    
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main-collapse" aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>                    
                    <div class="mt-2 collapse navbar-collapse navbar-collapse-overlay" id="navbar-main-collapse">                        
                        <div class="position-relative">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main-collapse" aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <?php view('partials.main_menu') ?>
                    </div>
                </div>
            </nav>
        </header>

        <?php section() ?>

        <?php view('partials.footer') ?>
        
        <script src="<?php echo assets('bundle.pack.js') ?>"></script>        
        <?php if(config('cookieconsent')->enabled): ?>
            <script src="<?php echo assets('cookieconsent.min.js') ?>"></script>
        <?php endif ?>
        <?php block('footer') ?>
        <script type="text/javascript">
            var lang = <?php echo json_encode([       
                "error" => e('Please enter a valid URL.'),
                "cookie" => !empty(config('cookieconsent')->message) ? e(config('cookieconsent')->message) : e("This website uses cookies to ensure you get the best experience on our website."),
                "cookieok" => e("Got it!"),
                "cookiemore" => e("Learn more"),
                "cookielink" => !empty(config('cookieconsent')->link) ? config('cookieconsent')->link : route('page', ['terms']),
                "couponinvalid" => e("The coupon enter is not valid"),
                "minurl" => e("You must select at least 1 url."),
                "minsearch" => e("Keyword must be more than 3 characters!"),
                "nodata" => e("No data is available for this request."),
                "datepicker" => [
                    '7d' => 'Last 7 Days',
                    '3d' => 'Last 30 Days',
                    'tm' => 'This Month',
                    'lm' => 'Last Month',                    
                ]]) ?>
        </script> 
        <script>
            feather.replace({
                'width': '1em',
                'height': '1em'
            })
        </script>    
        <script src="<?php echo assets('frontend/js/app.min.js') ?>"></script>
        <script src="<?php echo assets('server.min.js') ?>"></script>
        <?php echo html_entity_decode(config('customfooter')) ?>
        <?php if(!empty(config('analytic'))): ?>
			<script async src='https://www.googletagmanager.com/gtag/js?id=<?php echo config('analytic') ?>'></script>
            <script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '<?php echo config('analytic') ?>');</script>
		<?php endif ?>
    </body>

</html>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>jQuery Tagify Plugin Demo</title>

<!--    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">-->
    <link href="/template/test/jquerysctipttop.css" rel="stylesheet" type="text/css">

<!--    <link href="tagify.css" rel="stylesheet" type="text/css">-->
    <link href="/template/test/tagify.css" rel="stylesheet" type="text/css">
<!--    <link rel="stylesheet" href="/template/Tiny-Text-Field-Based-Tags-Input-Plugin-For-jQuery-Tagify/dist/tagify.css" >-->

    <style>
        body { background-color:#f7f7f7; font-family:'Roboto';}
        .container { margin:150px auto; max-width:480px;}
    </style>
</head>

<body>
<div id="jquery-script-menu">
    <div class="jquery-script-center">
        <ul>
            <li><a href="https://www.jqueryscript.net/form/Tiny-Text-Field-Based-Tags-Input-Plugin-For-jQuery-Tagify.html">Download This Plugin</a></li>
            <li><a href="https://www.jqueryscript.net/">Back To jQueryScript.Net</a></li>
        </ul>
        <div class="jquery-script-ads"><script type="text/javascript"><!--
                google_ad_client = "ca-pub-2783044520727903";
                /* jQuery_demo */
                google_ad_slot = "2780937993";
                google_ad_width = 728;
                google_ad_height = 90;
                //-->
            </script>
            <script type="text/javascript" src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script></div>
        <div class="jquery-script-clear"></div>
    </div>
</div>
<div class="container">
    <h1>jQuery Tagify Plugin Demo</h1>
    <input name='tags' placeholder='write some tags' value='jQuery,Script,Net'>
</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<!--<script src="jQuery.tagify.js"></script>-->
<script src="/template/test/jQuery.tagify.js"></script>
<!--<script src="/template/Tiny-Text-Field-Based-Tags-Input-Plugin-For-jQuery-Tagify/dist/jQuery.tagify.min.js"></script>-->

<script>
    $('[name=tags]').tagify();
</script>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
</body>
</html>

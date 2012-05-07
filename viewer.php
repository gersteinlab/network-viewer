<?php

/* Begin configuration */
$base_url  = ".";
$data_path = "./data";
$nav = array(
    'home'   => '../',
    'viewer' => './viewer.php',
);
/* End configuration */

$net_files = array();

if (!is_dir($data_path))
    die($data_path . " data directory is not readable");

$dh = opendir($data_path);
if (!$dh)
    die("Cannot open " . $data_path . ".");

while (($file = readdir($dh)) !== FALSE)
    if ($file[0] != '.')
        array_push($net_files, $file);

closedir($dh);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>ENCODE-Nets Viewer</title>
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le styles -->
        <link href="assets/css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }

        </style>
        <!--<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">-->

        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="images/favicon.ico">
        <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">

        <style type="text/css">
            #cytoscapeweb {
                height: 500px;
            }
            #cytoscapeweb-note {
            }
        </style>

    </head>

    <body>

        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<? echo $nav['home']; ?>">Network Viewer</a>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li><a href="<? echo $nav['home']; ?>">Home</a></li>
                            <li class="active"><a href="<? echo $nav['viewer']; ?>">Viewer</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">

            <div class="page-header">
                <h1>Network Viewer</h1>
            </div>

            <div class="row">
                <div class="span12 well" id="cytoscapeweb">
                    <h1><small>Click one of the files below.</small></h1>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="span4">
                    <div class="well" style="padding: 8px 0;">
                        <ul class="nav nav-list">
                            <li class="nav-header">Files</li>

<? foreach ($net_files as $file): ?>
                            <li><a class="file-item" data-netfile="<? echo $file; ?>" href="#"><? echo $file; ?></a></li>
<? endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="span8">
                    <div class="well">
                        <h3>Info</h3>
                        <div id="cytoscapeweb-note">
                            <p>Click a data file.</p>
                        </div>
                        <div id="download-link"></div>
                    </div>
                </div>
            </div>
            <hr>
            <footer>
                <p>&copy; Gerstein Lab, Yale University 2012</p>
            </footer>

        </div> <!-- /container -->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/bootstrap.js"></script>

        <script type="text/javascript" src="assets/js/json2.min.js"></script>
        <script type="text/javascript" src="assets/js/AC_OETags.min.js"></script>
        <script type="text/javascript" src="assets/js/cytoscapeweb.min.js"></script>
        
        <script type="text/javascript">
            window.onload = function() {
                // id of Cytoscape Web container div
                var div_id = "cytoscapeweb";
                
                // initialization options
                var options = {
                    swfPath: "assets/swf/CytoscapeWeb",
                    flashInstallerPath: "assets/swf/playerProductInstall"
                };
                
                var vis = new org.cytoscapeweb.Visualization(div_id, options);
                
                // callback when Cytoscape Web has finished drawing
                vis.ready(function() {
                
                    // add a listener for when nodes and edges are clicked
                    vis.addListener("click", "nodes", function(event) {
                        handle_click(event);
                    })
                    .addListener("click", "edges", function(event) {
                        handle_click(event);
                    });
                    
                    function handle_click(event) {
                         var target = event.target;
                         var infostr = "";                        

                         infostr += "<dl class=\"dl-horizontal\">";

                         infostr += "<dt>Selected</dt><dd>"  + event.group + "</dd>";
                         if (event.group == "edges") {
                             infostr += "<dt>Interaction</dt><dd>" + target.data.interaction + "</dd>";
                             infostr += "<dt>Label</dt><dd>"       + target.data.label + "</dd>";
                         } else {
                             infostr += "<dt>Label</dt><dd>"     + target.data.label + "</dd>";
                             infostr += "<dt>Type</dt><dd>"      + target.data.type + "</dd>";
                             infostr += "<dt>Degree</dt><dd>"    + target.data.Degree + "</dd>";
                             infostr += "<dt>Outdegree</dt><dd>" + target.data.Outdegree + "</dd>";
                             infostr += "<dt>Indegree</dt><dd>"  + target.data.Indegree + "</dd>";
                             infostr += "<dt>EdgeCount</dt><dd>" + target.data.EdgeCount + "</dd>"
                         }
                  
                         infostr += "</dl>";
                         $("#cytoscapeweb-note").html(infostr);
                    }
               });

                $(".file-item").bind("click", function () {
                    var filename = $(this).attr("data-netfile");

                    $("#cytoscapeweb").removeClass("well");
                    $("#cytoscapeweb-note").html("<p>Click a node or edge.</p>");                   
                    $(".file-item").each(function () {
                        $(this).parent().removeClass();
                    });

                    $.ajax({ 
                        url: "<? echo $base_url; ?>/data/" + filename,
                        dataType: "text", 
                        success: function (data) { 
                            vis.draw({ 
                                layout: "Preset", 
                                network: data, 
                                panZoomControlVisible: true 
                            }); 
                        }, 
                        error: function(jqXHR, textStatus, errorThrown) { 
                            alert("Error loading file " + filename + ": " + textStatus); 
                        } 
                    });

                    $(this).parent().addClass("active");
                    $("#download-link").html('<hr><a href="<? echo $base_url; ?>/data/' + filename + '" class="btn btn-info">Download file &raquo;</a>');
                });

            };
        </script>
 
    </body>
</html>

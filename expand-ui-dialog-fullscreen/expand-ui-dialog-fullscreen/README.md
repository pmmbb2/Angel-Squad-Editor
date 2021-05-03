# jqueryui.dialog.fullmode

[![MIT License][license-image]][license-url]

[license-image]: http://img.shields.io/badge/license-MIT-blue.svg?style=flat
[license-url]: LICENSE

### simple jquery plugin for adding full mode button to jquery-ui dialog. 
<br>
<img src="https://user-images.githubusercontent.com/18533793/64288721-74d3d200-cf6b-11e9-8b78-73486fdaf2b6.png" width="600">

### version:
* 1.1.0

### Demo
* See demo <a href="https://meshesha.js.org/jqueryui.dialog.fullmode/" target="_blank">here</a>.

###  usage:
 include necessary css files:
 ```
<link rel="stylesheet" href="./path/to/jquery-ui.css">
```
 include necessary js files:
 ```
<script type="text/javascript" src="./path/to/jquery.min.js"></script>
<script type="text/javascript" src="./path/to/jquery-ui.min.js"></script>
<script type="text/javascript" src="./path/to/jqueryui.dialog.fullmode.js"></script>
 ```
 html body :
 ```
 ...
    <div id="demo" title="full mode button">
      <!-- content -->
    </div>
 ...
 ```
 add javascript:
 ```
<script type="text/javascript">
$(function () {
   $("#demo").dialog({
        width: 500,
        height: 300,
        dialogClass: "dialog-full-mode" /*must to add this class name*/
    });
    
    //initiate the plugin
    $(document).dialogfullmode(); 
});
</script>
 ``` 

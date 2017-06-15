<!DOCTYPE HTML>

<html>
<head>
	<title>Multiple Upload</title>

<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/dropzone.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/basic.min.css') ?>">

<script type="text/javascript" src="<?php echo base_url('assets/jquery.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/dropzone.min.js') ?>"></script>

<style type="text/css">

body{
	background-color: #E8E9EC;
}

.dropzone {
	margin-top: 100px;
	border:2px dashed #0087F7;
}

</style>

</head>
<body>


<div class="dropzone col-md-8 col-md-offset-2">

  <div class="dz-message">
   <h3> Drag and Drop Images Here</h3>
  </div>
<div>
	<a class="btn btn-success" href="<?= base_url('index.php/project_images') ?>">Done Uploading</a>
</div>
</div>

<div class="row clear-fix">
                <div class="col-md-12">
                    <div style="margin-top: 1%;">
                        <blockquote>
                        <ul class="list-inline"  id="gallery">
                             
                        </ul>
                        </blockquote>
                    </div>  
                </div>
   </div>


<script type="text/javascript">
$(document).ready(function (){
	loadgallery();
});
Dropzone.autoDiscover = false;

var image_upload= new Dropzone(".dropzone",{
url: "<?php echo base_url('index.php/project_images/project_image_upload') ?>",
maxFilesize: 2,
method:"post",
acceptedFiles:"image/*",
paramName:"userfile",
dictInvalidFileType:"This file type is not supported.",
addRemoveLinks:true,
});


//Event when Starting upload
image_upload.on("sending",function(a,b,c){
	a.token=Math.random();
	c.append("token_image",a.token); //Preparing token for each photo
});


//Event when photos are deleted
image_upload.on("removedfile",function(a){
	var token=a.token;
	$.ajax({
		type:"post",
		data:{token:token},
		url:"<?php echo base_url('index.php/project_images/project_image_delete') ?>",
		cache:false,
		dataType: 'json',
		success: function(){
			console.log("Image deleted");
		},
		error: function(){
			console.log("Error in deleting image");

		}
	});
});

function  loadgallery(){
           $.ajax({
              url:'<?php echo base_url("index.php/project_images/project_slider_images") ?>',
              type:'GET'
            }).done(function (data){
                $("#gallery").html(data);
                var btnDelete  = $("#gallery").find($(".btn-delete"));
                (btnDelete).on('click', function (e){
                    e.preventDefault();
                    var id = $(this).attr('id');
                    $(id).hide();
                    $.ajax({
                        type:"post",
						data:{id:id},
						url:"<?php echo base_url('index.php/project_images/project_deleteimg') ?>",
						cache:false,
						success: function(){
							console.log("Image deleted" + document.URL);

							$('#gallery').load('<?php echo base_url("index.php/project_images") ?>').fadeIn("slow");

						},
						error: function(){
							console.log("Error in deleting image");

						}
                    }).done(function (data){
                         
                    });
                });
                 
            });
           }


</script>

</body>
</html>
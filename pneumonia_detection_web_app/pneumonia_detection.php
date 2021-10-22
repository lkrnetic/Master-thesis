
<html lang="en">
<head>
    <title>Pneumonia detection</title>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest"> </script> 
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<style>
    img {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    p {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width:400px;
    }
    .wrapper {
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<?php


// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    echo '<img id="img" src="' . $target_dir . basename($_FILES["fileToUpload"]["name"]) . '" 
        style="width:400px;height:400px;margin:0 auto;"/>';
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //echo "<p>File is an image - " . $check["mime"] . ".</p>";
        $uploadOk = 1;
    } else {
        echo "<p>File is not an image.</p>";
        $uploadOk = 0;
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<p>File already exists.</p>";
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES["fileToUpload"]["size"] > 50000000) {
        echo "<p>Sorry, your file is too large.</p>";
        $uploadOk = 0;
    }

// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<p>Sorry, your file was not uploaded.</p>";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<p>The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.</p>";
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
        }
    }
}
?>
<div class="wrapper">
    <form action="" method="post" enctype="multipart/form-data"
          style="width: 400px;margin: 0 auto;">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>
</div>
<script>
<?php
if(isset($_POST["submit"])) {
	echo "async function run(){
        const MODEL_URL = 'http://localhost/pneumonia_detection_web_app/model.json';
        const model = await tf.loadLayersModel(MODEL_URL);
        console.log(model.summary());
		const image = document.getElementById('img');
		var img = tf.browser.fromPixels(image, 3).resizeNearestNeighbor([180,180]).toFloat();
		const offset = tf.scalar(255.0);
		var normalized  = img.div(offset);
		const axis = 0;
		normalized = normalized.expandDims(axis);
		console.log(normalized.shape);
		prediction = model.predict(normalized);
		console.log(prediction.dataSync());
		var pIndex = tf.argMax(prediction, 1).dataSync();
		var classNames = ['Normal', 'Pneumonia'];
		alert(classNames[pIndex]);
	}
	run();";
	}
?>
</script>
	</body>   
</html>

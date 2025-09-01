<?php
    $img = $_GET['360_image_url'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Panoramic View</title>

</head>

<body>

    <div class="main-container">
        
        <div class="image-container"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/105/three.js"
        integrity="sha512-wi1en5HQFr/+nc03XLj7iJohyUcclImFC3U5uOjYE+CM6FTla7scwzbuy56+Z5sIZ3sZy1KuNdjLIid4vhJMzg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./Js/Panolens.js"></script>
    <script>
        const panoramaImage = new PANOLENS.ImagePanorama("<?php echo $img; ?>");
        const imageContainer = document.querySelector(".image-container");

        const viewer = new PANOLENS.Viewer({
            container: imageContainer,
            autoRotate: true,
            autoRotateSpeed: 0.5,
            controlBar: true,
        });

        viewer.add(panoramaImage);   
    </script>
</body>

</html>
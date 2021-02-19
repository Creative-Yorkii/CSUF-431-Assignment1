<?php
$photoname = $_POST['photoName'];
$photoDate = $_POST['photoDate'];
$photoPhotographer = $_POST['photoPhotographer'];
$location = $_POST['photoLocation'];
$document_root = $_SERVER['DOCUMENT_ROOT'];
$file_name = $_FILES["fileToUpload"]["name"];

$target_dir = "C:/wamp64/www/uploads/";

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "C:/wamp64/www/uploads/" . basename($_FILES["fileToUpload"]["name"]));
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Photo Gallery</title>
        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  </script>
    </head>
        <body>
        <h1>Photo Gallery</h1>
          <form>
            <div class="form-row">
              <div class="col">
              <a href="index.html" class="btn btn-primary">Upload</a>
              </div>
            <div class = "col">
            <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
            <span id="selected">Name</span>
            <span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li><a href="#">Name</a></li>
              <li><a href="#">Date</a></li>
              <li><a href="#">Location</a></li>
              <li><a href="#">Photographer</a></li>
            </ul>
          </div>
        </div>
      </div>
    </form>
        <?php
$photoString = $photoname . "\t" . $photoDate . "\t" . $photoPhotographer . "\t" . $location . "\t" . $file_name . "\n";

@$openItems = fopen("C:/wamp64/www/info.txt", 'ab');

flock($openItems, LOCK_EX);fwrite($openItems, $photoString, strlen($photoString));flock($openItems, LOCK_UN);fclose($openItems);

$items = file("C:/wamp64/www/info.txt");
$number_of_photos = count($items);

$photoinfo = array();
$step = 0;

for ($i = 0;$i < $number_of_photos;$i++){ foreach (explode("\t", $items[$i]) as $ele) { $photoinfo[$i][$step] = $ele; $step++; } $step = 0;}

$sortedArray = array();

foreach ($photoinfo as $worth){ $photoKeyArray[] = array( 'name' => $worth[0], 'date' => $worth[1], 'photographer' => $worth[2], 'location' => $worth[3], 'img' => $worth[4] );}

$columns = array_column($photoKeyArray, 'name');
array_multisort($columns, SORT_ASC, $photoKeyArray);

?>

        <div id = 'container'>
            <div class = "col-sm-3 col-sm-6" id ="img-thumb">
                <div class=img-thumbnail >
                    <img src = "" id ="img" class="img-responsive" width="307" height="240"/>
                    <figcaption class="figure-caption" id = "name"> </figcaption>
                    <figcaption class="figure-caption" id ="date"> </figcaption>
                    <figcaption class="figure-caption" id = "location"> </figcaption>
                    <figcaption class="figure-caption" id = "photographer"> </figcaption>

                </div>
            </div>
        </div>

        <script>

        //https://www.w3schools.com/howto/howto_js_portfolio_filter.asp
        //https://stackoverflow.com/questions/48711834/filtering-image-gallery
        //https://stackoverflow.com/questions/46766718/filter-by-image-dont-want-show-all-image/46767033
        //https://www.geeksforgeeks.org/how-to-add-filter-with-portfolio-gallery-using-html-css-and-javascript/

        // References used for creating the sorting algorithm for the gallery array.

            var photoElement = document.getElementById('container');
            var unsortedArray =
            <?php echo json_encode($photoKeyArray, JSON_PRETTY_PRINT) ?>;

            $('.dropdown-menu a').click(function(){
                $('#selected').text($(this).text());
                var x = this.text;

               if(x == 'Date'){ unsortedArray.sort(function(a,b){ var photoDate1 = new Date(a.date), photoDate2 = new Date(b.date); return photoDate1- photoDate2; })
                display();
               }
               if(x == 'Name'){ unsortedArray.sort(function(a,b){ var photoName1=a.name.toLowerCase(), photoName2=b.name.toLowerCase(); if(photoName1 < photoName2){return -1;} if(photoName1 > photoName2){return 1;} else {return 0;}})
                display();
               }
               if(x == 'Location'){ unsortedArray.sort(function(a,b){ var photoName1=a.location.toLowerCase(), photoName2=b.location.toLowerCase(); if(photoName1 < photoName2){ return -1;} if(photoName1 > photoName2){return 1;} else {return 0;}})
                display();
               }
               if(x == 'Photographer'){ unsortedArray.sort(function(a,b){ var photoName1=a.photographer.toLowerCase(), photoName2=b.photographer.toLowerCase(); if(photoName1 < photoName2){return -1;} if(photoName1 > photoName2){return 1;} else {return 0;} })
                display();
               }
             });

            var arrayCopy = photoElement.cloneNode(true);
            for (var i = 0; i < unsortedArray.length; i++) {
                var gallaryArray = document.querySelectorAll("[id='container']");
                var arrayCopy = photoElement.cloneNode(true);
                var arrayLength = Object.keys(unsortedArray[i]).length;
                for (var j = 0; j < arrayLength; j++) {
                    var photoValue = unsortedArray[i].img;
                    var nameValue = unsortedArray[i].name;
                    var photographerValue = unsortedArray[i].photographer;
                    var dateValue = unsortedArray[i].date;
                    var locationValue = unsortedArray[i].location;
                    gallaryArray[i].querySelector("#name").innerHTML = nameValue;
                    gallaryArray[i].querySelector("#date").innerHTML = dateValue;
                    gallaryArray[i].querySelector("#location").innerHTML = locationValue;
                    gallaryArray[i].querySelector("#photographer").innerHTML = photographerValue;
                    gallaryArray[i].querySelector("#img").src = 'uploads/' + photoValue;
                }
                if(i+1 !== unsortedArray.length){
                document.body.appendChild(arrayCopy);
               }
            }
            function display(){
                for (var i = 0; i < unsortedArray.length; i++) {
                    var gallaryArray = document.querySelectorAll("[id='container']");
                    var arrayLength = Object.keys(unsortedArray[i]).length;
                    for (var j = 0; j < arrayLength; j++) {
                        var photoValue = unsortedArray[i].img;
                        var nameValue = unsortedArray[i].name;
                        var photographerValue = unsortedArray[i].photographer;
                        var dateValue = unsortedArray[i].date;
                        var locationValue = unsortedArray[i].location;
                        gallaryArray[i].querySelector("#name").innerHTML = nameValue;
                        gallaryArray[i].querySelector("#date").innerHTML = dateValue;
                        gallaryArray[i].querySelector("#location").innerHTML = locationValue;
                        gallaryArray[i].querySelector("#photographer").innerHTML = photographerValue;
                        gallaryArray[i].querySelector("#img").src = 'uploads/' + photoValue;
                      }
                  }
            }
        </script>
        </body>
</html>

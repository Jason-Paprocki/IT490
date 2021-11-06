<?php
// if login

if(!isset($_COOKIE['id'])){
    header("/login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
<style>
    body {
  padding: 2rem 0rem;
}

.like {
  font-size: 0.5rem;
}

.text {
    display: block;/* or inline-block */
  text-overflow: ellipsis;
  word-wrap: break-word;
  overflow: hidden;
  max-height: 1.6em;
  line-height: 1.8em;
}
</style>
    <title>API</title>
  </head>
  <body>
    <div class="container">

  
        <div class="row">
            
     
        </div>
      </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"
    ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
/*
used fetch api 
pass data to appenddata function and used jquery to show in div with class row

*/
      let response = fetch(
        "https://api.rescuegroups.org/v5/public/animals/search/available/dogs",
        {
          method: "POST",
          headers: {
              //api key
            Authorization: "Li91u8iK",
            Accept: "application/json",
            "Content-Type": "application/vnd.api+json",
          },
          // body: JSON.stringify(callData)
        }
      ).then(async (res) => {
        let data = await res.json();
        console.log(data.data);
        appendData(data.data);
      });

      function appendData(data) {
        var mainContainer = document.getElementById("myData");
        for (var i = 0; i < data.length; i++) {
       
          
          
            $(".row").append(
            "<div class='col-4 col-sm-4 col-md-4 col-lg-3'>"+
                "<div class='card'>"+
                 " <img class='card-img' height='150px'src='"+ data[i].attributes.pictureThumbnailUrl+"' alt='Vans'>"+
                 "<div class='card-img-overlay d-flex justify-content-end'>"+
                 "  <a href='#' class='card-link text-danger like'>"+
                 "    <i class='fas fa-heart'></i>"+
                 "  </a>"+
                 "</div>"+
                 "<div class='card-body'>"+
                 "  <h4 class='card-title'>"+ data[i].attributes.name+"</h4>"+
                 "  <h6 class='card-subtitle mb-2 text-muted'>"+ data[i].attributes.ageString+"</h6>"+
                 " <div style='  display: block;/* or inline-block */text-overflow: ellipsis;word-wrap: break-word;overflow: hidden;max-height: 20em;line-height: 1.8em;'> <p  class=' card-text'>"+ data[i].attributes.descriptionHtml+"</p></div>"+
                "  <div class='buy d-flex justify-content-between align-items-center'>"+
                 "      <button type='button'  class='btn btn-primary mt-3'>"+ data[i].attributes.ageGroup+"</button>"+
                "          <button type='button' class='btn btn-secondary btn-sm  mt-3'>"+ data[i].attributes.sex+"</button>"+
                 "  </div>"+
                 "</div>"+
               " </div>"+
              "</div>"

          );
        }
      }
    </script>
  </body>
</html>

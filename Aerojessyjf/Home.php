<!DOCTYPE html>
<html>
  <head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" type="text/css" href="header.css" />

    <style>
      .head {
        background-image: url(//content.skyscnr.com/m/785bdfcbe683606c/Large-Flights-hero-2.jpg?crop=1800px:1375px&amp;quality=60);
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
        min-height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        background-blend-mode: multiply;
        font-family: verdana;
      }

      .head p {
        margin-left: 900px;
        font-size: 100px;
        color: white;
      }
    </style>

  </head>

  <body>
    <div class="head">
      
      <?php 
        session_start();
        include_once('Header.php');
      ?>
      <p>Let the journey begin with us</p>
    </div>

    <div class="intro">
      <p class="heading">Our services</p>
      <hr width="8%" size="3px" color="#ff7b59" align="center" />
      <p style="text-align:center; width:120vw;">
        Plan your perfect trip Search Flights and Book to our most popular
        destinations
      </p>
      <p></p>
    </div>

    <div class="cities">
      <div class="city">
        <img src="./images/london.webp" alt="London" />
        <div class="cityname">
          <span>London</span><br />
          <a href="Search.php"></a>
        </div>
      </div>
      <div class="city">
        <img src="./images/newyork.webp" alt="New York" />
        <div class="cityname">
          <span>New York</span><br />
          <a href="Search.php"></a>
        </div>
      </div>
      <div class="city">
        <img src="./images/delhi.webp" alt="New Delhi" />
        <div class="cityname">
          <span>Delhi</span><br />
          <a href="Search.php"></a>
        </div>
      </div>
      <div class="city">
        <img src="./images/mumbai.webp" alt="Mumbai" />
        <div class="cityname">
          <span>Mumbai</span><br />
          <a href="Search.php"></a>
        </div>
      </div>
      <div class="city">
        <img src="./images/sydney.webp" alt="Sydney" />
        <div class="cityname">
          <span>Sydney</span><br />
          <a href="Search.php"></a>
        </div>
      </div>
      <div class="city">
        <img src="./images/toronto.webp" alt="Toronto" />
        <div class="cityname">
          <span>Toronto</span><br />
          <a href="Search.php"></a>
        </div>
      </div>
      <div class="city">
        <img src="./images/sanfra.webp" alt="San Francisco" />
        <div class="cityname">
          <span>San Francisco</span><br />
          <a href="Search.php"></a>
        </div>
      </div>
      <div class="city">
        <img src="./images/chicago.webp" alt="Chicago" />
        <div class="cityname">
          <span>Chicago</span><br />
          <a href="Search.php"></a>
        </div>
      </div>
      <div class="city">
        <img src="./images/washington.webp" alt="Washington" />
        <div class="cityname">
          <span>Washington</span><br />
          <a href="Search.php"></a>
        </div>
      </div>
    </div>

    <?php include('Footer.php'); ?> 
  </body>
</html>

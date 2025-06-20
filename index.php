<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>Homepage</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-plot-listing.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <link rel="stylesheet" href="assets/css/owl.css">

  </head>

<body>

<!-- header section starts -->
<?php include_once "assets/parts/header.php" ?>
<!-- header section ends -->



<!-- main banner section starts -->
<div class="main-banner">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="top-text header-text">
            <h6>Over 36,500+ Active Listings</h6>
            <h2>Find Nearby Places &amp; Things</h2>
          </div>
        </div>
        <div class="col-lg-12">
          <form id="search-form" name="gs" method="submit" role="search" action="#">
            <div class="row">
              <div class="col-lg-3 align-self-center">
                  <fieldset>
                      <select name="area" class="form-select" aria-label="Area" id="chooseCategory" onchange="this.form.click()">
                          <option selected>All Areas</option>
                          <option value="Village">Village</option>
                          <option value="Town">Town</option>
                          <option value="Modern City">Modern City</option>
                          <option value="Countryside">Countryside</option>
                          <option value="Desert">Desert</option>
                      </select>
                  </fieldset>
              </div>
              <div class="col-lg-3 align-self-center">
                  <fieldset>
                      <input type="address" name="address" class="searchText" placeholder="Enter a location" autocomplete="on" required>
                  </fieldset>
              </div>
              <div class="col-lg-3 align-self-center">
                  <fieldset>
                      <select name="price" class="form-select" aria-label="Default select example" id="chooseCategory" onchange="this.form.click()">
                          <option selected>Price Range</option>
                          <option value="$100 - $250">$100 - $250</option>
                          <option value="$250 - $500">$250 - $500</option>
                          <option value="$500 - $1000">$500 - $1,000</option>
                          <option value="$1000+">$1,000 or more</option>
                      </select>
                  </fieldset>
              </div>
              <div class="col-lg-3">                        
                  <fieldset>
                      <button class="main-button"><i class="fa fa-search"></i> Search Now</button>
                  </fieldset>
              </div>
            </div>
          </form>
        </div>
        <div class="col-lg-10 offset-lg-1">
          <ul class="categories">
            <li><a href="category.php"><span class="icon"><img src="assets/images/search-icon-01.png" alt="Home"></span>Apartments</a></li>
            <li><a href="listing.php"><span class="icon"><img src="assets/images/search-icon-02.png" alt="Food"></span>Food</a></li>
            <li><a href="#"><span class="icon"><img src="assets/images/search-icon-03.png" alt="Vehicle"></span>Car rental</a></li>
            <li><a href="#"><span class="icon"><img src="assets/images/search-icon-04.png" alt="Shopping"></span>Shopping</a></li>
            <li><a href="#"><span class="icon"><img src="assets/images/search-icon-05.png" alt="Travel"></span>Tours</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
<!-- main banner section ends -->


<!-- main popular categories section starts -->
<div class="popular-categories">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="section-heading">
              <h2>Popular Categories</h2>
              <h6>Check Them Out</h6>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="naccs">
              <div class="grid">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="menu">
                      <div class="first-thumb active">
                        <div class="thumb">
                          <span class="icon"><img src="assets/images/search-icon-01.png" alt=""></span>
                          Apartments
                        </div>
                      </div>
                      <div>
                        <div class="thumb">                 
                          <span class="icon"><img src="assets/images/search-icon-02.png" alt=""></span>
                          Food &amp; Life
                        </div>
                      </div>
                      <div>
                        <div class="thumb">                 
                          <span class="icon"><img src="assets/images/search-icon-03.png" alt=""></span>
                          Cars
                        </div>
                      </div>
                      <div>
                        <div class="thumb">                 
                          <span class="icon"><img src="assets/images/search-icon-04.png" alt=""></span>
                          Shopping
                        </div>
                      </div>
                      <div class="last-thumb">
                        <div class="thumb">                 
                          <span class="icon"><img src="assets/images/search-icon-05.png" alt=""></span>
                          Traveling
                        </div>
                      </div>
                    </div>
                  </div> 
                  <div class="col-lg-9 align-self-center">
                    <ul class="nacc">
                      <li class="active">
                        <div>
                          <div class="thumb">
                            <div class="row">
                              <div class="col-lg-5 align-self-center">
                                <div class="left-text">
                                  <h4>Your Home Away from Home. Choose Your Home.</h4>
                                  <p>Spacious and comfortable, our apartments are ideal for longer stays or travelers seeking the comforts of home. Each apartment features a fully equipped kitchen, a private bathroom, cozy living area, and modern amenities to ensure a relaxing and independent stay.</p>
                                  <div class="main-white-button"><a href="#"> Choose Apartments</a></div>
                                </div>
                              </div>
                              <div class="col-lg-7 align-self-center">
                                <div class="right-image">
                                  <img src="assets/images/tabs-image-01.jpg" alt="">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div>
                          <div class="thumb">
                            <div class="row">
                              <div class="col-lg-5 align-self-center">
                                <div class="left-text">
                                  <h4>Taste Local and International Cuisine</h4>
                                  <p>Enjoy a variety of delicious meals prepared with fresh, local ingredients. Whether you're craving traditional dishes or international cuisine, our dining options are designed to satisfy every taste. Breakfast, lunch, and dinner are served with care in a cozy and welcoming atmosphere.</p>
                                  <div class="main-white-button"><a href="#"> Check places</a></div>
                                </div>
                              </div>
                              <div class="col-lg-7 align-self-center">
                                <div class="right-image">
                                  <img src="assets/images/tabs-image-02.jpg" alt="Foods on the table">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div>
                          <div class="thumb">
                            <div class="row">
                              <div class="col-lg-5 align-self-center">
                                <div class="left-text">
                                  <h4>Your Journey Starts with Comfortable Transport!</h4>
                                  <p>Whether you need a rental car for exploring or a safe place to park your own vehicle, we’ve got you covered. Our hotel offers convenient car rental services and secure parking options, ensuring a smooth and stress-free travel experience.</p>
                                  <div class="main-white-button"><a href="listing.php">Choose Comrortable Transport</a></div>
                                </div>
                              </div>
                              <div class="col-lg-7 align-self-center">
                                <div class="right-image">
                                  <img src="assets/images/tabs-image-03.jpg" alt="cars in the city">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div>
                          <div class="thumb">
                            <div class="row">
                              <div class="col-lg-5 align-self-center">
                                <div class="left-text">
                                  <h4>Shop Local Treasures and International Brands</h4>
                                  <p>Discover nearby shopping spots ranging from local markets to popular malls. Whether you're looking for souvenirs, fashion, or essentials, you'll find plenty of options just minutes from your stay. Enjoy a convenient and fun shopping experience during your trip.</p>
                                  <div class="main-white-button"><a href="#"> Discover More</a></div>
                                </div>
                              </div>
                              <div class="col-lg-7 align-self-center">
                                <div class="right-image">
                                  <img src="assets/images/tabs-image-04.jpg" alt="Shopping Girl">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div>
                          <div class="thumb">
                            <div class="row">
                              <div class="col-lg-5 align-self-center">
                                <div class="left-text">
                                  <h4>Your Gateway to Local Adventures</h4>
                                  <p>Make the most of your journey with easy access to transportation and exciting travel opportunities. Whether you're planning day trips, guided tours, or airport transfers, we help you explore with confidence and comfort.</p>
                                  <div class="main-white-button"><a rel="nofollow" href="https://templatemo.com/contact">Read More</a></div>
                                </div>
                              </div>
                              <div class="col-lg-7 align-self-center">
                                <div class="right-image">
                                  <img src="assets/images/tabs-image-05.jpg" alt="Traveling Beach">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>          
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<!-- main popular categories section ends -->





<!-- footer section starts -->
<?php include_once "assets/parts/footer.php" ?>
<!-- footer section ends -->


  <!-- Scripts -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/custom.js"></script>
  
</body>

</html>
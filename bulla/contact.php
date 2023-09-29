<?php
header('Content-Type: text/html; charset=UTF-8');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include("../includes/db.php"); // Include the database connection script
include("../includes/functions.php");


$title = "CONTACT/CV";
        ob_start();
        // Include header.php and replace the title placeholder
        ob_flush(); // Flush the output buffer to send the header.php content
        include_once '../includes/header.php';
        $headerContent = ob_get_clean();
        $headerContent = str_replace('<!-- TITLE_PLACEHOLDER -->', $title, $headerContent);
        echo $headerContent;

?>
<div class="container mt-5">
<section id="contact" class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>Contact</h2>
                        <p><i>There is no time like the present</i></p>
                        <p id='email-placeholder'>_wait4it_</p>
                        <p>+41 (0)76 394 3982</p>

        <!-- Profile Picture -->
        <img src="http://ibulla.com/i/assets/img/portrait-marco-spitzbarth-23.jpg" alt="Marco Spitzbarth" class="img-fluid rounded-circle mx-auto d-block pt-3" width="50%">

                    </div>
                    <div class="col-lg-6">
                        <h2>Atelier</h2>
                        <p>Basislager 80/20<br>Aargauerstrasse 52-94<br>8048 Zürich</p>
                        <p>Tram 4 bis Würzgraben</p>
                        <iframe width="100%" height="350" src="https://www.openstreetmap.org/export/embed.html?bbox=8.494998514652254%2C47.39184357666707%2C8.497855067253115%2C47.393877237765665&amp;layer=mapnik&amp;marker=47.3928604170262%2C8.496426790952682" style="border: 1px solid black"></iframe><br/><small><a href="https://www.openstreetmap.org/?mlat=47.39286&amp;mlon=8.49643#map=19/47.39286/8.49643">View Larger Map</a></small>
                    </div>
                </div>
            </div>
    </section>
</div>

<section class="bg-dark pt-3 pb-3">
<div class="container mt-5 mb-5">
  <div class="card">
        <div class="card-body">
          <!-- Bio Heading -->
          <h2 class="card-title">Curriculum Vitae</h2>

<table class="cv-table">
    <thead><tr><th colspan='2'>Steps</th></tr></thead>
    <tbody>
        <tr><td width="25%">2020</td><td>Guest lecturer at ZHdK, AI and image recognition</td></tr>
        <tr><td>2016 - 2019</td><td>Bachelor Arts & Media, digitality Zurich University of the Arts (ZHdK)</td></tr>
        <tr><td>2018</td><td>Transcultural Collaboration, Intern</td></tr>
    </tbody>
</table>

<table class="cv-table">
    <thead><tr><th colspan='2'>Exhibitions (selection)</th></tr></thead>
    <tbody>
        <tr><td width="25%">2022</td><td>SudorMeta, Material Zürich</td></tr>
        <tr><td>2019</td><td>Diploma ZHdK, Zürich</td></tr>
        <tr><td>2018</td><td>Transcultural Collaboration, Hong Kong</td></tr>
        <tr><td>2018</td><td>Transcultural Collaboration, Shanghai</td></tr>
        <tr><td>2018</td><td>Besenkammer, Zurich</td></tr>
        <tr><td>2017</td><td>digital markets, MuDa, Zurich</td></tr>
        <tr><td>2017</td><td>Technopolitics Timeline, Connecting Spaces, Hong Kong</td></tr>
        <tr><td>2017</td><td>Highlights, ZHdK</td></tr>
    </tbody>
</table>

        </div>
  </div>
</div>
</section>

<?php
//if you want to show your cards in contac/cv also
echo '<section class="bg-dark p-1">';
echo '<div class="container mt-3 mb-5">';
        echo '<div class="card-columns">';
//echo '<div class="card-sizer col-md-3 col-lg-4"></div>';
        $onlineCardsHTML = generateOnlineCards($servername, $username, $password, $database);
    if ($onlineCardsHTML !== false) {
    // Display the generated cards HTML
    echo $onlineCardsHTML;
    } else {
    // Handle any errors here
    echo "Error generating online cards.";
    }
echo "</div></div></section>";



include_once '../includes/footer.php';
?>

<script>
        function revealEmail() {
            var email = 'bulla@ibulla.com'; // Replace with your email address

            var emailPlaceholder = document.getElementById('email-placeholder');
            
            // Display 'Email: ' first
            emailPlaceholder.innerHTML = 'Marco Spitzbarth<br>';

            // Wait for 2 seconds before revealing the email
            setTimeout(function() {
                for (var i = 0; i < email.length; i++) {
                    setTimeout(function(index) {
                        return function() {
                            emailPlaceholder.innerHTML += email[index];
                        };
                    }(i), i * 100); // 100 milliseconds delay between each character
                }
            }, 2000); // 2 seconds delay before revealing
        }

        // Call the function to reveal the email after the page loads
        window.onload = revealEmail;
</script>
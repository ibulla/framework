<?php

function generateDeepLink($id, $servername, $username, $password, $database) {
    // Initialize the deep link variable
    $deepLink = '';

    $mysqli = new mysqli($servername, $username, $password, $database);
        $mysqli->set_charset("utf8");

        // Check the connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

    // Prepare and execute a query to fetch the title from the url_mapping table
    $query = "SELECT title FROM url_mapping WHERE resource_id = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($title);

        // Fetch the title
        if ($stmt->fetch()) {
            // Build the deep link using the retrieved title
            $deepLink = "https://ibulla.com/i/works/" . $title;
        }

        // Close the statement
        $stmt->close();
    }

    // Return the deep link
    return $deepLink;
}

/*-----------------------*/

function beautifyDate($dbDate, $showTime = true) {
    // Convert the database date to a DateTime object
    $dateTime = new DateTime($dbDate);
    
    if ($showTime) {
        // Format the full date and time
        $beautifiedDate = $dateTime->format('j.n.Y H:i:s');
    } else {
        // Format only the year
        $beautifiedDate = $dateTime->format('Y');
    }
    
    return $beautifiedDate;
}

/*-----------------------*/

function generateBlogPage($resource_id,$servername, $username, $password, $database) {
    ob_start();

    try {
        // Create a new mysqli connection
        $mysqli = new mysqli($servername, $username, $password, $database);
        $mysqli->set_charset("utf8");

        // Check the connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare a SQL statement to fetch the blog entry by resource_id
        $stmt = $mysqli->prepare("SELECT id, title, content, created_at,touched FROM blog_posts WHERE id = ?");
        $stmt->bind_param("i", $resource_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $blogEntry = $result->fetch_assoc();

// Get the title from the blog entry
        $title = $blogEntry ? htmlspecialchars($blogEntry['title']) : 'Page Not Found';

        // Include header.php and replace the title placeholder
        ob_flush(); // Flush the output buffer to send the header.php content
        include_once 'includes/header.php';
        $headerContent = ob_get_clean();
        $headerContent = str_replace('<!-- TITLE_PLACEHOLDER -->', $title, $headerContent);
        echo $headerContent;

        // Check if a blog entry was found
        if ($blogEntry) {
            // Generate the blog entry content
            echo '<div class="container mt-3 mb-1">';
            echo '<div class="row">';
            echo '<div class="col-md-12">';
            echo '<h2>' . ($blogEntry['title']) . '</h2>';
            echo '<p>' . ($blogEntry['content']) . '</p>';
            echo '</div>';
            echo '</div>';
            //echo "<p class='mb-0 text-muted'><small id='blogDate'>".beautifyDate($blogEntry["created_at"],0)."|".beautifyDate($blogEntry["touched"])."</small></p>";
            echo "<p class='mb-0 text-muted'><a href='#' class='text-muted'><small id='blogDate'>".getYearsByCardID($resource_id,$servername, $username, $password, $database)." | ".$blogEntry['title']."</small></a></p>";
            echo '</div>';
        } else {
            echo '<p>Blog entry not found.</p>';
        }

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

        // Include footer.php
        include_once 'includes/footer.php';

        // Close the database connection
        $mysqli->close();
    } catch (Exception $e) {
        // Handle any exceptions or errors here
        echo '<p>Error: ' . $e->getMessage() . '</p>';
    }

    // Get and clear the output buffer, returning the captured HTML
    return ob_get_clean();
}

/*-----------------------*/

function formatTitle($title) {
    // Convert the title to lowercase
    $lowercaseTitle = trim(strtolower($title)); 
    // Replace whitespace with hyphens
    $formattedTitle = str_replace(' ', '-', $lowercaseTitle);
    
    return $formattedTitle;
}

/*-----------------------*/

function generateBlogHello($servername, $username, $password, $database) {
    ob_start();
    try {
        $mysqli = new mysqli($servername, $username, $password, $database);
        $mysqli->set_charset("utf8");
        // Check the connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        // Fetch all blog entries from the table
        $query = "SELECT * FROM blog_posts";
        $result = $mysqli->query($query);
// Get the title from the blog entry
        $title = "WORKS";
// Include header.php and replace the title placeholder
        ob_flush(); // Flush the output buffer to send the header.php content
        include_once 'includes/header.php';
        $headerContent = ob_get_clean();
        $headerContent = str_replace('<!-- TITLE_PLACEHOLDER -->', $title, $headerContent);
        echo $headerContent;

echo '<section class="bg-dark pt-3 pb-3">';
echo '<div class="container mt-3 mb-5">';
//echo generateYearList($servername, $username, $password, $database);
echo generateBlogListByY($servername, $username, $password, $database);
echo "</div></section>";

        // Include footer.php
        include_once 'includes/footer.php';
        // Close the database connection
        $mysqli->close();
    } catch (Exception $e) {
        // Handle any exceptions or errors here
        echo '<p>Error: ' . $e->getMessage() . '</p>';
    }
    // Get and clear the output buffer, returning the captured HTML
    return ob_get_clean();
}
/*-----------------------*/

function generateBlogListByY($servername, $username, $password, $database) {
$mysqli = new mysqli($servername, $username, $password, $database);
        $mysqli->set_charset("utf8");
        // Check the connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
    $resultHTML = ''; // Initialize the result variable
    // Fetch blog entries from the database, ordered by year (descending)
    $query = "SELECT * FROM cards WHERE online = 1 ORDER BY YEAR(position) DESC, created_at DESC";
    $result = mysqli_query($mysqli, $query);

    if (!$result) {
        // Handle database error
        return "Error fetching blog entries.";
    } else {
        $currentYear = null;
        $entriesInYear = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $year = $row['position'];
            $title = $row['title'];
            $blogID = $row['blog_id'];
            $imageURL = $row['image_url'];

$deepLink = generateDeepLink($blogID, $servername, $username, $password, $database);

            // If the year changes, start a new row
            if ($year !== $currentYear) {
                if (!empty($entriesInYear)) {
                    // Display the entries of the previous year in a row
                    $resultHTML .= '<div class="row mt-1">';
                    foreach ($entriesInYear as $entry) {
                        $resultHTML .= '<div class="col-md-4 mb-1 pr-2 pl-1">';
                        $resultHTML .= $entry;
                        $resultHTML .= '</div>';
                    }
                    $resultHTML .= '</div>';
                    $entriesInYear = array();
                }
                // Display the year as a bigger title
                $resultHTML .= '<a name="'.$year.'"><h2 class="mt-4 text-muted">'.$year.'</h2></a>';
                $currentYear = $year;
            }

            // Create a list item for each blog entry
            $entryHTML = '<div class="pr-1 ibullaYear">';
            if (!empty($imageURL)) {
                $entryHTML .= '<a class="ibullaCard" href="'.$deepLink.'"><img src="../assets/img/' . $imageURL . '" class="img-fluid" alt="' . $title . '" width="128"></a>';
            }
            //$entryHTML .= '<div class="media-body">';
            $entryHTML .= '<div class="texti-overlay"><a class="text-muted" href="'.$deepLink.'">' . $title . '</a></div>';
            //$entryHTML .= '</div>';
            $entryHTML .= '</div>';

            $entriesInYear[] = $entryHTML;
        }

        // Display the last year's entries in a row
        if (!empty($entriesInYear)) {
            $resultHTML .= '<div class="row mt-1">';
            foreach ($entriesInYear as $entry) {
                $resultHTML .= '<div class="col-md-4 mb-1 pr-2 pl-1">';
                $resultHTML .= $entry;
                $resultHTML .= '</div>';
            }
            $resultHTML .= '</div>';
        }

        mysqli_free_result($result);
    }

    return $resultHTML; // Return the generated HTML
}
/*-----------------------*/

function generateBlogListe($delete,$servername, $username, $password, $database) {
    ob_start();

    try {
        // Create a new mysqli connection
        $mysqli = new mysqli($servername, $username, $password, $database);
        $mysqli->set_charset("utf8");

        // Check the connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Fetch all blog entries from the table
        $query = "SELECT * FROM blog_posts ORDER BY id DESC";
        $result = $mysqli->query($query);

        // Check if blog entries were found
        if ($result->num_rows > 0) {
            echo '<div class="container mt-2">';
            echo '<div class="list-group">';
            while ($row = $result->fetch_assoc()) {
                // Generate HTML for each blog entry
                echo '<a href="manage_blog_posts_update.php?id='.$row['id'].'" class="list-group-item list-group-item-action">'.$row['id'].' --> ' . htmlspecialchars($row['title']) . '</a>';
                if($delete != 0){
                echo '<p><button class="btn btn-danger delete-btn float-right" data-id="'.$row['id'].'">Delete</button></p>';
                }

            }
            echo '</div>';
            echo '</div>';
        } else {
            echo '<p>No blog entries found.</p>';
        }

        // Close the database connection
        $mysqli->close();
    } catch (Exception $e) {
        // Handle any exceptions or errors here
        echo '<p>Error: ' . $e->getMessage() . '</p>';
    }

    // Get and clear the output buffer, returning the captured HTML
    return ob_get_clean();
}
/*-----------------------*/

function generateOnlineCards($servername, $username, $password, $database) {
    $mysqli = new mysqli($servername, $username, $password, $database);
        $mysqli->set_charset("utf8");
        // Check the connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

    $query = "SELECT * FROM cards WHERE online = 1 ORDER BY position DESC";
    $result = mysqli_query($mysqli, $query);

    if (!$result) {
        // Handle database error
        return false;
    }

$cardsHTML = '';

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $title = $row['title'];
        $imageURL = $row['image_url'];
        $description = $row['description'];
        $createdAt = $row['created_at'];
        $blogID = $row['blog_id'];
        $year = $row['position'];
$deepLink = generateDeepLink($blogID, $servername, $username, $password, $database);
        // Generate HTML for each card
        $cardsHTML .= '
            <div class="card mb-3">
                <a href="'.$deepLink.'">
                <img src="../assets/img/' . $imageURL . '" class="card-img-top" alt="' . $title . '">
                </a>
                <div class="card-body">
                    <h5 class="card-title"><small>' . $year . '</small> | ' . $title . '</h5>
                    <p class="card-text">' . $description . '</p>
                    <!--<p class="card-footer-bottom"><small class="text-muted">' . beautifyDate($createdAt,0) . '</small></p>-->
                    <div class="text-center">
                    <a href="'.$deepLink.'" class="btn btn-sm btn-light">Read More</a>
                    </div>
                </div>
            </div>
        ';
    }

    mysqli_free_result($result);

    return $cardsHTML;
}

/*-----------------------*/

function getYearsByCardID($cardID,$servername, $username, $password, $database) {
$mysqli = new mysqli($servername, $username, $password, $database);
        $mysqli->set_charset("utf8");
        // Check the connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
    // Initialize an empty array to store the years
    $years = array();
    // Sanitize the input to prevent SQL injection
    $cardID = mysqli_real_escape_string($mysqli, $cardID);
    // Construct the SQL query
    $query = "SELECT DISTINCT YEAR(position) AS year FROM cards WHERE blog_id = '$cardID'";
    // Execute the query
    $result = mysqli_query($mysqli, $query);
    if ($result) {
        // Fetch each year and add it to the array
        while ($row = mysqli_fetch_assoc($result)) {
            $years[] = $row['year'];
        }
        // Free the result set
        mysqli_free_result($result);
    } else {
        // Handle query execution error, e.g., log an error message
        return "no link | ";
    }
    // Close the database connection
    mysqli_close($mysqli);
    // Join the years array into a string with " | " separator
    $returnYears = implode(" | ", $years);
    // Return the years as a string
    return $returnYears;
}

/*-----------------------*/


function generateYearList($servername, $username, $password, $database) {
$mysqli = new mysqli($servername, $username, $password, $database);
        $mysqli->set_charset("utf8");
        // Check the connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

    // Initialize an empty array to store unique years and counts
    $yearsData = array();

    // Construct a SQL query to retrieve unique years and count cards for each year
    $query = "SELECT YEAR(position) AS year, COUNT(*) AS card_count FROM cards WHERE online = '1' GROUP BY year ORDER BY year DESC";

    // Execute the query
    $result = mysqli_query($mysqli, $query);

    if ($result) {
        // Fetch each row of data
        while ($row = mysqli_fetch_assoc($result)) {
            $year = $row['year'];
            $cardCount = $row['card_count'];

            // Create an anchor link and add it to the yearsData array
            //$yearLink = '<a class="link-info" href="#' . $year . '">' . $year . ' [' . $cardCount . ']</a>';
            $yearLink = '<button type="button" class="btn btn-sm btn-outline-light">' . $year . ' <span class="badge badge-light">' . $cardCount . '</span></button>';
            $yearsData[] = $yearLink;
        }

        // Free the result set
        mysqli_free_result($result);
    } else {
        // Handle query execution error, e.g., log an error message
        return "Error fetching data.";
    }

    // Close the database connection
    mysqli_close($mysqli);

    // Join the year links into a string
    //$yearList = implode(' | ', $yearsData);
    $yearList = implode(' ', $yearsData);

    // Return the HTML list
    return $yearList;
}

/*-----------------------*/

?>
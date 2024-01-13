<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .carousel-item img {
            width: 50%;
            height: 60vh; /* Adjust the height as needed */
            object-fit: cover;
        }

        /* Add any additional styles for your page */

    </style>
</head>
<h5>Barangay Tula-tula Organic livelihood for Gardening</h5>
<body>

    <!-- Bootstrap Carousel -->
    <div id="headerCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="image/1.png" class="d-block w-100" alt="Image 1">
            </div>
            <!-- Carousel Item 2 -->
            <div class="carousel-item">
                <img src="image/2.png" class="d-block w-100" alt="Image 2">
            </div>
            <!-- Carousel Item 3 -->
            <div class="carousel-item">
                <img src="image/3.png" class="d-block w-100" alt="Image 2">
            </div>
            <!-- Add more carousel items as needed -->

        </div>
        <!-- Carousel Controls -->
        <a class="carousel-control-prev" href="#headerCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#headerCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        /* Add any additional styles for your page */
    </style>
</head>

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Style for the entire navigation bar */
        .navbar {
            background-color: #f8f9fa; /* Light background color */
        }

        /* Style for the "etanom" word in the navigation bar */
        .navbar-brand.etanom {
            color: lightgreen;
            font-size: 24px; /* Adjust the font size as needed */
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(21, 122, 80);">
        <div class="container-fluid">
            <a class="navbar-brand" href="http://localhost/final/etanom/php-sqlite-forum/?page=topic">Ask community</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <!-- FAQs button in the navigation bar -->
                    <li class="nav-item">
                        <button type="button" class="btn btn-link nav-link" data-bs-toggle="modal"
                            data-bs-target="#faqModal">
                            FAQs
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <?php if ($_SESSION['type'] != 1): ?>
    <div class="container-fluid">
    <div class="card-body rounded-0 mx-auto" style="width: 1090px;"> <!-- Adjust the width as needed -->
        <div class="row">
            <h2 class="fw-bold mt-4 mb-3">Topics</h2>
                <?php
                    // Fetch hot topics based on certain criteria (e.g., comments, views)
                    $hot_topics_sql = "SELECT *, COALESCE((SELECT `fullname` FROM `user_list` WHERE `user_list`.`user_id` = `topic_list`.`user_id`),'N/A') AS `author` FROM `topic_list` WHERE `status` = 1 ORDER BY (SELECT COUNT(*) FROM `comment_list` WHERE `comment_list`.`topic_id` = `topic_list`.`topic_id`) DESC LIMIT 5";

                    $hot_topics_qry = $conn->query($hot_topics_sql);
                    while ($hot_row = $hot_topics_qry->fetchArray()):
                        $hot_date_created = new DateTime($hot_row['date_created'], new DateTimeZone('UTC'));
                        $hot_date_created->setTimezone(new DateTimeZone('Asia/Manila'));
                ?>
                 <a class="card rounded-0 shadow mb-3 text-reset text-decoration-none topic-item" href="./?page=view_topic&id=<?= $hot_row['topic_id'] ?>&fromFeed=true">
                    <div class="card-body rounded-0" style="width: 1090px;"> <!-- Adjust the width as needed -->
                        <div class="container-fluid">
                            <h6 class="fw-bolder"><?= $hot_row['title'] ?></h6> <!-- Fix the mismatched tag -->
                            <?php if (!empty($hot_row['image'])): ?>
                            <!-- Display uploaded image -->
                            <img src="path/to/uploaded/images/<?= $hot_row['image'] ?>" alt="Uploaded Image" class="img-fluid mb-3">
                            <?php endif; ?>
                            <hr>
                            <div class="lh-1 d-flex w-100 justify-content-between mb-3">
                                <div><small class="text-secondary">Author: <?= $hot_row['author'] ?></small></div>
                                <div><small class="text-secondary">Created At: <?= $hot_date_created->format("M d, Y g:i A") ?></small></div>
                            </div>
                            <p class="truncate-3"><?= $hot_row['description'] ?></p>
                        </div>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- FAQs Modal -->
    <div class="modal fade" id="faqModal" tabindex="-1" aria-labelledby="faqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="faqModalLabel">Frequently Asked Questions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                        // Fetch most commented questions with a limit of 3
                        $faq_sql = "SELECT *, COALESCE((SELECT `fullname` FROM `user_list` WHERE `user_list`.`user_id` = `topic_list`.`user_id`),'N/A') AS `author` FROM `topic_list` WHERE `status` = 1 ORDER BY (SELECT COUNT(*) FROM `comment_list` WHERE `comment_list`.`topic_id` = `topic_list`.`topic_id`) DESC LIMIT 3";

                        $faq_qry = $conn->query($faq_sql);
                        while ($faq_row = $faq_qry->fetchArray()):
                    ?>
                    <p>Q: <?= $faq_row['title'] ?></p>
                    <p>A: <?= $faq_row['description'] ?></p>
                    <?php endwhile; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
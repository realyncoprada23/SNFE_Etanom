<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Community</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        #post-form {
            margin-bottom: 20px;
        }

        .post {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }

        .comment {
            margin-left: 20px;
            border: 1px solid #eee;
            padding: 5px;
        }
    </style>
</head>
<body>

    <div id="post-form">
        <h2>Create a Post</h2>
        <textarea id="post-text" rows="4" cols="50" placeholder="Type your post..."></textarea><br>
        <button onclick="submitPost()">Post</button>
    </div>

    <div id="posts-container">
        <!-- Posts will be dynamically added here -->
    </div>

    <script>
        function submitPost() {
            var postText = document.getElementById("post-text").value;

            if (postText.trim() !== "") {
                var postContainer = document.getElementById("posts-container");

                var postDiv = document.createElement("div");
                postDiv.className = "post";
                postDiv.innerHTML = "<p>" + postText + "</p>";

                var commentDiv = document.createElement("div");
                commentDiv.className = "comment";
                commentDiv.innerHTML = "<textarea rows='2' cols='40' placeholder='Type your comment...'></textarea><br><button onclick='submitComment(this)'>Comment</button>";

                postDiv.appendChild(commentDiv);
                postContainer.appendChild(postDiv);

                document.getElementById("post-text").value = "";
            }
        }

        function submitComment(button) {
            var commentText = button.previousElementSibling.value;

            if (commentText.trim() !== "") {
                var commentDiv = document.createElement("div");
                commentDiv.className = "comment";
                commentDiv.innerHTML = "<p>" + commentText + "</p>";

                button.parentElement.appendChild(commentDiv);
                button.previousElementSibling.value = "";
            }
        }
    </script>

</body>
</html>

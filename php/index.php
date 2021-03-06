<?php
session_start();
header('Access-Control-Allow-Origin: *');
require_once 'database.php';

$all_scores = $database->query(
    "(SELECT users.username, games.user_id, games.points, games.level FROM
    (SELECT user_id, MAX(points) AS points FROM `games`
    GROUP BY user_id) AS max_scores
    JOIN `games` ON max_scores.user_id = games.user_id AND max_scores.points = games.points
    JOIN `users` ON games.user_id = users.user_id
    ORDER BY points DESC LIMIT 10)
    UNION
    (SELECT users.username, games.user_id, games.points, games.level FROM
    users JOIN games ON users.user_id = games.user_id
    WHERE users.user_id = ".$_SESSION['user_id']."
    ORDER BY points DESC LIMIT 1)"); //DESC means in biggest to smallest (descending)
    // query takes the username, user_id, points and level from the database. the data taken for each user is the max points with the corresponding level
    // then the data kept is the best 10 scores in the database. After finding he best 10 players in the game, if the current user's score is
    // not in the top 10, then the bes score of the user is also added to the data that is sent to the game

$to_print = [];
while ($row = $all_scores->fetch_assoc()) {
    $row["selected"] = ($row["user_id"] == $_SESSION['user_id']);
    $to_print[] = $row; // adding to list
}

echo json_encode($to_print);
<?php

require_once 'VotingService.php';

$userId = $_GET["userId"];
$votingService = new VotingService();

$votingChoice = $votingService->getVotingChoiceByUserId($userId);

if(empty($votingChoice)) {
    $res = $votingService->getVotingResults();

    $votingResults = array();

    foreach($res as $key => $value) {
        if($key == 'votedItemName' && $votingResults[$value]){
            $votingResults[$value] += 1;
        } else {
            $votingResults[$value] = 1;
        }
    }

    arsort($votingResults);
    return json_encode($votingResults);
}else {
    return $votingChoice;
}

?>